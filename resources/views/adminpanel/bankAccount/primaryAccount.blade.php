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
                        <input type="text" id="walletAmount" class="form-control text-left" value="{{$bank->wallet}}"  dir="rtl" readonly >
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
    function formatNumber(num) {
        // تبدیل به عدد و سپس فرمت کردن
        const number = parseFloat(num) || 0;
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const walletElement = document.getElementById('walletAmount');

        if (walletElement) {
            const currentValue = walletElement.value || walletElement.textContent;
            const formattedValue = formatNumber(currentValue);

            // اگر input است
            if (walletElement.type === 'text') {
                walletElement.value = formattedValue;
            } else { // اگر element دیگر است
                walletElement.textContent = formattedValue;
            }
        }
    });
</script>
@endsection
