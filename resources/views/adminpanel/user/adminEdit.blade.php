@extends('AdminPanel.layout.master')

@section('title')
    ویرایش ادمین
@endsection

@section('content')
    <div class="container">
        <h4 class="card-title">ویرایش ادمین</h4>
        <form action="{{route('editA')}}" method="POST" >
            @method('PUT')
            @csrf

            <div class="form-group row">
                <label  class="col-sm-2 col-form-label">نام و نام خانوادگی</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control text-left"  dir="rtl" name="name" value="{{$user->name}}" required>
                </div>
            </div>
            <div class="form-group row">
                <label  class="col-sm-2 col-form-label">نام کاربری</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control text-left" dir="rtl"  value="{{$user->username}}" name="username" required >
                </div>
            </div>

            <div class="form-group row">
                <label  class="col-sm-2 col-form-label">رمز عبور</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control text-left" dir="rtl" name="password" required>
                </div>
            </div>

             <input type="hidden" name="id" value="{{$user->id}}">


            <div class="form-group row">
                <button type="submit" class="btn btn-success btn-uppercase">
                    <i class="ti-check-box m-r-5"></i> ویرایش
                </button>

            </div>
        </form>
    </div>
@endsection
