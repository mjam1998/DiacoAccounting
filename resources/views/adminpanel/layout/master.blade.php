<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> حسابداری دیاکو </title>
    <link rel="shortcut icon" href="{{asset('AdminPanel/assets/media/image/accounting.png')}}">
    <meta name="theme-color" content="#5867dd">
    <link rel="stylesheet" href="{{asset('AdminPanel/vendors/bundle.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('AdminPanel/vendors/slick/slick.css')}}">
    <link rel="stylesheet" href="{{asset('AdminPanel/vendors/slick/slick-theme.css')}}">
    <link rel="stylesheet" href="{{asset('AdminPanel/vendors/vmap/jqvmap.min.css')}}">
    <link rel="stylesheet" href="{{asset('AdminPanel/assets/css/app.css')}}" type="text/css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{asset('AdminPanel/assets/css/persian-datepicker.min.css')}}"/>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body class="small-navigation">
<div class="navigation">
    <div class="navigation-icon-menu">
        <ul>
            <li data-toggle="tooltip" title="تراکنش ها">
                <a href="#transaction" title=" تراکنش ها">
                    <i class=" icon ti-package bi bi-card-checklist"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title="چک ها">
                <a href="#checks" title=" چک ها">
                    <i class=" icon ti-package bi bi-bank"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title=" تنظیم درصد تراکنش های فروش">
                <a href="#percentCategory" title=" تنظیم درصد تراکنش های فروش">
                    <i class="icon ti-user bi bi-percent"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title="ادمین">
                <a href="#users" title=" ادمین">
                    <i class="icon ti-user"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title=" حساب های بانکی">
                <a href="#banks" title=" حساب های بانکی">
                    <i class="icon ti-user bi bi-credit-card"></i>
                </a>
            </li>

            <li data-toggle="tooltip" title=" طلب های قسطی">
                <a href="#debts" title=" طلب های قسطی">
                    <i class="icon ti-user bi bi-cash-coin"></i>
                </a>
            </li>

        </ul>

        <ul>

            <li data-toggle="tooltip" title="خروج">
                <a href="{{route('logOut')}}" class="go-to-page">
                    <i class="icon ti-power-off"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="navigation-menu-body">
        <ul id="users">
            <li>
                <a href="#">ادمین</a>
                <ul>
                    <li><a href="{{route('AdminAdd')}}">ایجاد ادمین</a></li>
                    <li><a href="{{route('AdminList')}}">لیست ادمین</a></li>
                </ul>
            </li>
        </ul>
        <ul id="percentCategory">
            <li>
                <a href="{{route('percentTransactionCategory')}}">تنظیم درصد</a>
               {{-- <ul>
                    <li><a href=""> تنظیم درصد تراکنش های فروش</a></li>

                </ul>--}}
            </li>
        </ul>
        <ul id="banks">
            <li>
                <a href="#">حساب های بانکی</a>
                <ul>
                    <li><a href="{{route('bankAccount.primary')}}">حساب اصلی نقدی دردسترس </a></li>
                    <li><a href="{{route('bankAccount.list')}}">لیست حساب های بانکی دیگر</a></li>
                </ul>
            </li>
        </ul>
        <ul id="transaction">
            <li>
                <a href="{{route('AdminHome')}}">تراکنش ها</a>

            </li>
        </ul>

    </div>
</div>
<!-- end::navigation -->
<!-- begin::header -->
<div class="header">
    <!-- begin::header body -->
    <div class="header-body">
        <div class="header-body-left">
            <h3 class="page-title">نرم افزار حسابداری دیاکو</h3>
            <!-- begin::breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('AdminHome')}}">داشبورد</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> @yield('title')  </li>
                </ol>
            </nav>
        </div>

            <!-- end::breadcrumb -->
            <div class="header-body-right">
                <div class="d-flex align-items-center">
                    <!-- begin::navbar navigation toggler -->
                    <div class="d-xl-none d-lg-none d-sm-block navigation-toggler">
                        <a href="#">
                            <i class="ti-menu"></i>
                        </a>
                    </div>
                    <!-- end::navbar navigation toggler -->

                    <!-- begin::navbar toggler -->

                    <!-- end::navbar toggler -->
                </div>
            </div>



    </div>
    <!-- end::header body -->

</div>
<!-- end::header -->
<!-- begin::main content -->
<main class="main-content">
  <div class="card">
        <div class="card-body">

                @yield('content')

        </div>
    </div>


</main>


<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script src="{{asset('AdminPanel/vendors/bundle.js')}}"></script>
<script src="{{asset('AdminPanel/vendors/slick/slick.min.js')}}"></script>
<script src="{{asset('AdminPanel/vendors/vmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('AdminPanel/assets/js/app.js')}}"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="{{asset('AdminPanel/assets/js/persian-date.min.js')}}"></script>
<script src="{{asset('AdminPanel/assets/js/persian-datepicker.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.persianDate').persianDatepicker({
            format: 'YYYY/MM/DD', // فقط سال/ماه/روز
            timePicker: {
                enabled: false // غیرفعال کردن انتخاب زمان
            },
            toolbox: {
                calendarSwitch: {
                    enabled: false // غیرفعال کردن سوئیچ تقویم
                }
            },
            observer: true,
            altField: '#dateInput'
        });
    });
</script>

</body>
</html>
