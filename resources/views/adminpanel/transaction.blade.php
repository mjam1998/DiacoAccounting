@extends('AdminPanel.layout.master')

@section('title')
    تراکنش ها
@endsection

@section('content')
    <style>
        /* کد بالا را اینجا قرار دهید */
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

