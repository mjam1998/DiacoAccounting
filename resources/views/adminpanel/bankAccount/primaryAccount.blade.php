@extends('AdminPanel.layout.master')

@section('title')
حساب نقدی دردسترس
@endsection

@section('content')
<div class="container">

    <h4 class="card-title"> حساب  اصلی نقدی دردسترس</h4>
@if(session()->has('editAccountPrimary'))
        <p class="alert alert-success">{{session('editAccountPrimary')}}</p>

    @endif

            <form method="POST" action="{{route('editPrimary')}}" >
               @csrf
                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label"> موجودی حساب نقدی دردسترس(تومان) </label>
                    <div class="col-sm-3">

                        <input type="text" id="walletAmount" class="form-control text-left money-display"  value="{{number_format($bank->wallet)}}"  dir="rtl"  >
                        <input type="hidden" class="money-value" name="wallet">
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label">نام صاحب حساب</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control text-left"  dir="rtl" name="name" value="{{$bank->name}}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label">نام بانک </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control text-left"  dir="rtl" name="bank_name" value="{{$bank->bank_name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label">شماره کارت  </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control text-left"  dir="rtl" name="account_card" value="{{$bank->account_card}}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label">شماره حساب  </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control text-left"  dir="rtl" name="account_number" value="{{$bank->account_number}}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label">شماره شبا </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control text-left"  dir="rtl" name="account_shaba" value="{{$bank->account_shaba}}">
                    </div>
                </div>




                <div class="form-group row">
                    <button type="submit" class="btn btn-success btn-uppercase">
                        <i class="ti-check-box m-r-5"></i> ذخیره
                    </button>

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
@endsection
