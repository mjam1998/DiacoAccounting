@extends('AdminPanel.layout.master')

@section('title')
    چک ها
@endsection

@section('content')
    <style>
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
    <div class="container">
        <h4 class="card-title">  چک ها </h4>
        @if(session()->has('addcheck'))
            <p class="alert alert-success">{{session('addcheck')}}</p>

        @endif

        @if(session()->has('deleteCheck'))
            <p class="alert alert-danger">{{session('deleteCheck')}}</p>

        @endif
        <div class="mb-3">
            <form method="POST" action="{{route('bankChecks.submit')}}"  >
                @csrf

                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label">مبلغ چک</label>
                    <div class="col-sm-3">
                        <input class="form-control money-display" type="text"  placeholder="تومان" required>
                        <input type="hidden" class="money-value" name="check_amount">
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label"> تاریخ سر رسید </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control persianDate text-left"  dir="rtl" name="check_date" >
                    </div>
                </div>
                <div class="form-group row" >
                    <label class="col-sm-2 col-form-label">انتخاب حساب چک:</label>
                    <div class="col-sm-3">
                        <select name="bankAccount_id" class="form-select form-control" >
                            <option selected>یک گزینه را انتخاب کنید...</option>

                            @foreach($bankAccounts as $bankAccount)
                                <option value="{{$bankAccount->id}}" > بانک {{$bankAccount->bank_name}} به نام {{$bankAccount->name}}</option>
                            @endforeach

                        </select>
                    </div>

                </div>
                <div class="form-group row">
                    <label  class="col-sm-2 col-form-label"> توضیحات  </label>
                    <div class="col-sm-3">
                        <textarea name="description" class="form-control"> </textarea>
                    </div>
                </div>





                <div class="form-group row">
                    <button type="submit" class="btn btn-success btn-uppercase">
                        <i class="ti-check-box m-r-5"></i> ذخیره
                    </button>

                </div>
            </form>
        </div>


        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="datatable table table-striped table-bordered table-hover">
                        <thead class="thead-dark" style="background-color: black">
                        <tr>
                            <th scope="col">آیدی</th>
                            <th scope="col"> مبلغ چک</th>
                            <th scope="col">تاریخ سرسید</th>
                            <th scope="col">حساب برداشت </th>
                            <th scope="col"> توضیحات</th>
                            <th scope="col">تاریخ ثبت</th>
                            <th scope="col"> حذف</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($checks as $check)
                            <tr>
                                <td>{{ $check->id }}</td>
                                <td>{{number_format($check->check_amount)  }} تومان</td>
                                <td>{{$check->persianDate }}</td>
                                <td>  بانک {{$check->bankAccount->bank_name}} به نام {{$check->bankAccount->name}}</td>
                                <td>{{ $check->description }}</td>
                                <td>{{ $check->persianCreate }}</td>
                                <td><a href="{{route('bankCheck.delete',['id'=>$check->id])}}" class="btn btn-danger" style="color: white">حذف</a></td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>





    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // مدیریت فرمت مبلغ
            document.querySelectorAll('.money-display').forEach(moneyDisplay => {
                const hiddenInput = moneyDisplay.parentElement.querySelector('.money-value');
                if (!hiddenInput) return;

                function formatNumber(num) {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }

                moneyDisplay.addEventListener('input', function () {
                    let value = this.value.replace(/[^\d]/g, '');
                    hiddenInput.value = value;
                    this.value = value ? formatNumber(value) + ' تومان' : '';
                });

                // مقدار اولیه اگر وجود دارد
                if (hiddenInput.value) {
                    moneyDisplay.value = formatNumber(hiddenInput.value) + ' تومان';
                }
            });

            // اعتبارسنجی فرم قبل از ارسال
            document.getElementById('checkForm').addEventListener('submit', function(e) {
                const amountInput = document.querySelector('.money-value');
                if (!amountInput.value || amountInput.value === '0') {
                    e.preventDefault();
                    alert('لطفا مبلغ چک را وارد کنید');
                    return false;
                }

                // اطمینان از اینکه مقدار عددی است
                const numericValue = amountInput.value.replace(/[^\d]/g, '');
                amountInput.value = numericValue;
            });
        });
    </script>
@endsection


