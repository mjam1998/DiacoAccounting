@extends('AdminPanel.layout.master')

@section('title')
    تراکنش ها
@endsection

    @section('content')
        <style>
            .chart-container {
                position: relative;
                height: 400px; /* برای نمودارهای خطی، میله‌ای و پراکندگی */
                width: 100%;
            }
            .pie-container {
                position: relative;
                height: 350px; /* افزایش ارتفاع برای نمودارهای دایره‌ای */
                width: 100%;
                max-width: 450px; /* محدود کردن عرض برای تناسب بهتر */
                margin: 0 auto; /* وسط‌چین کردن */
            }
            .small-pie {
                position: relative;
                height: 300px; /* افزایش ارتفاع برای نمودار دایره‌ای کوچک */
                width: 100%;
                max-width: 350px; /* محدود کردن عرض */
                margin: 0 auto; /* وسط‌چین کردن */
            }
            .table-container table.dataTable thead th {
                background-color: #000 !important;
                color: #fff !important;
                text-align: center !important;
                vertical-align: middle !important;
                font-weight: 700;
                border: none !important;
                padding: 1rem !important;
            }
            .table-container table.dataTable tbody td {
                text-align: center !important;
                vertical-align: middle !important;
                padding: 1rem !important;
            }
            .datatable th,
            .datatable td {
                text-align: center !important;
            }
        </style>

        <h4 class="card-title">تراکنش ها</h4>
        <!-- هشدار طلب‌های سررسید -->
        @if($showOverdueAlert && $overdueDebts->isNotEmpty())
            <div class="alert alert-danger  fade show" >
                <strong>هشدار!</strong> شما {{ $overdueDebts->count() }} طلب سررسید دارید که هنوز پرداخت نشده‌اند.
                لطفاً در <a href="{{ route('debt.list') }}">بخش طلب‌ها</a> وضعیت پرداخت آن‌ها را ثبت کنید.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif
        @if(session()->has('success'))
            <p class="alert alert-success">{{session('success')}}</p>
        @endif
        @if(session()->has('deleteSucces'))
            <p class="alert alert-danger">{{session('deleteSucces')}}</p>
        @endif

        <div class="row ">

            <h5 style="margin-left: 20px;" >ثبت تراکنش جدید:</h5>


            <select id="main-transaction-selector" class="form-select " >
                <option selected>نوع تراکنش را انتخاب کنید...</option>
                <option value="sale">فروش</option>
                <option value="cost"> هزینه</option>
                <option value="ad"> تبلیغات</option>
            </select>


        </div>
        <div style="margin-top: 60px; display: none;" id="section-sale" class="transaction-section">
            <div class="row">
                <h5>فروش</h5>
            </div>
            <form method="post" action="{{route('transaction.submit')}}">
                @csrf
                <div class="row " >
                    <input type="hidden" name="transaction_type_id" value="1">
                    <div class="form-group ">
                        <label>دسته بندی را انتخاب کنید:</label>
                        <select name="category_id" class="form-select form-control" >
                            <option selected>یک گزینه را انتخاب کنید...</option>
                            @foreach($sellCategories as $sellCategory)
                                <option value="{{$sellCategory->id}}" > {{$sellCategory->name}}</option>
                            @endforeach



                        </select>
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>ثبت قیمت فروش:</label>
                        <input class="form-control money-display" type="text"  placeholder="تومان" required>
                        <input type="hidden" class="money-value" name="sellPrice">
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>ثبت قیمت خرید:</label>
                        <input class="form-control money-display" type="text"  placeholder="تومان" required>
                        <input type="hidden" class="money-value" name="buyPrice">
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>ثبت تاریخ تراکنش:</label>
                        <input  class="form-control persianDate" type="text" name="created_at" >
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>توضیحات:</label>
                        <textarea name="description" class="form-control" cols="35" placeholder="اختیاری"></textarea>
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label></label>
                        <button type="submit" class=" btn-success form-control px-5 mt-2">ثبت</button>
                    </div>


                </div>
            </form>

        </div>


        <div style="margin-top: 60px; display: none;" id="section-cost" class="transaction-section">
            <div class="row">
                <h5>هزینه</h5>
            </div>
            <form method="post" action="{{route('transaction.submit')}}">
                @csrf
                <div class="row " >
                    <input type="hidden" name="transaction_type_id" value="2">
                    <div class="form-group ">
                        <label>دسته بندی را انتخاب کنید:</label>
                        <select name="category_id" class="form-select form-control" >
                            <option selected>یک گزینه را انتخاب کنید...</option>
                            @foreach($costCategories as $costCategory)
                                <option value="{{$costCategory->id}}" > {{$costCategory->name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group " style="margin-right: 20px;">
                        <label>ثبت قیمت :</label>
                        <input class="form-control money-display" type="text"  placeholder="تومان" required>
                        <input type="hidden" class="money-value" name="buyPrice">
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>ثبت تاریخ تراکنش:</label>
                        <input  class="form-control persianDate" type="text" name="created_at" >
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>توضیحات:</label>
                        <textarea name="description" class="form-control" cols="35" placeholder="اختیاری"></textarea>
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label></label>
                        <button type="submit" class=" btn-danger form-control px-5 mt-2">ثبت</button>
                    </div>
                </div></form>

        </div>

        <div style="margin-top: 60px; display: none;" id="section-ad" class="transaction-section">
            <div class="row">
                <h5>تبلیغات</h5>
            </div>
            <form method="post" action="{{route('transaction.submit')}}">
                @csrf
                <div class="row " >
                    <input type="hidden" name="transaction_type_id" value="3">
                    <div class="form-group ">
                        <label>دسته بندی را انتخاب کنید:</label>
                        <select name="category_id" class="form-select form-control" >
                            <option selected>یک گزینه را انتخاب کنید...</option>
                            @foreach($adCategories as $adCategory)
                                <option value="{{$adCategory->id}}" > {{$adCategory->name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group " style="margin-right: 20px;">
                        <label>ثبت قیمت :</label>
                        <input class="form-control money-display" type="text"  placeholder="تومان" required>
                        <input type="hidden" class="money-value" name="buyPrice">
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>انتخاب حساب برداشت:</label>
                        <select name="bank_accounts_id" class="form-select form-control" >
                            <option selected>یک گزینه را انتخاب کنید...</option>
                            <option value="1" > حساب نقدی ها </option>
                            @foreach($bankAccounts as $bankAccount)
                                <option value="{{$bankAccount->id}}" > بانک {{$bankAccount->bank_name}} به نام {{$bankAccount->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>ثبت تاریخ تراکنش:</label>
                        <input  class="form-control persianDate" type="text" name="created_at" >
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label>توضیحات:</label>
                        <textarea name="description" class="form-control" cols="35" placeholder="اختیاری"></textarea>
                    </div>
                    <div class="form-group " style="margin-right: 20px;">
                        <label></label>
                        <button type="submit" class="  form-control px-5 mt-2" style="background-color: purple;color: white">ثبت</button>
                    </div>
                </div>
            </form>

        </div>



        <div class="row " style="margin-top: 100px">
            <div class="col-12">
                <div class="table-responsive" >
                    <h5>لیست تراکنش ها</h5>
                    <table  class=" datatable table table-striped table-bordered table-hover">
                        <thead class="thead-dark" style="background-color: black">
                        <tr>
                            <th scope="col">آیدی</th>
                            <th scope="col">نوع تراکنش</th>
                            <th scope="col">دسته بندی</th>
                            <th scope="col">قیمت فروش(تومان)</th>
                            <th scope="col">قیمت خرید(تومان)</th>
                            <th scope="col">تاریخ ثبت</th>
                            <th scope="col">حذف</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>
                                    {{$transaction->transaction_type->name}}

                                </td>
                                <td>{{ $transaction->category->name }}</td>
                                <td>{{ number_format($transaction->sellPrice) }}</td>
                                <td>{{number_format($transaction->buyPrice)  }}</td>

                                <td>{{ $transaction->persian_date }}</td>
                                <td><a href="{{route('transaction.delete',['id'=>$transaction->id])}}" class="btn btn-danger" style="color: white">حذف</a></td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row " style="margin-top: 100px">

            <h5>باکس گزارشات</h5>
        </div>
        <div class="row mt-2">
            <button id="toggleTableBtn" class="btn btn-primary d-block">

                <div>فروش کل</div>
                <div class="mt-3">{{number_format($sellTransTotalAmount) }} تومان</div>
            </button>
            <button id="costBtn" class="btn btn-primary d-block" style="margin-right: 10px">

                <div>هزینه کل</div>
                <div class="mt-3">{{number_format($costTransTotalAmount) }} تومان</div>
            </button>
            <button id="adsBtn" class="btn btn-primary d-block" style="margin-right: 10px">

                <div> سرمایه گذاری تبلیغات</div>
                <div class="mt-3">{{number_format($adsTransTotalAmount) }} تومان</div>
            </button>
            <button  class="btn btn-primary d-block" style="margin-right: 10px">

                <div>   موجودی کل نقدی</div>
                <div class="mt-3">{{number_format($wallet) }} تومان</div>
            </button>
            <button id="profitBtn" class="btn btn-primary d-block" style="margin-right: 10px">

                <div>   سود کل</div>
                <div class="mt-3">{{number_format($profitTotalAmount) }} تومان</div>
            </button>
            <button id="debtBtn" class="btn btn-primary d-block" style="margin-right: 10px">

                <div>    در انتظار پرداخت</div>
                <div class="mt-3">{{number_format($totalUnpaid) }} تومان</div>
            </button>
            <button id="taxBtn" class="btn btn-primary d-block" style="margin-right: 10px">

                <div>   مالیات پرداختی</div>
                <div class="mt-3">{{number_format($taxAmount) }} تومان</div>
            </button>
            <button id="comBtn" class="btn btn-primary d-block" style="margin-right: 10px">

                <div>   کمیسیون پرداختی</div>
                <div class="mt-3">{{number_format($commissionAmount) }} تومان</div>
            </button>
            <button id="logBtn" class="btn btn-primary d-block" style="margin-right: 10px" >

                <div>   لجستیک پرداختی</div>
                <div class="mt-3">{{number_format($logisticsAmount) }} تومان</div>
            </button>
        </div>
        <div class="row mt-2">

        </div>

        <div id="detailsTable" class="row mt-4" style="display: none;">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">اسنپ پی </th>
                            <th scope="col">ترب پی </th>
                            <th scope="col">باسلام </th>
                            <th scope="col">سایت </th>
                            <th scope="col">فروش نقدی </th>
                            <th scope="col">دیجی کالا </th>
                            <th scope="col">اسنپ شاپ  </th>
                            <th scope="col">پاساژ </th>
                            <th scope="col">اینستاگرام </th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{number_format($snapTrans) }}  تومان </td>
                            <td>{{number_format($torobTrans) }}  تومان </td>
                            <td>{{number_format($basalamTrans) }}  تومان </td>
                            <td>{{number_format($siteTrans) }}  تومان </td>
                            <td>{{number_format($naghdiTrans) }}  تومان </td>
                            <td>{{number_format($digikalaTrans) }}  تومان </td>
                            <td>{{number_format($snapshopTrans) }}  تومان </td>
                            <td>{{number_format($pasajTrans) }}  تومان </td>
                            <td>{{number_format($instaTrans) }}  تومان </td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="costTable" class="row mt-4" style="display: none;">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">هزینه کاری </th>
                            <th scope="col">هزینه شخصی </th>


                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{number_format($costKari) }}  تومان </td>
                            <td>{{number_format($costShakhsi) }}  تومان </td>


                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="adsTable" class="row mt-4" style="display: none;">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">تبلیغات اسنپ </th>
                            <th scope="col">تبلیغات ترب </th>
                            <th scope="col">
                                تبلیغات اینستاگرام
                            </th>
                            <th scope="col">تبلبغات باسلام </th>
                            <th scope="col">تبلیغات گوگل </th>
                            <th scope="col">تبلیغات پاساژ </th>
                            <th scope="col">سئو سایت </th>


                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{number_format($snapAds) }}  تومان </td>
                            <td>{{number_format($torobAds) }}  تومان </td>
                            <td>{{number_format($instaAds) }}  تومان </td>
                            <td>{{number_format($basalamAds) }}  تومان </td>
                            <td>{{number_format($googleAds) }}  تومان </td>
                            <td>{{number_format($pasajAds) }}  تومان </td>
                            <td>{{number_format($seoAds) }}  تومان </td>



                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="profitTable" class="row mt-4" style="display: none;">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">اسنپ پی </th>
                            <th scope="col">ترب پی </th>
                            <th scope="col">باسلام </th>
                            <th scope="col">سایت </th>
                            <th scope="col">فروش نقدی </th>
                            <th scope="col">دیجی کالا </th>
                            <th scope="col">اسنپ شاپ  </th>
                            <th scope="col">پاساژ </th>
                            <th scope="col">اینستاگرام </th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{number_format($snapProfit) }}  تومان </td>
                            <td>{{number_format($torobProfit) }}  تومان </td>
                            <td>{{number_format($basalamProfit) }}  تومان </td>
                            <td>{{number_format($siteProfit) }}  تومان </td>
                            <td>{{number_format($naghdiProfit) }}  تومان </td>
                            <td>{{number_format($digikalaProfit) }}  تومان </td>
                            <td>{{number_format($snapshopProfit) }}  تومان </td>
                            <td>{{number_format($pasajProfit) }}  تومان </td>
                            <td>{{number_format($instaProfit) }}  تومان </td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="debtTable" class="row mt-4" style="display: none;">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">اسنپ پی </th>
                            <th scope="col">ترب پی  </th>
                            <th scope="col">باسلام  </th>
                            <th scope="col">پاساژ  </th>


                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{number_format($unpaidCat1) }}  تومان </td>
                            <td>{{number_format($unpaidCat2) }}  تومان </td>
                            <td>{{number_format($unpaidCat3) }}  تومان </td>
                            <td>{{number_format($unpaidCat8) }}  تومان </td>


                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="taxTable" class="row mt-4" style="display: none;">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">اسنپ پی </th>
                            <th scope="col">ترب پی </th>
                            <th scope="col">باسلام </th>
                            <th scope="col">سایت </th>
                            <th scope="col">فروش نقدی </th>
                            <th scope="col">دیجی کالا </th>
                            <th scope="col">اسنپ شاپ  </th>
                            <th scope="col">پاساژ </th>
                            <th scope="col">اینستاگرام </th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{number_format($paidTaxCat1) }}  تومان </td>
                            <td>{{number_format($paidTaxCat2) }}  تومان </td>
                            <td>{{number_format($paidTaxCat3) }}  تومان </td>
                            <td>{{number_format($paidTaxCat4) }}  تومان </td>
                            <td>{{number_format($paidTaxCat5) }}  تومان </td>
                            <td>{{number_format($paidTaxCat6) }}  تومان </td>
                            <td>{{number_format($paidTaxCat7) }}  تومان </td>
                            <td>{{number_format($paidTaxCat8) }}  تومان </td>
                            <td>{{number_format($paidTaxCat9) }}  تومان </td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="comTable" class="row mt-4" style="display: none;">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">اسنپ پی </th>
                            <th scope="col">ترب پی </th>
                            <th scope="col">باسلام </th>
                            <th scope="col">سایت </th>
                            <th scope="col">فروش نقدی </th>
                            <th scope="col">دیجی کالا </th>
                            <th scope="col">اسنپ شاپ  </th>
                            <th scope="col">پاساژ </th>
                            <th scope="col">اینستاگرام </th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{number_format($commissionCat1) }}  تومان </td>
                            <td>{{number_format($commissionCat2) }}  تومان </td>
                            <td>{{number_format($commissionCat3) }}  تومان </td>
                            <td>{{number_format($commissionCat4) }}  تومان </td>
                            <td>{{number_format($commissionCat5) }}  تومان </td>
                            <td>{{number_format($commissionCat6) }}  تومان </td>
                            <td>{{number_format($commissionCat7) }}  تومان </td>
                            <td>{{number_format($commissionCat8) }}  تومان </td>
                            <td>{{number_format($commissionCat9) }}  تومان </td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="logTable" class="row mt-4" style="display: none;">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">اسنپ پی </th>
                            <th scope="col">ترب پی </th>
                            <th scope="col">باسلام </th>
                            <th scope="col">سایت </th>
                            <th scope="col">فروش نقدی </th>
                            <th scope="col">دیجی کالا </th>
                            <th scope="col">اسنپ شاپ  </th>
                            <th scope="col">پاساژ </th>
                            <th scope="col">اینستاگرام </th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{number_format($logisticsCat1) }}  تومان </td>
                            <td>{{number_format($logisticsCat2) }}  تومان </td>
                            <td>{{number_format($logisticsCat3) }}  تومان </td>
                            <td>{{number_format($logisticsCat4) }}  تومان </td>
                            <td>{{number_format($logisticsCat5) }}  تومان </td>
                            <td>{{number_format($logisticsCat6) }}  تومان </td>
                            <td>{{number_format($logisticsCat7) }}  تومان </td>
                            <td>{{number_format($logisticsCat8) }}  تومان </td>
                            <td>{{number_format($logisticsCat9) }}  تومان </td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="container mx-auto p-6 space-y-10">

            <!-- ۱. روند فروش -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-bold">روند فروش — </h3>

                </div>
                <div class="chart-container"><canvas id="salesChart"></canvas></div>
            </div>

            <!-- ۲. مقایسه فروش و سود -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">مقایسه فروش و سود — </h3>
                <div class="chart-container"><canvas id="salesProfitChart"></canvas></div>
            </div>

            <!-- ۳. بهترین پلتفرم‌ها -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">بهترین پلتفرم‌ها — </h3>
                <div class="chart-container"><canvas id="platformChart"></canvas></div>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100"><tr>
                            <th class="px-4 py-2 text-right">پلتفرم</th>
                            <th class="px-4 py-2 text-right">فروش</th>
                            <th class="px-4 py-2 text-right">% فروش</th>
                            <th class="px-4 py-2 text-right">سود</th>
                            <th class="px-4 py-2 text-right">% سود</th>
                        </tr></thead>
                        <tbody>
                        @foreach($platformPerformance['table'] as $row)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $row['name'] }}</td>
                                <td class="px-4 py-2">{{ number_format($row['sales']) }}</td>
                                <td class="px-4 py-2">{{ $row['sales_percent'] }}%</td>
                                <td class="px-4 py-2">{{ number_format($row['profit']) }}</td>
                                <td class="px-4 py-2">{{ $row['profit_percent'] }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>



            <!-- ۵. هزینه‌ها -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-bold">ترکیب هزینه‌ها</h3>

                    </div>
                    <div class="pie-container"><canvas id="expensePieChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">روند هزینه‌ها</h3>
                    <div class="chart-container"><canvas id="expenseTrendChart"></canvas></div>
                </div>
            </div>

            <!-- ۶. تبلیغات -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-bold">هزینه تبلیغات</h3>

                    </div>
                    <div class="pie-container"><canvas id="adSpendPieChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">روند هزینه تبلیغات</h3>
                    <div class="chart-container"><canvas id="adSpendTrendChart"></canvas></div>
                </div>
            </div>

            <!-- ۷. بازدهی تبلیغات -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">بازدهی تبلیغات (تبلیغ vs سود)</h3>
                <div class="chart-container"><canvas id="adEfficiencyChart"></canvas></div>
            </div>

            <!-- ۸. نسبت تبلیغ به درآمد -->
            <div class="bg-white p-6 rounded-lg shadow text-center mt-4">
                <h3 class="text-lg font-bold mb-4">درصد هزینه تبلیغ از درآمد</h3>
                <div class="small-pie mx-auto"><canvas id="adToRevenueChart"></canvas></div>
            </div>





        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const charts = {};

                function createChart(id, data, type = 'line') {
                    const ctx = document.getElementById(id).getContext('2d');
                    if (charts[id]) charts[id].destroy();

                    const chartType = type === 'doughnut' ? 'doughnut' :
                        type === 'bar' ? 'bar' :
                            type === 'scatter' ? 'scatter' : 'line';

                    // لاگ داده‌ها برای دیباگ
                    console.log(`Chart Data for ${id}:`, JSON.parse(JSON.stringify(data)));

                    charts[id] = new Chart(ctx, {
                        type: chartType,
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: { size: 12 },
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    enabled: true,
                                    mode: chartType === 'scatter' ? 'nearest' : 'index',
                                    intersect: chartType === 'scatter' ? true : false,
                                    callbacks: {
                                        label: function(context) {
                                            if (chartType === 'scatter') {
                                                const point = context.raw;
                                                return `${point.platform}: ${point.x.toLocaleString()} تومان (${point.adMonth})، فروش: ${point.y.toLocaleString()} تومان (${point.salesMonth})`;
                                            }
                                            return `${context.dataset.label}: ${context.raw.toLocaleString()}`;
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: id === 'adEfficiencyChart' ? 'بازدهی تبلیغات: هزینه در مقابل فروش ماه بعد' : '',
                                    font: { size: 16 }
                                }
                            },
                            scales: chartType === 'bar' ? {
                                x: { stacked: false },
                                y: { stacked: false, beginAtZero: true }
                            } : chartType === 'scatter' ? {
                                x: {
                                    title: { display: true, text: 'هزینه تبلیغات (تومان)' },
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString();
                                        }
                                    }
                                },
                                y: {
                                    title: { display: true, text: 'فروش ماه بعد (تومان)' },
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString();
                                        }
                                    }
                                }
                            } : chartType === 'line' ? {
                                x: { stacked: false },
                                y: { stacked: false, beginAtZero: true }
                            } : {} // برای doughnut هیچ مقیاسی اعمال نمی‌شود
                        }
                    });
                }

                // رندر اولیه نمودارها
                createChart('salesChart', @json($salesData));
                createChart('salesProfitChart', @json($salesProfitData), 'bar');
                createChart('platformChart', @json($platformPerformance['chart']), 'bar');
                createChart('expensePieChart', @json($expenseBreakdown['pie']), 'doughnut');
                createChart('expenseTrendChart', @json($expenseBreakdown['trend']));
                createChart('adSpendPieChart', @json($adSpendByCategory), 'doughnut');
                createChart('adSpendTrendChart', @json($adSpendTrend));
                createChart('adEfficiencyChart', @json($adEfficiency), 'scatter');
                createChart('adToRevenueChart', @json($adToRevenueRatio), 'doughnut');
                createChart('beforeAfterChart', @json($beforeAfterAd), 'bar');
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // همه فیلدهای نمایش قیمت را پیدا کن
                const moneyDisplays = document.querySelectorAll('.money-display');

                moneyDisplays.forEach(function (moneyDisplay) {
                    // فیلد مخفی مرتبط را در همان فرم یا والد نزدیک پیدا کن
                    // اگر name="sellPrice" در hidden input هست، از آن استفاده کن
                    const moneyValue = moneyDisplay.parentElement.querySelector('input[name="sellPrice"]') ||
                        moneyDisplay.closest('.form-group').querySelector('.money-value');

                    // اگر فیلد مخفی پیدا نشد، می‌توانیم آن را بسازیم (اختیاری)
                    if (!moneyValue) {
                        console.warn('فیلد مخفی .money-value یا input[name="sellPrice"] پیدا نشد برای:', moneyDisplay);
                        return;
                    }

                    // تابع فرمت کردن عدد
                    function formatNumber(num) {
                        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }

                    // وقتی کاربر تایپ می‌کند
                    moneyDisplay.addEventListener('input', function () {
                        let value = this.value.replace(/[^\d]/g, ''); // فقط اعداد

                        // ذخیره مقدار خام در فیلد مخفی
                        moneyValue.value = value;

                        // نمایش فرمت‌شده
                        this.value = value ? formatNumber(value) : '';
                    });

                    // هنگام لود صفحه، اگر مقدار اولیه داشت، فرمت کن
                    if (moneyDisplay.value.trim() !== '') {
                        let rawValue = moneyDisplay.value.replace(/[^\d]/g, '');
                        if (rawValue) {
                            moneyDisplay.value = formatNumber(rawValue);
                            moneyValue.value = rawValue;
                        }
                    }
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // 1. مدیریت select اصلی
                const mainSelector = document.getElementById('main-transaction-selector');
                const sections = document.querySelectorAll('.transaction-section');

                function showSection(sectionId) {
                    sections.forEach(sec => {
                        sec.style.display = sec.id === sectionId ? 'block' : 'none';
                    });
                }

                // وقتی کاربر انتخاب کرد
                mainSelector.addEventListener('change', function () {
                    const value = this.value;
                    if (value) {
                        showSection('section-' + value);
                    } else {
                        sections.forEach(sec => sec.style.display = 'none');
                    }
                });

                // 2. مدیریت select داخل هر بخش (مثلاً "دسته‌بندی را انتخاب کنید")
                document.querySelectorAll('.form-select.form-control').forEach(select => {
                    select.addEventListener('change', function () {
                        const section = this.closest('.transaction-section');
                        if (section) {
                            showSection(section.id);
                            // اگر می‌خوای select اصلی هم آپدیت بشه:
                            const type = section.id.split('-')[1]; // sale, cost, ad
                            mainSelector.value = type;
                        }
                    });
                });

                // 3. فرمت قیمت (کد قبلی شما — اصلاح‌شده)
                document.querySelectorAll('.money-display').forEach(moneyDisplay => {
                    const hiddenInput = moneyDisplay.parentElement.querySelector('input[type="hidden"]');
                    if (!hiddenInput) return;

                    function formatNumber(num) {
                        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }

                    moneyDisplay.addEventListener('input', function () {
                        let value = this.value.replace(/[^\d]/g, '');
                        hiddenInput.value = value;
                        this.value = value ? formatNumber(value) : '';
                    });

                    // مقدار اولیه
                    if (moneyDisplay.value.trim()) {
                        let raw = moneyDisplay.value.replace(/[^\d]/g, '');
                        moneyDisplay.value = formatNumber(raw);
                        hiddenInput.value = raw;
                    }
                });

                // 4. تاریخ شمسی (اختیاری — بعداً اضافه کن)
                // مثلاً با persian-datepicker
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const btn = document.getElementById('toggleTableBtn');
                const table = document.getElementById('detailsTable');

                btn.addEventListener('click', function () {
                    if (table.style.display === 'none' || table.style.display === '') {
                        // نمایش جدول
                        table.style.display = 'block';
                        setTimeout(() => table.classList.add('show')); // برای انیمیشن


                    } else {
                        // مخفی کردن جدول
                        table.classList.remove('show');
                        setTimeout(() => table.style.display = 'none'); // تطابق با زمان انیمیشن


                    }
                });


                const costbtn = document.getElementById('costBtn');
                const costtable = document.getElementById('costTable');

                costbtn.addEventListener('click', function () {
                    if (costtable.style.display === 'none' || costtable.style.display === '') {
                        // نمایش جدول
                        costtable.style.display = 'block';
                        setTimeout(() => costtable.classList.add('show')); // برای انیمیشن


                    } else {
                        // مخفی کردن جدول
                        costtable.classList.remove('show');
                        setTimeout(() => costtable.style.display = 'none'); // تطابق با زمان انیمیشن


                    }
                });
                const adsBtn = document.getElementById('adsBtn');
                const adsTable = document.getElementById('adsTable');

                adsBtn.addEventListener('click', function () {
                    if (adsTable.style.display === 'none' || adsTable.style.display === '') {
                        // نمایش جدول
                        adsTable.style.display = 'block';
                        setTimeout(() => adsTable.classList.add('show')); // برای انیمیشن


                    } else {
                        // مخفی کردن جدول
                        adsTable.classList.remove('show');
                        setTimeout(() => adsTable.style.display = 'none'); // تطابق با زمان انیمیشن


                    }
                });
                const profitBtn = document.getElementById('profitBtn');
                const profitTable = document.getElementById('profitTable');

                profitBtn.addEventListener('click', function () {
                    if (profitTable.style.display === 'none' || profitTable.style.display === '') {
                        // نمایش جدول
                        profitTable.style.display = 'block';
                        setTimeout(() => adsTable.classList.add('show')); // برای انیمیشن


                    } else {
                        // مخفی کردن جدول
                        profitTable.classList.remove('show');
                        setTimeout(() => profitTable.style.display = 'none'); // تطابق با زمان انیمیشن


                    }
                });
                const debtBtn = document.getElementById('debtBtn');
                const debtTable = document.getElementById('debtTable');

                debtBtn.addEventListener('click', function () {
                    if (debtTable.style.display === 'none' || debtTable.style.display === '') {
                        // نمایش جدول
                        debtTable.style.display = 'block';
                        setTimeout(() => adsTable.classList.add('show')); // برای انیمیشن


                    } else {
                        // مخفی کردن جدول
                        debtTable.classList.remove('show');
                        setTimeout(() => debtTable.style.display = 'none'); // تطابق با زمان انیمیشن


                    }
                });
                const taxBtn = document.getElementById('taxBtn');
                const taxTable = document.getElementById('taxTable');

                taxBtn.addEventListener('click', function () {
                    if (taxTable.style.display === 'none' || taxTable.style.display === '') {
                        // نمایش جدول
                        taxTable.style.display = 'block';
                        setTimeout(() => adsTable.classList.add('show')); // برای انیمیشن


                    } else {
                        // مخفی کردن جدول
                        taxTable.classList.remove('show');
                        setTimeout(() => taxTable.style.display = 'none'); // تطابق با زمان انیمیشن


                    }
                });
                const comBtn = document.getElementById('comBtn');
                const comTable = document.getElementById('comTable');

                comBtn.addEventListener('click', function () {
                    if (comTable.style.display === 'none' || comTable.style.display === '') {
                        // نمایش جدول
                        comTable.style.display = 'block';
                        setTimeout(() => adsTable.classList.add('show')); // برای انیمیشن


                    } else {
                        // مخفی کردن جدول
                        comTable.classList.remove('show');
                        setTimeout(() => comTable.style.display = 'none'); // تطابق با زمان انیمیشن


                    }
                });
                const logBtn = document.getElementById('logBtn');
                const logTable = document.getElementById('logTable');

                logBtn.addEventListener('click', function () {
                    if (logTable.style.display === 'none' || logTable.style.display === '') {
                        // نمایش جدول
                        logTable.style.display = 'block';
                        setTimeout(() => adsTable.classList.add('show')); // برای انیمیشن


                    } else {
                        // مخفی کردن جدول
                        logTable.classList.remove('show');
                        setTimeout(() => logTable.style.display = 'none'); // تطابق با زمان انیمیشن


                    }
                });
            });





        </script>
    @endsection
