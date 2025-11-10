@extends('AdminPanel.layout.master')

@section('title')
    ایجاد ادمین
@endsection

@section('content')
    <div class="container">
        <h4 class="card-title">ایجاد ادمین</h4>
        <form action="{{route('CreateAdmin')}}" method="POST" >
            @csrf
            <div class="form-group row">
                <label  class="col-sm-2 col-form-label">نام و نام خانوادگی</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control text-left"  dir="rtl" name="name" required>
                </div>
            </div>
            <div class="form-group row">
                <label  class="col-sm-2 col-form-label">نام کاربری</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control text-left" dir="rtl" name="username" required >
                </div>
            </div>

            <div class="form-group row">
                <label  class="col-sm-2 col-form-label">رمز عبور</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control text-left" dir="rtl" name="password" required>
                </div>
            </div>




            <div class="form-group row">
                <button type="submit" class="btn btn-success btn-uppercase">
                    <i class="ti-check-box m-r-5"></i> ذخیره
                </button>

            </div>
        </form>
    </div>
@endsection
