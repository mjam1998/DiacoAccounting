<?php

namespace App\Http\Controllers;


use App\Models\Bank_account;
use App\Models\Category;
use App\Models\Debt;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;

class UserController extends Controller
{
    public function adminHome()
    {
        $categories = Category::all();
        $sellCategories=$categories->where('transaction_type_id',1);
        $costCategories=$categories->where('transaction_type_id',2);
        $adCategories=$categories->where('transaction_type_id',3);
        $bankAccounts=Bank_account::query()->whereNot('id',1)->get();
        $transactions=Transaction::query()->orderBy('id','desc')->get();
        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->created_at);
            $persianDate = Jalalian::fromCarbon($date)->format('Y/m/d');


            $transaction->persian_date = $persianDate;
        }
        return view('adminPanel.transaction',['sellCategories'=>$sellCategories,'costCategories'=>$costCategories,'adCategories'=>$adCategories,'bankAccounts'=>$bankAccounts,'transactions'=>$transactions]);
    }
    public function adminList(){
        $users=User:: query()->withTrashed()->get();
        return view('adminPanel.user.adminList',compact('users'));
    }
    public function adminAdd(){
        return view('adminPanel.user.adminAdd');
    }
    public  function  store(Request $request)
    {
        $data=$request->all();
        $data['password']=Hash::make($data['password']);
        User::query()->create($data);
        return redirect(route('AdminList'));

    }

    public function editAdmin($id)
    {
        $user=User::query()->find($id);
        return view('adminPanel.user.adminEdit',compact('user'));
    }

    public function edit(request $request)
    {
        $data=$request->all();
        $data['password']=Hash::make($data['password']);
        $user=User::query()->find($data['id']);
        $user->update($data);
        return redirect(route('AdminList'));
    }

    public function delete($id)
    {
        $user=User::query()->find($id);
        $user->delete();
        return redirect(route('AdminList'));
    }
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect(route('AdminList'));
    }

    public function logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function bankAccountPrimary()
    {
        $bank=Bank_account::query()->where('status',1)->first();

        return view('adminPanel.bankAccount.primaryAccount',compact('bank'));
    }

    public function editPrimary( Request $request)
    {
        $bank=Bank_account::query()->where('status',1)->first();
        $bank->update([
            'name'=>$request['name'],
            'bank_name'=>$request['bank_name'],
            'account_number'=>$request['account_number'],
            'account_card'=>$request['account_card'],
            'account_shaba'=>$request['account_shaba'],
        ]);
        return redirect(route('bankAccount.primary'))->with('editAccountPrimary','اطلاعات با موفقیت بروز شد.');
    }

    public function bankAccountList()
    {
        $banks=Bank_account::query()->where('status',2)->get();
        return view('adminPanel.bankAccount.accountsList',compact('banks'));
    }

    public function addBankAccount(Request $request)
    {
        Bank_account::query()->create([
            'name'=>$request['name'],
            'bank_name'=>$request['bank_name'],
            'account_number'=>$request['account_number'],
            'account_card'=>$request['account_card'],
            'account_shaba'=>$request['account_shaba'],
            'status'=>2
        ]);
        return redirect(route('bankAccount.list'))->with('addAccount','اطلاعات با موفقیت افزوده شد.');
    }

    public function percentTransactionCategory()
    {
        $categories=Category::query()->where('transaction_type_id',1)->get();
        return view('adminPanel.percentSellTransaction',compact('categories'));
    }

    public function percentTransactionCategorySubmit(Request $request)
    {
        $category=Category::query()->where('id',$request['id'])->first();

        $category->update([
          'commission'=>$request['commission'],
            'tax'=>$request['tax'],
            'logistics'=>$request['logistics']
        ]);
        return redirect(route('percentTransactionCategory'))->with('submitPercent','اطلاعات با موفقیت ثبت شد.');
    }

    public function transactionSubmit(Request $request)
    {
        $data = $request->all();
        $normalizedDate = $this->normalizePersianDate($request['created_at']);

        // تبدیل تاریخ ورودی به Jalalian
        $inputJalaliDate = Jalalian::fromFormat('Y/m/d', $normalizedDate);

        // تاریخ میلادی برای ذخیره در تراکنش
        $gregorianDate = $inputJalaliDate->toCarbon()->format('Y-m-d');

        if($request['transaction_type_id'] == 1){
            if ($request['category_id'] == 1 || $request['category_id'] ==2){ // snap pay
                $category = Category::query()->where('id', $request['category_id'])->first();
                $commission = $category['commission'];
                $tax = $category['tax'];
                $logistics = $category['logistics'];
                $amountCommission = $request['sellPrice'] * ($commission/100);
                $amountTax = $amountCommission * ($tax/100);
                $debtAmount = ($request['sellPrice'] - ($amountCommission + $amountTax)) / 4;
                $profit = $request['sellPrice'] - ($request['buyPrice'] + $amountCommission + $amountTax + $logistics);


                $currentJalali = $inputJalaliDate;

                // تبدیل به محدوده میلادی برای جستجو
                $startOfMonth = $currentJalali->getFirstDayOfMonth()->toCarbon();
                $endOfMonth = $currentJalali->getFirstDayOfMonth()->addMonths(1)->toCarbon();

                $debt = Debt::query()->where('category_id', $request['category_id'])
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->first();

                if($debt != null){
                    $debt->update([
                        'debt1' => $debt['debt1'] + $debtAmount,
                        'debt2' => $debt['debt2'] + $debtAmount,
                        'debt3' => $debt['debt3'] + $debtAmount,
                        'debt4' => $debt['debt4'] + $debtAmount,
                    ]);
                } else {

                    $debt1Date = $inputJalaliDate->addMonths(1)->format('Y-m-05');
                    $debt2Date = $inputJalaliDate->addMonths(2)->format('Y-m-05');
                    $debt3Date = $inputJalaliDate->addMonths(3)->format('Y-m-05');
                    $debt4Date = $inputJalaliDate->addMonths(4)->format('Y-m-05');

                    // تبدیل به میلادی
                    $debt1Gregorian = Jalalian::fromFormat('Y-m-d', $debt1Date)->toCarbon();
                    $debt2Gregorian = Jalalian::fromFormat('Y-m-d', $debt2Date)->toCarbon();
                    $debt3Gregorian = Jalalian::fromFormat('Y-m-d', $debt3Date)->toCarbon();
                    $debt4Gregorian = Jalalian::fromFormat('Y-m-d', $debt4Date)->toCarbon();

                    $debtn=Debt::query()->create([
                        'category_id' => $request['category_id'],
                        'debt1' => $debtAmount,
                        'debt2' => $debtAmount,
                        'debt3' => $debtAmount,
                        'debt4' => $debtAmount,
                        'debt1_time' => $debt1Gregorian,
                        'debt2_time' => $debt2Gregorian,
                        'debt3_time' => $debt3Gregorian,
                        'debt4_time' => $debt4Gregorian,
                        'created_at' => $gregorianDate
                    ]);
                }

                Transaction::query()->create([
                    'transaction_type_id' => 1,
                    'debt_id'=>$debtn['id'],
                    'category_id' => $request['category_id'],
                    'buyPrice' => $request['buyPrice'],
                    'sellPrice' => $request['sellPrice'],
                    'isDebt' => true,
                    'description' => $request['description'],
                    'profit' => $profit,
                    'commission' => $amountCommission,
                    'logistics' => $logistics,
                    'tax' => $amountTax,
                    'created_at' => $gregorianDate
                ]);


            }
            elseif($request['category_id'] == 3){ //

                $category = Category::query()->where('id', $request['category_id'])->first();
                $commission = $category['commission'];
                $tax = $category['tax'];
                $logistics = $category['logistics'];
                $amountCommission = $request['sellPrice'] * ($commission/100);
                $amountTax = $amountCommission * ($tax/100);
                $debtAmount = $request['sellPrice'] - ($amountCommission + $amountTax) ;
                $profit = $request['sellPrice'] - ($request['buyPrice'] + $amountCommission + $amountTax + $logistics);


                $currentJalali = $inputJalaliDate;



                $debt = Debt::query()->where('category_id', $request['category_id'])
                    ->where('created_at',$gregorianDate )
                    ->first();

                if($debt != null){

                    $debt->update([
                        'debt1' => $debt['debt1'] + $debtAmount

                    ]);

                }
                if ($debt== null){

                    $debt1Date = $inputJalaliDate->addDays(15)->format('Y-m-d');


                    // تبدیل به میلادی
                    $debt1Gregorian = Jalalian::fromFormat('Y-m-d', $debt1Date)->toCarbon();


                    $debtn= Debt::query()->create([
                        'category_id' => $request['category_id'],
                        'debt1' => $debtAmount,

                        'debt1_time' => $debt1Gregorian,

                        'created_at' => $gregorianDate
                    ]);
                }

                Transaction::query()->create([
                    'transaction_type_id' => 1,
                    'debt_id'=>$debtn['id'],
                    'category_id' => $request['category_id'],
                    'buyPrice' => $request['buyPrice'],
                    'sellPrice' => $request['sellPrice'],
                    'isDebt' => true,
                    'description' => $request['description'],
                    'profit' => $profit,
                    'commission' => $amountCommission,
                    'logistics' => $logistics,
                    'tax' => $amountTax,
                    'created_at' => $gregorianDate
                ]);


            }
            elseif ($request['category_id'] == 8){ //پاساژ

                $category = Category::query()->where('id', $request['category_id'])->first();
                $commission = $category['commission'];
                $tax = $category['tax'];
                $logistics = $category['logistics'];
                $amountCommission = $request['sellPrice'] * ($commission/100);
                $amountTax = $amountCommission * ($tax/100);
                $debtAmount = $request['sellPrice'] - ($amountCommission + $amountTax) ;
                $profit = $request['sellPrice'] - ($request['buyPrice'] + $amountCommission + $amountTax + $logistics);


                $currentJalali = $inputJalaliDate;



                $debt = Debt::query()->where('category_id', $request['category_id'])
                    ->where('created_at',$gregorianDate )
                    ->first();

                if($debt != null){

                    $debt->update([
                        'debt1' => $debt['debt1'] + $debtAmount

                    ]);

                }
                if ($debt== null){

                    $debt1Date = $inputJalaliDate->addDays(2)->format('Y-m-d');


                    // تبدیل به میلادی
                    $debt1Gregorian = Jalalian::fromFormat('Y-m-d', $debt1Date)->toCarbon();


                    $debtN=Debt::query()->create([
                        'category_id' => $request['category_id'],
                        'debt1' => $debtAmount,

                        'debt1_time' => $debt1Gregorian,

                        'created_at' => $gregorianDate
                    ]);
                }

                Transaction::query()->create([
                    'transaction_type_id' => 1,
                    'debt_id'=>$debtN['id'],
                    'category_id' => $request['category_id'],
                    'buyPrice' => $request['buyPrice'],
                    'sellPrice' => $request['sellPrice'],
                    'isDebt' => true,
                    'description' => $request['description'],
                    'profit' => $profit,
                    'commission' => $amountCommission,
                    'logistics' => $logistics,
                    'tax' => $amountTax,
                    'created_at' => $gregorianDate
                ]);


            }
            else{
                $category = Category::query()->where('id', $request['category_id'])->first();
                $commission = $category['commission'];
                $tax = $category['tax'];
                $logistics = $category['logistics'];
                $amountCommission = $request['sellPrice'] * ($commission/100);
                $amountTax = $amountCommission * ($tax/100);

                $profit = $request['sellPrice'] - ($request['buyPrice'] + $amountCommission + $amountTax + $logistics);

                Transaction::query()->create([
                    'transaction_type_id' => 1,
                    'category_id' => $request['category_id'],
                    'buyPrice' => $request['buyPrice'],
                    'sellPrice' => $request['sellPrice'],
                    'isDebt' => false,
                    'description' => $request['description'],
                    'profit' => $profit,
                    'commission' => $amountCommission,
                    'logistics' => $logistics,
                    'tax' => $amountTax,
                    'created_at' => $gregorianDate
                ]);

                $cash=Bank_account::query()->where('status',1)->first();
                $cash->update([
                   'wallet' => $cash['wallet'] + $profit
                ]);

            }


        }
        if($request['transaction_type_id'] == 2 ){
            Transaction::query()->create([
                'transaction_type_id' => 2,
                'category_id' => $request['category_id'],
                'buyPrice' => $request['buyPrice'],


                'description' => $request['description'],


                'created_at' => $gregorianDate
            ]);

            $cash=Bank_account::query()->where('status',1)->first();
            $cash->update([
               'wallet' => $cash['wallet'] - $request['buyPrice']
            ]);
        }
        if($request['transaction_type_id'] == 3 ){
            Transaction::query()->create([
                'transaction_type_id' => 3,
                'category_id' => $request['category_id'],
                'buyPrice' => $request['buyPrice'],


                'description' => $request['description'],
                'bank_accounts_id'=> $request['bank_accounts_id'],

                'created_at' => $gregorianDate
            ]);
            if($request['bank_accounts_id'] ==1){
                $cash=Bank_account::query()->where('status',1)->first();
                $cash->update([
                    'wallet' => $cash['wallet'] - $request['buyPrice']
                ]);
            }

        }
        return redirect()->route('AdminHome')->with('success','تراکنش با موفقیت ثبت شد.');
    }
    public function normalizePersianDate($date)
    {
        // تبدیل اعداد فارسی به انگلیسی
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $date = str_replace($persian, $english, $date);

        // حذف فاصله و کاراکترهای اضافی
        $date = preg_replace('/[^\d\/]/', '', $date);

        return $date;
    }
    public function convertToPersianNumbers($string)
    {
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return str_replace($english, $persian, $string);
    }

}
