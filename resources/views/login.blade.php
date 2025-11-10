@extends('Layout.master')
 @section('content')

     <!-- begin::page loader-->
     <div class="page-loader">
         <div class="spinner-border"></div>
     </div>
     <!-- end::page loader -->

     <div class="form-wrapper " style="border:1px solid #0d153c;">

         <!-- logo -->
         <div class="logo"  >
             <img src="{{asset('AdminPanel/assets/media/image/accounting.png')}}" class="img-fluid" alt="image">
         </div>
         <!-- ./ logo -->
         <h2>نرم افزار حسابداری دیاکو</h2>
         <h5 style="color: #0d5ff5">ورود به حساب کاربری</h5>
         @if(session()->has('loginMessage'))
             <div class="alert alert-danger">
                 {{ session()->get('loginMessage') }}
             </div>
         @endif
         <!-- form -->
         <form action="{{route('login.post')}}" method="post">
             @csrf
             <div class="form-group">
                 <input type="text" name="username" class="form-control text-left" placeholder="نام کاربری" dir="ltr" required autofocus>
             </div>
             <div class="form-group">
                 <input type="text" name="password" class="form-control text-left" placeholder=" رمز عبور" dir="ltr" required>
             </div>

             <button type="submit" class="btn btn-primary btn-block">ورود</button>
             <hr>

         </form>
         <!-- ./ form -->

 @endsection
