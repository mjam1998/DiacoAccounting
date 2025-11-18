<?php

namespace App\Http\Controllers;

use App\Models\Bank_account;
use App\Models\bankCheck;
use App\Models\Category;
use App\Models\Debt;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\Jalalian;

class UserController extends Controller
{
    public function adminHome(Request $request)
    {
        // --- بررسی طلب‌های سررسید ---
        $today = Carbon::today();
        $overdueDebts = Debt::with('category')->get()->filter(function ($debt) use ($today) {
            return
                (!$debt->debt1_isPaid && $debt->debt1_time instanceof \Carbon\Carbon && $debt->debt1_time->lte($today)) ||
                ($debt->debt2 && !$debt->debt2_isPaid && $debt->debt2_time instanceof \Carbon\Carbon && $debt->debt2_time->lte($today)) ||
                ($debt->debt3 && !$debt->debt3_isPaid && $debt->debt3_time instanceof \Carbon\Carbon && $debt->debt3_time->lte($today)) ||
                ($debt->debt4 && !$debt->debt4_isPaid && $debt->debt4_time instanceof \Carbon\Carbon && $debt->debt4_time->lte($today));
        });

        // متغیر برای نمایش هشدار (بدون Session)
        $showOverdueAlert = $overdueDebts->isNotEmpty();

        // چک‌های معوق: تاریخ سررسید گذشته و is_paid = 0
        $overdueChecks = BankCheck::query()->where('is_paid', 0)
            ->whereDate('check_date', '<=', $today) // فرض می‌کنم فیلد تاریخ سررسید check_date هست
            ->orderBy('check_date')
            ->get();

        // متغیر برای نمایش هشدار
        $showOverdueCheckAlert = $overdueChecks->isNotEmpty();


        $categories = Category::all();
        $sellCategories = $categories->where('transaction_type_id', 1);
        $costCategories = $categories->where('transaction_type_id', 2);
        $adCategories = $categories->where('transaction_type_id', 3);
        $bankAccounts = Bank_account::where('id', '>', 1)->get();

        $transactions = Transaction::with(['transaction_type', 'category'])->latest()->get();
        foreach ($transactions as $t) {
            $t->persian_date = Jalalian::fromCarbon(Carbon::parse($t->created_at))->format('Y/m/d');
        }


        $sellTransaction = $transactions->where('transaction_type_id', 1);
        $taxAmount = $sellTransaction->sum('tax');
        $logisticsAmount = $sellTransaction->sum('logistics');
        $commissionAmount = $sellTransaction->sum('commission');
        $sellTransTotalAmount = $sellTransaction->sum('sellPrice');
        $capitalAmount = $sellTransaction->sum('buyPrice');
        $profitTotalAmount = $sellTransaction->sum('profit');

        $snapTrans = $sellTransaction->where('category_id', 1)->sum('sellPrice');
        $torobTrans = $sellTransaction->where('category_id', 2)->sum('sellPrice');
        $basalamTrans = $sellTransaction->where('category_id', 3)->sum('sellPrice');
        $siteTrans = $sellTransaction->where('category_id', 4)->sum('sellPrice');
        $naghdiTrans = $sellTransaction->where('category_id', 5)->sum('sellPrice');
        $digikalaTrans = $sellTransaction->where('category_id', 6)->sum('sellPrice');
        $snapshopTrans = $sellTransaction->where('category_id', 7)->sum('sellPrice');
        $pasajTrans = $sellTransaction->where('category_id', 8)->sum('sellPrice');
        $instaTrans = $sellTransaction->where('category_id', 9)->sum('sellPrice');

        $snapProfit = $sellTransaction->where('category_id', 1)->sum('profit');
        $torobProfit = $sellTransaction->where('category_id', 2)->sum('profit');
        $basalamProfit = $sellTransaction->where('category_id', 3)->sum('profit');
        $siteProfit = $sellTransaction->where('category_id', 4)->sum('profit');
        $naghdiProfit = $sellTransaction->where('category_id', 5)->sum('profit');
        $digikalaProfit = $sellTransaction->where('category_id', 6)->sum('profit');
        $snapshopProfit = $sellTransaction->where('category_id', 7)->sum('profit');
        $pasajProfit = $sellTransaction->where('category_id', 8)->sum('profit');
        $instaProfit = $sellTransaction->where('category_id', 9)->sum('profit');

        $costTransaction = $transactions->where('transaction_type_id', 2);
        $costTransTotalAmount = $costTransaction->sum('buyPrice');
        $costKari = $costTransaction->where('category_id', 10)->sum('buyPrice');
        $costShakhsi = $costTransaction->where('category_id', 11)->sum('buyPrice');

        $adsTransaction = $transactions->where('transaction_type_id', 3);
        $adsTransTotalAmount = $adsTransaction->sum('buyPrice');
        $snapAds = $adsTransaction->where('category_id', 12)->sum('buyPrice');
        $torobAds = $adsTransaction->where('category_id', 13)->sum('buyPrice');
        $instaAds = $adsTransaction->where('category_id', 14)->sum('buyPrice');
        $basalamAds = $adsTransaction->where('category_id', 15)->sum('buyPrice');
        $googleAds = $adsTransaction->where('category_id', 16)->sum('buyPrice');
        $pasajAds = $adsTransaction->where('category_id', 17)->sum('buyPrice');
        $seoAds = $adsTransaction->where('category_id', 18)->sum('buyPrice');

        $wallet = Bank_account::where('status', 1)->first()?->wallet ?? 0;

        // --- بدهی‌های پرداخت‌نشده ---
        $totalUnpaid = Debt::all()->sum->unpaid_amount;

        $unpaidForCategory = function ($categoryId) {
            return Debt::where('category_id', $categoryId)->get()->sum->unpaid_amount;
        };

        $unpaidCat1 = $unpaidForCategory(1);
        $unpaidCat2 = $unpaidForCategory(2);
        $unpaidCat3 = $unpaidForCategory(3);
        $unpaidCat8 = $unpaidForCategory(8);

        $paidTaxCat1 = $this->sumField(1, 'tax'); $paidTaxCat2 = $this->sumField(2, 'tax'); $paidTaxCat3 = $this->sumField(3, 'tax');
        $paidTaxCat4 = $this->sumField(4, 'tax'); $paidTaxCat5 = $this->sumField(5, 'tax'); $paidTaxCat6 = $this->sumField(6, 'tax');
        $paidTaxCat7 = $this->sumField(7, 'tax'); $paidTaxCat8 = $this->sumField(8, 'tax'); $paidTaxCat9 = $this->sumField(9, 'tax');

        $commissionCat1 = $this->sumField(1, 'commission'); $commissionCat2 = $this->sumField(2, 'commission');
        $commissionCat3 = $this->sumField(3, 'commission'); $commissionCat4 = $this->sumField(4, 'commission');
        $commissionCat5 = $this->sumField(5, 'commission'); $commissionCat6 = $this->sumField(6, 'commission');
        $commissionCat7 = $this->sumField(7, 'commission'); $commissionCat8 = $this->sumField(8, 'commission');
        $commissionCat9 = $this->sumField(9, 'commission');

        $logisticsCat1 = $this->sumField(1, 'logistics'); $logisticsCat2 = $this->sumField(2, 'logistics');
        $logisticsCat3 = $this->sumField(3, 'logistics'); $logisticsCat4 = $this->sumField(4, 'logistics');
        $logisticsCat5 = $this->sumField(5, 'logistics'); $logisticsCat6 = $this->sumField(6, 'logistics');
        $logisticsCat7 = $this->sumField(7, 'logistics'); $logisticsCat8 = $this->sumField(8, 'logistics');
        $logisticsCat9 = $this->sumField(9, 'logistics');

        $capCat1 = $this->sumField(1, 'buyPrice'); $capCat2 = $this->sumField(2, 'buyPrice');
        $capCat3 = $this->sumField(3, 'buyPrice'); $capCat4 = $this->sumField(4, 'buyPrice');
        $capCat5 = $this->sumField(5, 'buyPrice'); $capCat6 = $this->sumField(6, 'buyPrice');
        $capCat7 = $this->sumField(7, 'buyPrice'); $capCat8 = $this->sumField(8, 'buyPrice');
        $capCat9 = $this->sumField(9, 'buyPrice');

        // --- نمودارها ---

        // در UserController@adminHome

        $period = 'monthly'; // فقط ۱۲ ماه شمسی
        $salesPeriod = 'monthly';
        $expensePeriod = 'monthly';
        $adPeriod = 'monthly';

        $salesData = $this->getSalesTrend($salesPeriod);
        $salesProfitData = $this->getSalesProfitComparison($period);
        $platformPerformance = $this->getPlatformPerformance($period);

        $expenseBreakdown = $this->getExpenseBreakdown($expensePeriod);
        $adSpendByCategory = $this->getAdSpendByCategory($adPeriod);
        $adSpendTrend = $this->getAdSpendTrend($adPeriod);
        $adEfficiency = $this->getAdEfficiency($adPeriod);
        $adToRevenueRatio = $this->getAdToRevenueRatio($adPeriod);
        $overallROI = $this->calculateOverallROI($period);
        $beforeAfterAd = $this->getBeforeAfterAdImpact('ترب', $adPeriod);

        $periodLabel = '۱۲ ماه اخیر';

        return response()->view('adminPanel.transaction', compact(
            'sellCategories', 'costCategories', 'adCategories', 'bankAccounts', 'transactions',
            'sellTransTotalAmount', 'snapTrans', 'torobTrans', 'basalamTrans', 'siteTrans',
            'naghdiTrans', 'digikalaTrans', 'snapshopTrans', 'pasajTrans', 'instaTrans',
            'costTransTotalAmount', 'costKari', 'costShakhsi',
            'adsTransTotalAmount', 'snapAds', 'torobAds', 'instaAds', 'basalamAds',
            'googleAds', 'pasajAds', 'seoAds',
            'wallet', 'profitTotalAmount',
            'snapProfit', 'torobProfit', 'basalamProfit', 'siteProfit', 'naghdiProfit',
            'digikalaProfit', 'snapshopProfit', 'pasajProfit', 'instaProfit',
            'totalUnpaid', 'unpaidCat1', 'unpaidCat2', 'unpaidCat3', 'unpaidCat8',
            'taxAmount', 'paidTaxCat1', 'paidTaxCat2', 'paidTaxCat3', 'paidTaxCat4',
            'paidTaxCat5', 'paidTaxCat6', 'paidTaxCat7', 'paidTaxCat8', 'paidTaxCat9',
            'commissionCat1', 'commissionCat2', 'commissionCat3', 'commissionCat4',
            'commissionCat5', 'commissionCat6', 'commissionCat7', 'commissionCat8', 'commissionCat9',
            'logisticsCat1', 'logisticsCat2', 'logisticsCat3', 'logisticsCat4',
            'logisticsCat5', 'logisticsCat6', 'logisticsCat7', 'logisticsCat8', 'logisticsCat9',
            'logisticsAmount', 'commissionAmount',

            'salesData', 'salesProfitData', 'platformPerformance',
            'expenseBreakdown', 'adSpendByCategory', 'adSpendTrend', 'adEfficiency',
            'adToRevenueRatio', 'overallROI', 'beforeAfterAd',
            'period', 'periodLabel',
            'salesPeriod',  'expensePeriod', 'adPeriod','showOverdueAlert',
        'overdueDebts','capitalAmount','capCat1', 'capCat2', 'capCat3', 'capCat4', 'capCat5','capCat6','capCat7',
            'capCat8','capCat9','showOverdueCheckAlert','overdueChecks'
        ))->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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

                 $cashAmount2=Bank_account::query()->find(1);
                 $cashAmount2->update([
                    'wallet'=>$cashAmount2['wallet'] - $logistics
                 ]);
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

                    Transaction::query()->create([
                        'transaction_type_id' => 1,
                        'debt_id'=>$debt['id'],
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
                $cashAmount2=Bank_account::query()->find(1);
                $cashAmount2->update([
                    'wallet'=>$cashAmount2['wallet'] - $logistics
                ]);

                $currentJalali = $inputJalaliDate;



                $debt = Debt::query()->where('category_id', $request['category_id'])
                    ->where('created_at',$gregorianDate )
                    ->first();

                if($debt != null){

                    $debt->update([
                        'debt1' => $debt['debt1'] + $debtAmount

                    ]);
                    Transaction::query()->create([
                        'transaction_type_id' => 1,
                        'debt_id'=>$debt['id'],
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




            }
            elseif ($request['category_id'] == 8){ //پاساژ

                $category = Category::query()->where('id', $request['category_id'])->first();
                $commission = $category['commission'];
                $tax = $category['tax'];
                $logistics = $category['logistics'];
                $amountCommission = $request['sellPrice'] * ($commission/100);
                if ($amountCommission>150000){
                    $amountCommission=150000;
                }
                $amountTax = $amountCommission * ($tax/100);
                $debtAmount = $request['sellPrice'] - ($amountCommission + $amountTax) ;
                $profit = $request['sellPrice'] - ($request['buyPrice'] + $amountCommission + $amountTax + $logistics);

                $cashAmount2=Bank_account::query()->find(1);
                $cashAmount2->update([
                    'wallet'=>$cashAmount2['wallet'] - $logistics
                ]);
                $currentJalali = $inputJalaliDate;



                $debt = Debt::query()->where('category_id', $request['category_id'])
                    ->where('created_at',$gregorianDate )
                    ->first();

                if($debt != null){

                    $debt->update([
                        'debt1' => $debt['debt1'] + $debtAmount

                    ]);
                    Transaction::query()->create([
                        'transaction_type_id' => 1,
                        'debt_id'=>$debt['id'],
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




            }
            elseif ($request['category_id'] == 4){
                $category = Category::query()->where('id', $request['category_id'])->first();
                $commission = $category['commission'];
                $tax = $category['tax'];
                $logistics = $category['logistics'];
                $amountCommission = $request['sellPrice'] * ($commission/100);
                if ($amountCommission>12000){
                    $amountCommission=12000;
                }
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

        // طلب‌های پرداخت نشده (حداقل یک قسط پرداخت نشده)
        $unpaidDebts = $debts->filter(function ($debt) {
            return
                !$debt->debt1_isPaid ||
                ($debt->debt2 && !$debt->debt2_isPaid) ||
                ($debt->debt3 && !$debt->debt3_isPaid) ||
                ($debt->debt4 && !$debt->debt4_isPaid);
        });

        // طلب‌های پرداخت شده (همه قسط‌ها پرداخت شده)
        $paidDebts = $debts->filter(function ($debt) {
            return
                $debt->debt1_isPaid &&
                (!$debt->debt2 || $debt->debt2_isPaid) &&
                (!$debt->debt3 || $debt->debt3_isPaid) &&
                (!$debt->debt4 || $debt->debt4_isPaid);
        });

        // طلب‌های سررسید (حداقل یک قسط معوق با سررسید گذشته یا امروز)
        $overdueDebts = $debts->filter(function ($debt) {
            $today = Carbon::today();
            return
                // قسط ۱: معوق و سررسید گذشته یا امروز
                (!$debt->debt1_isPaid && $debt->debt1_time instanceof \Carbon\Carbon && $debt->debt1_time->lte($today)) ||
                // قسط ۲: معوق و سررسید گذشته یا امروز
                ($debt->debt2 && !$debt->debt2_isPaid && $debt->debt2_time instanceof \Carbon\Carbon && $debt->debt2_time->lte($today)) ||
                // قسط ۳: معوق و سررسید گذشته یا امروز
                ($debt->debt3 && !$debt->debt3_isPaid && $debt->debt3_time instanceof \Carbon\Carbon && $debt->debt3_time->lte($today)) ||
                // قسط ۴: معوق و سررسید گذشته یا امروز
                ($debt->debt4 && !$debt->debt4_isPaid && $debt->debt4_time instanceof \Carbon\Carbon && $debt->debt4_time->lte($today));
        });

        return view('adminpanel.debt.list', compact('unpaidDebts', 'paidDebts', 'overdueDebts'));
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

    public function bankChecks()
    {

        $checks=Bankcheck::all();
        if($checks->isNotEmpty()){
            foreach ($checks as $check) {
                $check->persianDate=Jalalian::fromCarbon(Carbon::parse($check->check_date))->format('Y/m/d');
                $check->persianCreate=Jalalian::fromCarbon(Carbon::parse($check->created_at))->format('Y/m/d');
            }
        }

        $bankAccounts = Bank_account::query()->whereNot('id', 1)->get();
        return view('adminpanel.bankCheck', compact('checks', 'bankAccounts'));
    }

    public function bankCheckSubmit(Request $request)
    {
        $normalizedDate = $this->normalizePersianDate($request['check_date']);

        // تبدیل تاریخ ورودی به Jalalian
        $inputJalaliDate = Jalalian::fromFormat('Y/m/d', $normalizedDate);

        // تاریخ میلادی برای ذخیره در تراکنش
        $gregorianDate = $inputJalaliDate->toCarbon()->format('Y-m-d');

        Bankcheck::query()->create([
            'bankAccount_id'=>$request['bankAccount_id'],
            'check_amount'=>$request['check_amount'],
            'check_date'=>$gregorianDate,
            'description'=>$request['description'],
        ]);

        return back()->with('addcheck','چک با موفقیت ثبت شد.');
    }

    public function bankCheckPaid(Request $request)
    {

        $check=bankCheck::query()->where('id',$request['check_id'])->first();
        $check->update([
            'is_paid'=>1
        ]);
        if ($check['bankAccount_id']==1){
            $wallet=Bank_account::query()->where('id',1)->first();
            $wallet->update([
                'wallet'=>$wallet['wallet'] - $check['check_amount']
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'چک با موفقیت پرداخت شد.'
        ]);
    }

    public function bankCheckDelete($id)
    {
        $bankcheck = Bankcheck::query()->find($id);
        $bankcheck->delete();
        return back()->with('deleteCheck','چک با موفقیت حذف شد.');
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

    //chart




    public function updateChart(Request $request)
    {
        $type = $request->type;
        $period = $request->period ?? 'monthly';

        $data = match ($type) {
            'sales' => $this->getSalesTrend($period),
            'salesProfit' => $this->getSalesProfitComparison($period),
            'platform' => $this->getPlatformPerformance($period)['chart'],

            'expensePie' => $this->getExpenseBreakdown($period)['pie'],
            'expenseTrend' => $this->getExpenseBreakdown($period)['trend'],
            'adSpendPie' => $this->getAdSpendByCategory($period),
            'adSpendTrend' => $this->getAdSpendTrend($period),
            'adEfficiency' => $this->getAdEfficiency($period),
            'adToRevenue' => $this->getAdToRevenueRatio($period),
            'beforeAfter' => $this->getBeforeAfterAdImpact('ترب', $period),
            default => ['labels' => [], 'datasets' => []],
        };

        return response()->json($data);
    }

    // --- توابع کمکی ---
    private function sumField($catId, $field) {
        return Transaction::where('transaction_type_id', 1)->where('category_id', $catId)->sum($field);
    }

    /**
     * تبدیل تاریخ میلادی به شمسی با فرمت درست
     */
    private function toJalali($date, $format = 'Y/m')
    {
        if (!$date) return 'نامشخص';

        // اگر فقط سال-ماه داریم (مثل 2025-06)، روز را 01 بگذار
        if (preg_match('/^\d{4}-\d{2}$/', $date)) {
            $date .= '-01';
        }

        try {
            return Jalalian::fromCarbon(Carbon::parse($date))->format($format);
        } catch (\Exception $e) {
            return $date;
        }
    }

    /**
     * تعیین بازه زمانی برای نمودارها بر اساس داده‌های واقعی
     */
    private function getDateRange($period)
    {
        // دریافت اولین و آخرین تاریخ تراکنش
        $firstTransaction = Transaction::min('created_at');
        $lastTransaction = Transaction::max('created_at');

        $now = Carbon::now();

        if (!$firstTransaction || !$lastTransaction) {
            return [$now->copy()->subMonths(12), $now];
        }

        $first = Carbon::parse($firstTransaction);
        $last = Carbon::parse($lastTransaction);

        // تبدیل به Jalalian برای محاسبه شمسی
        $firstJalali = Jalalian::fromCarbon($first);
        $lastJalali = Jalalian::fromCarbon($last);
        $nowJalali = Jalalian::fromCarbon($now);

        // محاسبه شروع بازه بر اساس دوره — با استفاده از Carbon
        $startCarbon = match ($period) {
            'daily'   => $now->copy()->subDays(30),
            'weekly'  => $now->copy()->subWeeks(12),
            'monthly' => $now->copy()->subMonths(12),           // Carbon
            '3months' => $now->copy()->subMonths(9),
            '6months' => $now->copy()->subMonths(18),
            'yearly'  => $now->copy()->subYears(3),
            default   => $now->copy()->subMonths(12),
        };

        // تبدیل شروع به شمسی
        $startJalali = Jalalian::fromCarbon($startCarbon);

        // اگر اولین تراکنش بعد از شروع محاسبه‌شده باشد، از آن شروع کن
        if ($firstJalali->greaterThan($startJalali)) {
            $startCarbon = $first;
        }

        // پایان: آخرین تراکنش یا امروز (هر کدام دیرتر)
        $endCarbon = $last->greaterThan($now) ? $last : $now;

        return [
            $startCarbon->startOfDay(),
            $endCarbon->endOfDay()
        ];
    }
    private function groupByJalaliPeriod($transactions, $period)
    {
        return $transactions->groupBy(function ($t) use ($period) {
            $jalali = Jalalian::fromCarbon($t->created_at);

            return match ($period) {
                'daily'   => $jalali->format('Y/m/d'),
                'weekly'  => $jalali->format('Y/m/d'), // شروع هفته (بعداً تنظیم می‌کنیم)
                'monthly' => $jalali->format('Y/m'),
                '3months' => $jalali->format('Y') . '/Q' . ceil($jalali->format('m') / 3),
                '6months' => $jalali->format('Y') . '/H' . ceil($jalali->format('m') / 6),
                'yearly'  => $jalali->format('Y'),
                default   => $jalali->format('Y/m'),
            };
        });
    }
    private function fillMissingPeriods($data, $startJalali, $endJalali, $period, $default = 0) {
        $filled = [];
        $current = $startJalali;
        while ($current->toCarbon()->lessThanOrEqualTo($endJalali->toCarbon())) { // استفاده از Carbon برای مقایسه
            $key = match ($period) {
                'daily' => $current->format('Y/m/d'),
                'weekly' => $current->startOfWeek()->format('Y/m/d'),
                'monthly' => $current->format('Y/m'),
                '3months' => $current->format('Y') . '/Q' . ceil($current->format('m') / 3),
                '6months' => $current->format('Y') . '/H' . ceil($current->format('m') / 6),
                'yearly' => $current->format('Y'),
                default => $current->format('Y/m'),
            };
            $filled[$key] = $data->get($key, $default);
            $current = $this->addPeriod($current, $period);
        }
        return collect($filled);
    }

    private function addPeriod($jalali, $period) {
        $carbon = $jalali->toCarbon(); // تبدیل به Carbon
        $newCarbon = match ($period) {
            'daily' => $carbon->addDay(),
            'weekly' => $carbon->addWeek(),
            'monthly' => $carbon->addMonth(),
            '3months' => $carbon->addMonths(3),
            '6months' => $carbon->addMonths(6),
            'yearly' => $carbon->addYear(),
            default => $carbon->addMonth(),
        };
        return Jalalian::fromCarbon($newCarbon); // بازگشت به Jalalian
    }
    private function getPeriodLabel($period) {
        return match ($period) {
            'daily' => '۳۰ روز اخیر',
            'weekly' => '۱۲ هفته اخیر',
            'monthly' => '۱۲ ماه اخیر',
            '3months' => '۹ ماه اخیر',
            '6months' => '۱۸ ماه اخیر',
            'yearly' => '۳ سال اخیر',
            default => 'نامشخص',
        };
    }

    // --- نمودارها ---

    /**
     * روند فروش — اصلاح شده برای نمایش داده‌های آینده
     */
    private function getSalesTrend($period)
    {
        [$startCarbon, $endCarbon] = $this->getDateRange($period);
        $startJalali = Jalalian::fromCarbon($startCarbon);
        $endJalali = Jalalian::fromCarbon($endCarbon);

        $transactions = Transaction::whereBetween('created_at', [$startCarbon, $endCarbon])
            ->where('sellPrice', '>', 0)
            ->get();

        $grouped = $this->groupByJalaliPeriod($transactions, $period)
            ->map->sum('sellPrice');

        $filled = $this->fillMissingPeriods($grouped, $startJalali, $endJalali, $period);

        $labels = $filled->keys()->map(function ($key) use ($period) {
            if ($period === 'weekly') {
                $date = Jalalian::fromFormat('Y/m/d', $key);
                return $date->format('Y/m/d') . ' - ' . $date->addDays(6)->format('Y/m/d');
            }
            return str_replace(['/', 'Q', 'H'], ['/', ' فصل ', ' نیم‌سال '], $key);
        });

        return [
            'labels' => $labels->values()->toArray(),
            'datasets' => [[
                'label' => 'فروش',
                'data' => $filled->values()->toArray(),
                'borderColor' => '#10b981',
                'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                'tension' => 0.3,
                'fill' => true
            ]]
        ];
    }
    /**
     * تعیین فیلد GROUP BY بر اساس دوره
     */
    private function getDateTruncField($period)
    {
        return match ($period) {
            'daily'   => 'DATE(created_at)',
            'weekly'  => 'DATE(DATE_SUB(created_at, INTERVAL WEEKDAY(created_at) DAY))',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
            '3months' => 'CONCAT(YEAR(created_at), "-Q", CEIL(MONTH(created_at)/3))',
            '6months' => 'CONCAT(YEAR(created_at), "-H", CEIL(MONTH(created_at)/6))',
            'yearly'  => 'YEAR(created_at)',
            default   => 'DATE_FORMAT(created_at, "%Y-%m")',
        };
    }

    /**
     * فرمت برچسب محور X
     */
    private function getLabelFormat($period)
    {
        return match ($period) {
            'daily'   => 'Y/m/d',
            'weekly'  => 'Y/m/d',
            'monthly' => 'Y/m',        // فقط سال/ماه
            '3months' => 'Y/m',
            '6months' => 'Y/m',
            'yearly'  => 'Y',
            default   => 'Y/m',
        };
    }

    private function getSalesProfitComparison($period)
    {
        [$startCarbon, $endCarbon] = $this->getDateRange($period);
        $startJalali = Jalalian::fromCarbon($startCarbon);
        $endJalali = Jalalian::fromCarbon($endCarbon);

        $transactions = Transaction::whereBetween('created_at', [$startCarbon, $endCarbon])
            ->where('transaction_type_id', 1)
            ->get();

        $grouped = $this->groupByJalaliPeriod($transactions, $period);

        $sales = $grouped->map->sum('sellPrice');
        $profit = $grouped->map->sum('profit');

        $filledSales = $this->fillMissingPeriods($sales, $startJalali, $endJalali, $period);
        $filledProfit = $this->fillMissingPeriods($profit, $startJalali, $endJalali, $period);

        $labels = $filledSales->keys()->map(fn($k) => str_replace(['/', 'Q', 'H'], ['/', ' فصل ', ' نیم‌سال '], $k));

        return [
            'labels' => $labels->values()->toArray(),
            'datasets' => [
                ['label' => 'فروش', 'data' => $filledSales->values()->toArray(), 'backgroundColor' => '#10b981'],
                ['label' => 'سود', 'data' => $filledProfit->values()->toArray(), 'backgroundColor' => '#8b5cf6']
            ]
        ];
    }

    private function getPlatformPerformance($period) {
        $platforms = [
            ['id' => 1, 'name' => 'اسنپ پی'],
            ['id' => 2, 'name' => 'ترب پی'],
            ['id' => 3, 'name' => 'باسلام'],
            ['id' => 4, 'name' => 'سایت'],
            ['id' => 5, 'name' => 'نقدی'],
            ['id' => 6, 'name' => 'دیجی کالا'],
            ['id' => 7, 'name' => 'اسنپ شاپ'],
            ['id' => 8, 'name' => 'پاساژ'],
            ['id' => 9, 'name' => 'اینستاگرام'],
        ];

        $totalSales = Transaction::query()->where('transaction_type_id', 1)->sum('sellPrice');
        $totalProfit = Transaction::query()->where('transaction_type_id', 1)->sum('profit');

        $table = collect($platforms)->map(function ($p) use ($totalSales, $totalProfit) {
            $sales = Transaction::query()->where('category_id', $p['id'])->sum('sellPrice');
            $profit = Transaction::query()->where('category_id', $p['id'])->sum('profit');
            return [
                'name' => $p['name'],
                'sales' => $sales,
                'sales_percent' => $totalSales > 0 ? round($sales / $totalSales * 100, 1) : 0,
                'profit' => $profit,
                'profit_percent' => $totalProfit > 0 ? round($profit / $totalProfit * 100, 1) : 0,
            ];
        })->toArray();

        $chart = [
            'labels' => array_column($table, 'name'),
            'datasets' => [
                ['label' => 'فروش', 'data' => array_column($table, 'sales'), 'backgroundColor' => '#10b981'],
                ['label' => 'سود', 'data' => array_column($table, 'profit'), 'backgroundColor' => '#8b5cf6']
            ]
        ];

        return ['chart' => $chart, 'table' => $table];
    }



    private function getExpenseBreakdown($period)
    {
        [$startCarbon, $endCarbon] = $this->getDateRange($period);
        $startJalali = Jalalian::fromCarbon($startCarbon);
        $endJalali = Jalalian::fromCarbon($endCarbon);

        $transactions = Transaction::where('transaction_type_id', 2)
            ->whereIn('category_id', [10, 11])
            ->whereBetween('created_at', [$startCarbon, $endCarbon])
            ->get();

        $grouped = $this->groupByJalaliPeriod($transactions, $period);

        $kari = $grouped->map(fn($items) => $items->where('category_id', 10)->sum('buyPrice'));
        $shakhsi = $grouped->map(fn($items) => $items->where('category_id', 11)->sum('buyPrice'));

        $filledKari = $this->fillMissingPeriods($kari, $startJalali, $endJalali, $period);
        $filledShakhsi = $this->fillMissingPeriods($shakhsi, $startJalali, $endJalali, $period);

        $labels = $filledKari->keys()->map(fn($k) => str_replace(['/', 'Q', 'H'], ['/', ' فصل ', ' نیم‌سال '], $k));

        $trend = [
            'labels' => $labels->values()->toArray(),
            'datasets' => [
                [
                    'label' => 'هزینه کاری',
                    'data' => $filledKari->values()->toArray(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'tension' => 0.3,
                    'fill' => true,
                ],
                [
                    'label' => 'هزینه شخصی',
                    'data' => $filledShakhsi->values()->toArray(),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.3,
                    'fill' => true,
                ],
            ]
        ];

        // Pie: کل هزینه‌ها
        $pieKari = $transactions->where('category_id', 10)->sum('buyPrice');
        $pieShakhsi = $transactions->where('category_id', 11)->sum('buyPrice');

        $pie = [
            'labels' => ['هزینه کاری', 'هزینه شخصی'],
            'datasets' => [[
                'data' => [$pieKari, $pieShakhsi],
                'backgroundColor' => ['#f59e0b', '#ef4444']
            ]]
        ];

        return ['trend' => $trend, 'pie' => $pie];
    }

    private function getAdSpendByCategory($period) {
        [$start, $end] = $this->getDateRange($period);
        $data = Transaction::query()->where('transaction_type_id', 3) // اصلاح به تبلیغات
        ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('buyPrice')
            ->where('buyPrice', '>', 0)
            ->selectRaw('category_id, SUM(buyPrice) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();
        return [
            'labels' => $data->pluck('category.name')->toArray(),
            'datasets' => [[
                'data' => $data->pluck('total')->toArray(),
                'backgroundColor' => ['#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#06b6d4', '#f97316']
            ]]
        ];
    }

    private function getAdSpendTrend($period)
    {
        [$startCarbon, $endCarbon] = $this->getDateRange($period);
        $startJalali = Jalalian::fromCarbon($startCarbon);
        $endJalali = Jalalian::fromCarbon($endCarbon);

        $transactions = Transaction::where('transaction_type_id', 3)
            ->whereBetween('created_at', [$startCarbon, $endCarbon])
            ->get();

        $grouped = $this->groupByJalaliPeriod($transactions, $period)->map->sum('buyPrice');
        $filled = $this->fillMissingPeriods($grouped, $startJalali, $endJalali, $period);

        $labels = $filled->keys()->map(fn($k) => str_replace(['/', 'Q', 'H'], ['/', ' فصل ', ' نیم‌سال '], $k));

        return [
            'labels' => $labels->values()->toArray(),
            'datasets' => [[
                'label' => 'هزینه تبلیغات',
                'data' => $filled->values()->toArray(),
                'borderColor' => '#ec4899',
                'backgroundColor' => 'rgba(236, 72, 153, 0.1)',
                'fill' => true
            ]]
        ];
    }

    private function getAdEfficiency($period) {
        [$startCarbon, $endCarbon] = $this->getDateRange($period);
        $startJalali = Jalalian::fromCarbon($startCarbon);
        $endJalali = Jalalian::fromCarbon($endCarbon);

        $platforms = [
            1 => 12, // اسنپ پی → تبلیغات اسنپ
            2 => 13, // ترب → تبلیغات ترب
            3 => 15, // باسلام → تبلیغات باسلام
            4 => 18, //  سایت → سیو سایت
            8 => 17, // پاساژ → تبلیغات پاساژ
            9 => 14, // اینستاگرام → تبلیغات اینستاگرام
        ];

        // پالت رنگی برای هر پلتفرم
        $platformColors = [
            1 => ['background' => '#10b981', 'border' => '#059669'], // اسنپ پی
            2 => ['background' => '#ec4899', 'border' => '#db2777'], // ترب
            3 => ['background' => '#f59e0b', 'border' => '#d97706'], // باسلام
            4 => ['background' => '#3b82f6', 'border' => '#2563eb'], // سایت
            8 => ['background' => '#8b5cf6', 'border' => '#7c3aed'], // پاساژ
            9 => ['background' => '#f97316', 'border' => '#ea580c'], // اینستاگرام
        ];

        // آرایه‌ای برای ذخیره داده‌های هر پلتفرم
        $platformData = [];
        foreach ($platforms as $sellCat => $adCat) {
            $platformData[$sellCat] = [
                'label' => Category::find($adCat)?->name ?? "تبلیغ $adCat",
                'data' => [],
                'backgroundColor' => $platformColors[$sellCat]['background'],
                'borderColor' => $platformColors[$sellCat]['border'],
                'pointRadius' => 8,
            ];
        }

        // پر کردن داده‌ها
        $current = $startJalali;
        while ($current->toCarbon()->lessThanOrEqualTo($endJalali->toCarbon()->subMonth())) {
            $monthStart = $current->getFirstDayOfMonth()->toCarbon();
            $monthEnd = $current->getFirstDayOfMonth()->toCarbon()->addMonth()->subDay();
            $nextMonthJalali = Jalalian::fromCarbon($current->toCarbon()->addMonth());
            $nextMonthStart = $nextMonthJalali->getFirstDayOfMonth()->toCarbon();
            $nextMonthEnd = $nextMonthJalali->getFirstDayOfMonth()->toCarbon()->addMonth()->subDay();

            foreach ($platforms as $sellCat => $adCat) {
                $adCost = Transaction::query()
                    ->where('transaction_type_id', 3)
                    ->where('category_id', $adCat)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->whereNotNull('buyPrice')
                    ->where('buyPrice', '>', 0)
                    ->sum('buyPrice');

                $sales = Transaction::query()
                    ->where('transaction_type_id', 1)
                    ->where('category_id', $sellCat)
                    ->whereBetween('created_at', [$nextMonthStart, $nextMonthEnd])
                    ->whereNotNull('sellPrice')
                    ->where('sellPrice', '>', 0)
                    ->sum('sellPrice');

                if ($adCost > 0 && $sales > 0) {
                    $platformData[$sellCat]['data'][] = [
                        'x' => $adCost,
                        'y' => $sales,
                        'platform' => Category::find($adCat)?->name ?? "تبلیغ $adCat",
                        'adMonth' => $current->format('Y/m'), // ماه شمسی تبلیغات
                        'salesMonth' => $nextMonthJalali->format('Y/m'), // ماه شمسی فروش
                    ];
                }
            }
            $current = Jalalian::fromCarbon($current->toCarbon()->addMonth());
        }

        // تبدیل به فرمت datasets
        $datasets = array_values(array_filter($platformData, function ($dataset) {
            return !empty($dataset['data']); // فقط دیتاست‌هایی که داده دارن
        }));



        return [
            'datasets' => $datasets
        ];
    }

    private function getAdToRevenueRatio($period) {
        [$startCarbon, $endCarbon] = $this->getDateRange($period);
        $startJalali = Jalalian::fromCarbon($startCarbon);
        $endJalali = Jalalian::fromCarbon($endCarbon);

        // تعریف پلتفرم‌ها (فروش => تبلیغات)
        $platforms = [
            1 => 12, // اسنپ پی → تبلیغات اسنپ
            2 => 13, // ترب → تبلیغات ترب
            3 => 15, // باسلام → تبلیغات باسلام
            4 => 18, // سایت → سئو سایت
            8 => 17, // پاساژ → تبلیغات پاساژ
            9 => 14, // اینستاگرام → تبلیغات اینستاگرام
        ];

        // پالت رنگی برای هر پلتفرم
        $platformColors = [
            1 => '#10b981', // اسنپ پی
            2 => '#ec4899', // ترب
            3 => '#f59e0b', // باسلام
            4 => '#3b82f6', // سایت
            8 => '#8b5cf6', // پاساژ
            9 => '#f97316', // اینستاگرام
        ];

        $labels = [];
        $ratios = [];
        $colors = [];

        // محاسبه درصد برای هر پلتفرم
        foreach ($platforms as $sellCat => $adCat) {
            // هزینه تبلیغات
            $adCost = Transaction::query()
                ->where('transaction_type_id', 3)
                ->where('category_id', $adCat)
                ->whereBetween('created_at', [$startCarbon, $endCarbon])
                ->whereNotNull('buyPrice')
                ->where('buyPrice', '>', 0)
                ->sum('buyPrice');

            // فروش
            $sales = Transaction::query()
                ->where('transaction_type_id', 1)
                ->where('category_id', $sellCat)
                ->whereBetween('created_at', [$startCarbon, $endCarbon])
                ->whereNotNull('sellPrice')
                ->where('sellPrice', '>', 0)
                ->sum('sellPrice');

            // درصد (هزینه تبلیغات نسبت به فروش)
            $ratio = $sales > 0 ? round(($adCost / $sales) * 100, 1) : 0;

            $labels[] = Category::find($adCat)?->name ?? "تبلیغ $adCat";
            $ratios[] = $ratio;
            $colors[] = $platformColors[$sellCat];
        }

        // محاسبه درصد کلی (همه پلتفرم‌ها)
        $totalAdCost = Transaction::query()
            ->where('transaction_type_id', 3)
            ->whereBetween('created_at', [$startCarbon, $endCarbon])
            ->whereNotNull('buyPrice')
            ->where('buyPrice', '>', 0)
            ->sum('buyPrice');

        $totalSales = Transaction::query()
            ->where('transaction_type_id', 1)
            ->whereBetween('created_at', [$startCarbon, $endCarbon])
            ->whereNotNull('sellPrice')
            ->where('sellPrice', '>', 0)
            ->sum('sellPrice');

        $totalRatio = $totalSales > 0 ? round(($totalAdCost / $totalSales) * 100, 1) : 0;

        $labels[] = 'کل';
        $ratios[] = $totalRatio;
        $colors[] = '#ef4444'; // رنگ قرمز برای درصد کلی

        // لاگ برای دیباگ
        Log::info('AdToRevenueRatio Debug:', [
            'period' => $period,
            'start' => $startCarbon->toDateTimeString(),
            'end' => $endCarbon->toDateTimeString(),
            'platforms' => array_map(function ($sellCat, $adCat) use ($ratios, $labels) {
                return [
                    'sellCat' => $sellCat,
                    'adCat' => $adCat,
                    'label' => Category::find($adCat)?->name ?? "تبلیغ $adCat",
                    'ratio' => $ratios[array_search(Category::find($adCat)?->name ?? "تبلیغ $adCat", $labels)],
                ];
            }, array_keys($platforms), $platforms),
            'totalRatio' => $totalRatio,
        ]);

        // نمودار میله‌ای
        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'درصد هزینه تبلیغات به فروش (%)',
                'data' => $ratios,
                'backgroundColor' => $colors,
                'borderColor' => $colors,
                'borderWidth' => 1,
            ]]
        ];
    }

    private function calculateOverallROI($period) {
        $ad = Transaction::query()->where('transaction_type_id', 3)->sum('buyPrice');
        $profit = Transaction::query()->where('transaction_type_id', 1)->sum('profit');
        return $ad > 0 ? round(($profit / $ad) * 100, 1) : 0;
    }

    private function getBeforeAfterAdImpact($platform, $period) {
        // مثال ساده
        return [
            'labels' => ['قبل از تبلیغ', 'بعد از تبلیغ'],
            'datasets' => [[
                'label' => 'فروش',
                'data' => [5000000, 8000000],
                'backgroundColor' => ['#94a3b8', '#10b981']
            ]],
            'growth' => 60
        ];
    }
}
