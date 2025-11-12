@extends('AdminPanel.layout.master')

@section('title')
    تراکنش ها
@endsection

@section('content')

        <h4 class="card-title">تراکنش ها</h4>

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
@endsection

