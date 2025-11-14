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
use Illuminate\Support\Facades\Log;
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

        $sellTransaction=$transactions->where('transaction_type_id',1);
        $taxAmount=$sellTransaction->sum('tax');
        $logisticsAmount=$sellTransaction->sum('logistics');
         $commissionAmount=$sellTransaction->sum('commission');
        $taxAmount=$sellTransaction->sum('tax');
        $sellTransTotalAmount=$sellTransaction->sum('sellPrice');
        $profitTotalAmount=$sellTransaction->sum('profit');
        $snapTrans=$sellTransaction->where('category_id',1)->sum('sellPrice');
        $snapProfit=$sellTransaction->where('category_id',1)->sum('profit');
        $torobTrans=$sellTransaction->where('category_id',2)->sum('sellPrice');
        $torobProfit=$sellTransaction->where('category_id',2)->sum('profit');
        $basalamTrans=$sellTransaction->where('category_id',3)->sum('sellPrice');
        $basalamProfit=$sellTransaction->where('category_id',3)->sum('profit');
        $siteTrans=$sellTransaction->where('category_id',4)->sum('sellPrice');
        $siteProfit=$sellTransaction->where('category_id',4)->sum('profit');
        $naghdiTrans=$sellTransaction->where('category_id',5)->sum('sellPrice');
        $naghdiProfit=$sellTransaction->where('category_id',5)->sum('profit');
        $digikalaTrans=$sellTransaction->where('category_id',6)->sum('sellPrice');
        $digikalaProfit=$sellTransaction->where('category_id',6)->sum('profit');
        $snapshopTrans=$sellTransaction->where('category_id',7)->sum('sellPrice');
        $snapshopProfit=$sellTransaction->where('category_id',7)->sum('profit');
        $pasajTrans=$sellTransaction->where('category_id',8)->sum('sellPrice');
        $pasajProfit=$sellTransaction->where('category_id',8)->sum('profit');
        $instaTrans=$sellTransaction->where('category_id',9)->sum('sellPrice');
        $instaProfit=$sellTransaction->where('category_id',9)->sum('profit');

        $costTransaction=$transactions->where('transaction_type_id',2);
        $costTransTotalAmount=$costTransaction->sum('buyPrice');
        $costKari=$costTransaction->where('category_id',10)->sum('buyPrice');
        $costShakhsi=$costTransaction->where('category_id',11)->sum('buyPrice');

        $adsTransaction=$transactions->where('transaction_type_id',3);
        $adsTransTotalAmount=$adsTransaction->sum('buyPrice');
        $snapAds=$adsTransaction->where('category_id',12)->sum('buyPrice');
        $torobAds=$adsTransaction->where('category_id',13)->sum('buyPrice');
        $instaAds=$adsTransaction->where('category_id',14)->sum('buyPrice');
        $basalamAds=$adsTransaction->where('category_id',15)->sum('buyPrice');
        $googleAds=$adsTransaction->where('category_id',16)->sum('buyPrice');
        $pasajAds=$adsTransaction->where('category_id',17)->sum('buyPrice');
        $seoAds=$adsTransaction->where('category_id',18)->sum('buyPrice');


        $wallet=Bank_account::query()->where('status',1)->first();


        $totalUnpaid = Debt::all()->sum->unpaid_amount;


        $unpaidForCategory = function ($categoryId) {
            return Debt::where('category_id', $categoryId)
                ->get()
                ->sum->unpaid_amount;
        };
        $unpaidCat1 = $unpaidForCategory(1);
        $unpaidCat2 = $unpaidForCategory(2);
        $unpaidCat3 = $unpaidForCategory(3);
        $unpaidCat8 = $unpaidForCategory(8);

        $paidTaxForCategory = function ($categoryId) {
            return Transaction::query()
                ->where('transaction_type_id', 1)
                ->where('category_id', $categoryId)
                ->sum('tax');
        };
        $paidTaxCat1 = $paidTaxForCategory(1);
        $paidTaxCat2 = $paidTaxForCategory(2);
        $paidTaxCat3 = $paidTaxForCategory(3);
        $paidTaxCat4 = $paidTaxForCategory(4);
        $paidTaxCat5 = $paidTaxForCategory(5);
        $paidTaxCat6 = $paidTaxForCategory(6);
        $paidTaxCat7 = $paidTaxForCategory(7);
        $paidTaxCat8 = $paidTaxForCategory(8);
        $paidTaxCat9 = $paidTaxForCategory(9);

        $commissionForCategory = function ($categoryId) {
            return Transaction::query()
                ->where('transaction_type_id', 1)
                ->where('category_id', $categoryId)
                ->sum('commission');
        };
        $logisticsForCategory = function ($categoryId) {
            return Transaction::query()
                ->where('transaction_type_id', 1)
                ->where('category_id', $categoryId)
                ->sum('logistics');
        };
        // ۹ متغیر جداگانه برای کمیسیون
        $commissionCat1 = $commissionForCategory(1);
        $commissionCat2 = $commissionForCategory(2);
        $commissionCat3 = $commissionForCategory(3);
        $commissionCat4 = $commissionForCategory(4);
        $commissionCat5 = $commissionForCategory(5);
        $commissionCat6 = $commissionForCategory(6);
        $commissionCat7 = $commissionForCategory(7);
        $commissionCat8 = $commissionForCategory(8);
        $commissionCat9 = $commissionForCategory(9);

        // ۹ متغیر جداگانه برای لجستیک
        $logisticsCat1 = $logisticsForCategory(1);
        $logisticsCat2 = $logisticsForCategory(2);
        $logisticsCat3 = $logisticsForCategory(3);
        $logisticsCat4 = $logisticsForCategory(4);
        $logisticsCat5 = $logisticsForCategory(5);
        $logisticsCat6 = $logisticsForCategory(6);
        $logisticsCat7 = $logisticsForCategory(7);
        $logisticsCat8 = $logisticsForCategory(8);
        $logisticsCat9 = $logisticsForCategory(9);
        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->created_at);
            $persianDate = Jalalian::fromCarbon($date)->format('Y/m/d');


            $transaction->persian_date = $persianDate;
        }

        return view('adminPanel.transaction',[
            'sellCategories'=>$sellCategories,
            'costCategories'=>$costCategories,
            'adCategories'=>$adCategories,
            'bankAccounts'=>$bankAccounts,
            'transactions'=>$transactions,
            'sellTransTotalAmount'=>$sellTransTotalAmount,
            'snapTrans'=>$snapTrans,
            'torobTrans'=>$torobTrans,
            'basalamTrans'=>$basalamTrans,
            'siteTrans'=>$siteTrans,
            'naghdiTrans'=>$naghdiTrans,
            'digikalaTrans'=>$digikalaTrans,
            'snapshopTrans'=>$snapshopTrans,
            'pasajTrans'=>$pasajTrans,
            'instaTrans'=>$instaTrans,
            'costTransTotalAmount'=>$costTransTotalAmount,
            'costKari'=>$costKari,
            'costShakhsi'=>$costShakhsi,
            'adsTransTotalAmount'=>$adsTransTotalAmount,
            'snapAds'=>$snapAds,
            'torobAds'=>$torobAds,
            'instaAds'=>$instaAds,
            'basalamAds'=>$basalamAds,
            'googleAds'=>$googleAds,
            'pasajAds'=>$pasajAds,
            'seoAds'=>$seoAds,
            'wallet'=>$wallet['wallet'],
            'profitTotalAmount'=>$profitTotalAmount,
            'snapProfit'=>$snapProfit,
            'torobProfit'=>$torobProfit,
            'basalamProfit'=>$basalamProfit,
            'siteProfit'=>$siteProfit,
            'naghdiProfit'=>$naghdiProfit,
            'digikalaProfit'=>$digikalaProfit,
            'snapshopProfit'=>$snapshopProfit,
            'pasajProfit'=>$pasajProfit,
            'instaProfit'=>$instaProfit,
            'totalUnpaid'=>$totalUnpaid,
            'unpaidCat1'=>$unpaidCat1,
            'unpaidCat2'=>$unpaidCat2,
            'unpaidCat3'=>$unpaidCat3,
            'unpaidCat8'=>$unpaidCat8,
            'taxAmount'=>$taxAmount,
            'paidTaxCat1'=>$paidTaxCat1,
            'paidTaxCat2'=>$paidTaxCat2,
            'paidTaxCat3'=>$paidTaxCat3,
            'paidTaxCat4'=>$paidTaxCat4,
            'paidTaxCat5'=>$paidTaxCat5,
            'paidTaxCat6'=>$paidTaxCat6,
            'paidTaxCat7'=>$paidTaxCat7,
            'paidTaxCat8'=>$paidTaxCat8,
            'paidTaxCat9'=>$paidTaxCat9,
            'commissionCat1'=>$commissionCat1,
            'commissionCat2'=>$commissionCat2,
            'commissionCat3'=>$commissionCat3,
            'commissionCat4'=>$commissionCat4,
            'commissionCat5'=>$commissionCat5,
            'commissionCat6'=>$commissionCat6,
            'commissionCat7'=>$commissionCat7,
            'commissionCat8'=>$commissionCat8,
            'commissionCat9'=>$commissionCat9,
            'logisticsCat1'=>$logisticsCat1,
            'logisticsCat2'=>$logisticsCat2,
            'logisticsCat3'=>$logisticsCat3,
            'logisticsCat4'=>$logisticsCat4,
            'logisticsCat5'=>$logisticsCat5,
            'logisticsCat6'=>$logisticsCat6,
            'logisticsCat7'=>$logisticsCat7,
            'logisticsCat8'=>$logisticsCat8,
            'logisticsCat9'=>$logisticsCat9,
            'logisticsAmount'=>$logisticsAmount,
            'commissionAmount'=>$commissionAmount,



        ]);
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
                $debtAmount = ($request['sellPrice'] - ($amountCommission + $amountTax+$logistics)) / 4;
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
                $debtAmount = $request['sellPrice'] - ($amountCommission + $amountTax+$logistics) ;
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
                $debtAmount = $request['sellPrice'] - ($amountCommission + $amountTax+$logistics) ;
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
                    session('debtNID',$debtN['id']);
                }

                Transaction::query()->create([
                    'transaction_type_id' => 1,
                    'debt_id'=>session('debtNID'),
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

    public function transactionDelete($id)
    {
        $transaction = Transaction::query()->find($id);
        $cash=Bank_account::query()->where('status',1)->first();
        if($transaction['transaction_type_id'] == 1){
            $debt=Debt::query()->find($transaction['debt_id']);
            if($transaction['category_id'] == 1 || $transaction['category_id'] == 2 ){
                $debtAmount = ($transaction['sellPrice'] - ($transaction['commission'] + $transaction['tax'] + $transaction['logistics']))/4;
                $debt->update([
                   'debt1' => $debt['debt1'] - $debtAmount,
                    'debt2' => $debt['debt2'] - $debtAmount,
                    'debt3' => $debt['debt3'] - $debtAmount,
                    'debt4' => $debt['debt4'] - $debtAmount
                ]);
                if($debt['debt1'] == 0){
                    $debt->delete();
                }
            }elseif($transaction['category_id'] == 3 || $transaction['category_id'] ==8 ){
                $debtAmount = $transaction['sellPrice'] - ($transaction['commission'] + $transaction['tax'] + $transaction['logistics']);
                $debt->update([
                    'debt1' => $debt['debt1'] - $debtAmount,

                ]);
                if($debt['debt1'] == 0){
                    $debt->delete();
                }
            }else{
                $cash->update([
                   'wallet' => $cash['wallet'] - $transaction['profit']
                ]);
            }

        }else{
            $cash->update([
                'wallet' => $cash['wallet'] - $transaction['buyPrice']
            ]);
        }
      $transaction->delete();
        return redirect()->route('AdminHome')->with('deleteSucces','تراکنش با موفقیت حذف شد.');
    }

    public function debtList()
    {
        // بارگذاری رابطه category
        $debts = Debt::with('category')->get();

        // جدا کردن پرداخت شده و پرداخت نشده
        $unpaidDebts = $debts->filter(function ($debt) {
            return
                !$debt->debt1_isPaid ||
                ($debt->debt2 && !$debt->debt2_isPaid) ||
                ($debt->debt3 && !$debt->debt3_isPaid) ||
                ($debt->debt4 && !$debt->debt4_isPaid);
        });

        $paidDebts = $debts->filter(function ($debt) {
            return
                $debt->debt1_isPaid &&
                (!$debt->debt2 || $debt->debt2_isPaid) &&
                (!$debt->debt3 || $debt->debt3_isPaid) &&
                (!$debt->debt4 || $debt->debt4_isPaid);
        });

        return view('adminpanel.debt.list', compact('unpaidDebts', 'paidDebts'));
    }
    public function payInstallment(Debt $debt, Request $request)
    {
        $installment = $request->input('installment'); // 1, 2, 3, 4
        $field = "debt{$installment}";
        $isPaidField = "debt{$installment}_isPaid";
        $timeField = "debt{$installment}_time";

        // بررسی وجود قسط
        if (!$debt->$field) {
            return back()->with('error', "قسط {$installment} وجود ندارد.");
        }

        // بررسی اینکه قبلاً پرداخت شده یا نه
        if ($debt->$isPaidField) {
            return back()->with('warning', "قسط {$installment} قبلاً پرداخت شده است.");
        }

        // دریافت مبلغ قسط
        $amount = $debt->$field;

        // ثبت پرداخت
        $debt->$isPaidField = true;
        $debt->save();

        $cash=Bank_account::query()->where('status',1)->first();
        $cash->update([
           'wallet' => $cash['wallet'] + $amount
        ]);


        return back()->with('success', "قسط {$installment} با موفقیت پرداخت شد.");
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
