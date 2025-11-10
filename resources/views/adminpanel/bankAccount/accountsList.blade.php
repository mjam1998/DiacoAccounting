@extends('AdminPanel.layout.master')

@section('title')
    اطلاعات حساب ها
@endsection

@section('content')
    <div class="container">
        <h4 class="card-title"> اطلاعات حساب ها </h4>
        @if(session()->has('addAccount'))
            <p class="alert alert-success">{{session('addAccount')}}</p>

        @endif
<div class="mb-3">
    <form method="POST" action="{{route('addBankAccount')}}"  >
        @csrf

        <div class="form-group row">
            <label  class="col-sm-2 col-form-label">نام صاحب حساب</label>
            <div class="col-sm-3">
                <input type="text" class="form-control text-left"  dir="rtl" name="name"  >
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-sm-2 col-form-label">نام بانک </label>
            <div class="col-sm-3">
                <input type="text" class="form-control text-left"  dir="rtl" name="bank_name" >
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-sm-2 col-form-label">شماره کارت  </label>
            <div class="col-sm-3">
                <input type="text" class="form-control text-left"  dir="rtl" name="account_card"  >
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-sm-2 col-form-label">شماره حساب  </label>
            <div class="col-sm-3">
                <input type="text" class="form-control text-left"  dir="rtl" name="account_number"  >
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-sm-2 col-form-label">شماره شبا </label>
            <div class="col-sm-3">
                <input type="text" class="form-control text-left"  dir="rtl" name="account_shaba" >
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
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">نام صاحب حساب</th>
                            <th scope="col">نام بانک</th>
                            <th scope="col">شماره کارت</th>
                            <th scope="col">شماره حساب</th>
                            <th scope="col">شماره شبا</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banks as $bank)
                            <tr>
                                <td>{{ $bank->name }}</td>
                                <td>{{ $bank->bank_name }}</td>
                                <td>{{ $bank->account_card }}</td>
                                <td>{{ $bank->account_number }}</td>
                                <td>{{ $bank->account_shaba }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>





    </div>

@endsection

