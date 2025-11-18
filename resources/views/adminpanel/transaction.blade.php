@extends('adminpanel.layout.master')

@section('title')
    تراکنش ها
@endsection

@section('content')
    <style>

        .chart-container {
            position: relative;
            height: 400px; /* برای نمودارهای خطی، میله‌ای و پراکندگی */
            width: 100%;
        }
        .pie-container {
            position: relative;
            height: 350px; /* افزایش ارتفاع برای نمودارهای دایره‌ای */
            width: 100%;
            max-width: 450px; /* محدود کردن عرض برای تناسب بهتر */
            margin: 0 auto; /* وسط‌چین کردن */
        }
        .small-pie {
            position: relative;
            height: 300px; /* افزایش ارتفاع برای نمودار دایره‌ای کوچک */
            width: 100%;
            max-width: 350px; /* محدود کردن عرض */
            margin: 0 auto; /* وسط‌چین کردن */
        }
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
        /* ====================== 1. جدول اصلی تراکنش‌ها ====================== */
        .transactions-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* اسکرول نرم در iOS */
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .transactions-table-wrapper table {
            min-width: 900px; /* مهم: جدول هیچوقت کوچکتر از این نمیشه */
            width: 100%;
            white-space: nowrap; /* متن‌ها خط جدید نمی‌زنن */
        }

        /* استایل ثابت برای هدر و بدنه */
        .transactions-table-wrapper table th,
        .transactions-table-wrapper table td {
            min-width: 100px;
            text-align: center !important;
            padding: 12px 8px !important;
            font-size: 14px !important; /* فونت ثابت و خوانا */
        }

        .transactions-table-wrapper table th {
            background-color: #000 !important;
            color: #fff !important;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* ====================== 2. جدول بهترین پلتفرم‌ها ====================== */
        .platform-performance-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .platform-performance-table-wrapper table {
            min-width: 700px; /* این جدول کوچکتره، ولی بازم اسکرول افقی می‌خواد */
            width: 100%;
            font-size: 14px;
        }

        .platform-performance-table-wrapper th,
        .platform-performance-table-wrapper td {
            padding: 10px 8px !important;
            text-align: center !important;
        }

        .platform-performance-table-wrapper th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        /* ====================== ریسپانسیو کلی ====================== */
        @media (max-width: 768px) {
            .transactions-table-wrapper table th,
            .transactions-table-wrapper table td,
            .platform-performance-table-wrapper table th,
            .platform-performance-table-wrapper table td {
                font-size: 13px !important;
                padding: 10px 6px !important;
            }

            /* اگر واقعاً صفحه خیلی کوچیک بود، حداقل خوانایی رو حفظ کن */
            .transactions-table-wrapper table {
                min-width: 800px;
            }
        }

        @media (max-width: 480px) {
            .transactions-table-wrapper table th,
            .transactions-table-wrapper table td {
                font-size: 12px !important;
                padding: 8px 4px !important;
            }
        }
        /* ==================== باکس‌های گزارشات — کاملاً ریسپانسیو ==================== */
        .report-buttons-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 20px 6px;
            padding: 0 6px;
        }

        /* تبلت */
        @media (min-width: 640px) {
            .report-buttons-wrapper {
                gap: 14px;
                padding: 0 10px;
            }
        }

        @media (min-width: 768px) {
            .report-buttons-wrapper {
                grid-template-columns: repeat(3, 1fr);
                gap: 16px;
            }
        }

        @media (min-width: 1024px) {
            .report-buttons-wrapper {
                grid-template-columns: repeat(4, 1fr);
                gap: 18px;
            }
        }

        @media (min-width: 1400px) {
            .report-buttons-wrapper {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        /* ================ سایه و افکت‌های جدید برای دکمه‌های گزارشات ================ */
        .report-buttons-wrapper .btn {
            width: 100% !important;
            margin: 0 !important;
            padding: 20px 14px !important;
            text-align: center;
            font-size: 15px;
            white-space: normal !important;
            height: auto !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: none !important;
            border-radius: 16px !important;
            box-shadow:
                0 8px 20px rgba(0, 0, 0, 0.25),   /* سایه اصلی عمیق و سیاه */
                0 4px 10px rgba(0, 0, 0, 0.2),    /* لایه دوم برای عمق بیشتر */
                inset 0 1px 0 rgba(255, 255, 255, 0.15) !important; /* یه نور کوچیک داخل برای حس برجستگی */
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px); /* افکت شیشه‌ای خیلی ظریف (اختیاری اما خیلی شیک) */
        }

        /* هاور: بالا آمدن + سایه خیلی سیاه و قوی */
        .report-buttons-wrapper .btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.4),
                0 12px 20px rgba(0, 0, 0, 0.3),
                0 0 20px rgba(0, 0, 0, 0.15) !important;
        }

        /* کلیک: کمی فرو رفتگی طبیعی */
        .report-buttons-wrapper .btn:active {
            transform: translateY(-4px) scale(0.99);
            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.35),
                0 6px 12px rgba(0, 0, 0, 0.25) !important;
        }

        /* افکت براق متحرک (Glare) — خیلی شیک و مدرن */
        .report-buttons-wrapper .btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                135deg,
                transparent 30%,
                rgba(255, 255, 255, 0.2) 50%,
                transparent 70%
            );
            transform: translateX(-100%) rotate(45deg);
            transition: none;
        }

        .report-buttons-wrapper .btn:hover::before {
            animation: shine 1.2s ease-in-out;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        /* ==================== جدول‌های مخفی (بازشو با کلیک) — اسکرول افقی + خوانا ==================== */
        .details-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: white;
        }

        .details-table-wrapper table {
            min-width: 800px;        /* هیچوقت کوچکتر از این نمیشه */
            width: 100%;
            white-space: nowrap;
            font-size: 14px;
        }

        .details-table-wrapper table th,
        .details-table-wrapper table td {
            padding: 12px 8px !important;
            text-align: center !important;
            min-width: 100px;
        }

        .details-table-wrapper table th {
            background-color: #000 !important;
            color: white !important;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* وقتی جدول باز میشه یه انیمیشن نرم داشته باشه */
        #detailsTable,
        #costTable,
        #adsTable,
        #profitTable,
        #debtTable,
        #taxTable,
        #comTable,
        #logTable {
            transition: all 0.4s ease-in-out;
            opacity: 0;
            transform: translateY(-10px);
        }

        #detailsTable.show,
        #costTable.show,
        #adsTable.show,
        #profitTable.show,
        #debtTable.show,
        #taxTable.show,
        #comTable.show,
        #logTable.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* موبایل — کمی فونت کوچکتر ولی همچنان خوانا */
        @media (max-width: 576px) {


            .details-table-wrapper table {
                min-width: 700px;
                font-size: 13px;
            }

            .details-table-wrapper table th,
            .details-table-wrapper table td {
                padding: 10px 6px !important;
                font-size: 13px;
            }
        }

        /* حذف پدینگ از داخل کارت‌هایی که نمودار دارن — فقط در موبایل و تبلت هم قشنگ بشه */
        .chart-card {
            padding: 0 !important;
            overflow: hidden; /* مهم: برای اینکه گوشه‌های گرد کارت هم حفظ بشه */
        }

        /* جایگزین کامل کلاس‌های pie-container و small-pie */
        .pie-container,
        .small-pie {
            position: relative;
            height: 380px;           /* یه کم بزرگتر از قبل */
            width: 100%;
            max-width: 100% !important;  /* مهم: محدودیت عرض حذف بشه */
            margin: 0 auto;
            padding: 0 20px;         /* یه فاصله کوچیک از چپ و راست برای زیبایی */
            box-sizing: border-box;
        }

        /* مخصوص دسکتاپ: نمودار دایره‌ای بزرگتر و کاملاً وسط */
        @media (min-width: 768px) {
            .pie-container,
            .small-pie {
                height: 420px;
                padding: 0 40px;     /* فاصله بیشتر در دسکتاپ */
            }

            /* اگه می‌خوای تو دسکتاپ حتی بزرگتر بشه (اختیاری) */
            .pie-container {
                height: 460px;
            }
        }

        /* موبایل: کوچیک‌تر ولی همچنان وسط و خوش‌فرم */
        @media (max-width: 767px) {
            .pie-container,
            .small-pie {
                height: 340px;
                padding: 0 10px;
            }
        }

        /* ==================== کارت اصلی انتخاب نوع تراکنش ==================== */
        .transaction-selector-card {
            background: linear-gradient(135deg, #1e3a8a, #1e40af) !important;
            color: white;
            padding: 24px 30px;
            border-radius: 20px;
            box-shadow:
                0 10px 30px rgba(30, 58, 138, 0.4),
                0 6px 15px rgba(0, 0, 0, 0.2);
            margin: 30px 0;
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        .transaction-selector-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: 0.8s;
        }
        .transaction-selector-card:hover::before {
            transform: translateX(100%);
        }
        .transaction-selector-card h5 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .transaction-selector-card select {
            background: rgba(255,255,255,0.95);
            color: #1e40af;
            font-weight: bold;
            border: none !important;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* ==================== کارت‌های فرم تراکنش (فروش / هزینه / تبلیغات) ==================== */
        .transaction-section {
            margin-top: 40px !important;
            padding: 30px;
            border-radius: 24px;
            box-shadow:
                0 15px 35px rgba(0, 0, 0, 0.15),
                0 5px 15px rgba(0, 0, 0, 0.1);
            background: white;
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
            border-top: 8px solid transparent;
            transition: all 0.4s ease;
        }

        /* حاشیه رنگی بالا — هماهنگ با نوع تراکنش */
        #section-sale { border-top-color: #10b981 !important; }    /* سبز زمردی */
        #section-cost { border-top-color: #ef4444 !important; }    /* قرمز */
        #section-ad   { border-top-color: #8b5cf6 !important; }    /* بنفش مدرن */

        /* عنوان داخل هر فرم */
        .transaction-section h5 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 30px;
            text-align: center;
            background: linear-gradient(90deg, #1f2937, #374151);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* استایل فرم‌ها — مدرن و شیک */
        .transaction-section .form-group {
            margin-bottom: 24px;
            position: relative;
        }
        .transaction-section label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }
        .transaction-section .form-control,
        .transaction-section .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        .transaction-section .form-control:focus,
        .transaction-section .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            background: white;
            outline: none;
        }

        /* دکمه ثبت — گرادیانت + افکت */
        .transaction-section button[type="submit"] {
            border-radius: 16px !important;
            padding: 16px 40px !important;
            font-size: 1.2rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        /* رنگ دکمه‌ها بر اساس نوع */
        #section-sale button { background: linear-gradient(135deg, #10b981, #059669) !important; }
        #section-cost button { background: linear-gradient(135deg, #ef4444, #dc2626) !important; }
        #section-ad   button { background: linear-gradient(135deg, #8b5cf6, #7c3aed) !important; }

        /* افکت هاور دکمه */
        .transaction-section button:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        .transaction-section button:active {
            transform: translateY(-1px);
        }

        /* انیمیشن ورود کارت */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ریسپانسیو — موبایل هم عالی باشه */
        @media (max-width: 768px) {
            .transaction-section {
                padding: 20px;
                margin: 20px 10px;
                border-radius: 18px;
            }
            .transaction-section h5 { font-size: 1.5rem; }
            .transaction-selector-card { padding: 20px; }
        }

        /* دکمه ثبت — کاملاً یکدست، گرادیانت، متن سفید، افکت براق */
        .transaction-section .submit-btn {
            width: 100%;
            max-width: 380px;
            margin: 30px auto 10px;
            display: block;
            padding: 18px 24px !important;
            font-size: 1.35rem !important;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: white !important;
            border: none !important;
            border-radius: 20px !important;
            box-shadow:
                0 10px 25px rgba(0,0,0,0.3),
                0 5px 10px rgba(0,0,0,0.2);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        /* گرادیانت مخصوص هر نوع */
        .sale-submit {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }
        .cost-submit {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
        }
        .ad-submit {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed) !important;
        }

        /* افکت براق متحرک */
        .submit-btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
            transform: translateX(-100%) rotate(45deg);
            transition: none;
        }
        .submit-btn:hover::before {
            animation: shine 1.4s ease-in-out;
        }

        /* هاور و کلیک */
        .submit-btn:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .submit-btn:active {
            transform: translateY(-2px) scale(0.98);
        }

        /* آیکون کوچیک داخل دکمه (اختیاری ولی خیلی شیک) */
        .submit-btn span {
            position: relative;
            z-index: 2;
        }
        .submit-btn::after {
            content: '✓';
            margin-right: 12px;
            font-size: 1.4rem;
            opacity: 0.9;
        }
    </style>

    <h4 class="card-title">تراکنش ها</h4>
    <!-- هشدار طلب‌های سررسید -->
    @if($showOverdueAlert && $overdueDebts->isNotEmpty())
        <div class="alert alert-danger  fade show" >
            <strong>هشدار!</strong> شما {{ $overdueDebts->count() }} طلب سررسید دارید که هنوز پرداخت نشده‌اند.
            لطفاً در <a href="{{ route('debt.list') }}">بخش طلب‌ها</a> وضعیت پرداخت آن‌ها را ثبت کنید.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif
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
                    <button type="submit" class="submit-btn sale-submit">ثبت</button>
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
                    <button type="submit" class="submit-btn cost-submit">ثبت</button>
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
                    <button type="submit" class="  submit-btn ad-submit" style="background-color: purple;color: white">ثبت</button>
                </div>
            </div>
        </form>

    </div>



    <div class="row " style="margin-top: 100px">
        <div class="col-12">
            <div class="">
                <h5 class="mb-3">لیست تراکنش ها</h5>
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
                        <tr style="background-color: @if($transaction->transaction_type_id==1)#E8F5E9 @endif @if($transaction->transaction_type_id==2)#FFEBEE @endif @if($transaction->transaction_type_id==3)#F3E5F5 @endif">
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
    <div class="report-buttons-wrapper">
        <button id="toggleTableBtn" class="btn btn-primary">
            <div>فروش کل</div>
            <div class="mt-2">{{number_format($sellTransTotalAmount)}} تومان</div>
        </button>
        <button id="costBtn" class="btn btn-danger">
            <div>هزینه کل</div>
            <div class="mt-2">{{number_format($costTransTotalAmount)}} تومان</div>
        </button>
        <button id="adsBtn" class="btn " style="background-color: #E8E8E8">
            <div>سرمایه‌گذاری تبلیغات</div>
            <div class="mt-2">{{number_format($adsTransTotalAmount)}} تومان</div>
        </button>
        <button class="btn " style="background-color: #E8E8E8" >
            <div>موجودی کل نقدی</div>
            <div class="mt-2">{{number_format($wallet)}} تومان</div>
        </button>
        <button id="profitBtn" class="btn " style="background-color: #E8E8E8">
            <div>سود کل</div>
            <div class="mt-2">{{number_format($profitTotalAmount)}} تومان</div>
        </button>
        <button id="debtBtn" class="btn " style="background-color: #E8E8E8">
            <div>در انتظار پرداخت</div>
            <div class="mt-2">{{number_format($totalUnpaid)}} تومان</div>
        </button>
        <button id="taxBtn" class="btn " style="background-color: #E8E8E8">
            <div>مالیات پرداختی</div>
            <div class="mt-2">{{number_format($taxAmount)}} تومان</div>
        </button>
        <button id="comBtn" class="btn " style="background-color: #E8E8E8">
            <div>کمیسیون پرداختی</div>
            <div class="mt-2">{{number_format($commissionAmount)}} تومان</div>
        </button>
        <button id="logBtn" class="btn " style="background-color: #E8E8E8">
            <div>لجستیک پرداختی</div>
            <div class="mt-2">{{number_format($logisticsAmount)}} تومان</div>
        </button>
        <button id="capBtn" class="btn " style="background-color: #E8E8E8">
            <div>سرمایه درگردش</div>
            <div class="mt-2">{{number_format($capitalAmount)}} تومان</div>
        </button>
    </div>
    <div class="row mt-2">

    </div>

    <div id="detailsTable" class="row mt-4" style="display: none;">
        <div class="col-12">
            <div class="details-table-wrapper">
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
            <div class="details-table-wrapper">
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
            <div class="details-table-wrapper">
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
            <div class="details-table-wrapper">
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
            <div class="details-table-wrapper">
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
            <div class="details-table-wrapper">
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
            <div class="details-table-wrapper">
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
            <div class="details-table-wrapper">
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
    <div id="capTable" class="row mt-4" style="display: none;">
        <div class="col-12">
            <div class="details-table-wrapper">
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
                        <td>{{number_format($capCat1) }}  تومان </td>
                        <td>{{number_format($capCat2) }}  تومان </td>
                        <td>{{number_format($capCat3) }}  تومان </td>
                        <td>{{number_format($capCat4) }}  تومان </td>
                        <td>{{number_format($capCat5) }}  تومان </td>
                        <td>{{number_format($capCat6) }}  تومان </td>
                        <td>{{number_format($capCat7) }}  تومان </td>
                        <td>{{number_format($capCat8) }}  تومان </td>
                        <td>{{number_format($capCat9) }}  تومان </td>

                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 chart-card">

        <!-- ۱. روند فروش -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-bold">روند فروش </h3>

            </div>
            <div class="chart-container"><canvas id="salesChart"></canvas></div>
        </div>

        <!-- ۲. مقایسه فروش و سود -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4">مقایسه فروش و سود  </h3>
            <div class="chart-container"><canvas id="salesProfitChart"></canvas></div>
        </div>

        <!-- ۳. بهترین پلتفرم‌ها -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4">بهترین پلتفرم ها</h3>
            <div class="chart-container"><canvas id="platformChart"></canvas></div>
            <div class="platform-performance-table-wrapper">
                <table class="table table-bordered table-striped min-w-full text-sm">
                    <thead class="bg-gray-100"><tr>
                        <th class="px-4 py-2 text-right">پلتفرم</th>
                        <th class="px-4 py-2 text-right">فروش</th>
                        <th class="px-4 py-2 text-right">% فروش</th>
                        <th class="px-4 py-2 text-right">سود</th>
                        <th class="px-4 py-2 text-right">% سود</th>
                    </tr></thead>
                    <tbody>
                    @foreach($platformPerformance['table'] as $row)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $row['name'] }}</td>
                            <td class="px-4 py-2">{{ number_format($row['sales']) }}</td>
                            <td class="px-4 py-2">{{ $row['sales_percent'] }}%</td>
                            <td class="px-4 py-2">{{ number_format($row['profit']) }}</td>
                            <td class="px-4 py-2">{{ $row['profit_percent'] }}%</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>



        <!-- ۵. هزینه‌ها -->

        <div class="bg-white p-6 rounded-lg shadow ">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-bold">ترکیب هزینه‌ها</h3>

            </div>
            <div class="pie-container"><canvas id="expensePieChart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4">روند هزینه‌ها</h3>
            <div class="chart-container"><canvas id="expenseTrendChart"></canvas></div>
        </div>


        <!-- ۶. تبلیغات -->

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-bold">هزینه تبلیغات</h3>

            </div>
            <div class="pie-container"><canvas id="adSpendPieChart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4">روند هزینه تبلیغات</h3>
            <div class="chart-container"><canvas id="adSpendTrendChart"></canvas></div>
        </div>


        <!-- ۷. بازدهی تبلیغات -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4">بازدهی تبلیغات (تبلیغ vs سود)</h3>
            <div class="chart-container"><canvas id="adEfficiencyChart"></canvas></div>
        </div>

        <!-- ۸. نسبت تبلیغ به درآمد -->
        <div class="bg-white p-6 rounded-lg shadow text-center mt-4">
            <h3 class="text-lg font-bold mb-4">درصد هزینه تبلیغ از درآمد</h3>
            <div class="small-pie mx-auto"><canvas id="adToRevenueChart"></canvas></div>
        </div>





    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const charts = {};

            function createChart(id, data, type = 'line') {
                const ctx = document.getElementById(id).getContext('2d');
                if (charts[id]) charts[id].destroy();

                const chartType = type === 'doughnut' ? 'doughnut' :
                    type === 'bar' ? 'bar' :
                        type === 'scatter' ? 'scatter' : 'line';

                // لاگ داده‌ها برای دیباگ
                console.log(`Chart Data for ${id}:`, JSON.parse(JSON.stringify(data)));

                charts[id] = new Chart(ctx, {
                    type: chartType,
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: { size: 12 },
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                enabled: true,
                                mode: chartType === 'scatter' ? 'nearest' : 'index',
                                intersect: chartType === 'scatter' ? true : false,
                                callbacks: {
                                    label: function(context) {
                                        if (chartType === 'scatter') {
                                            const point = context.raw;
                                            return `${point.platform}: ${point.x.toLocaleString()} تومان (${point.adMonth})، فروش: ${point.y.toLocaleString()} تومان (${point.salesMonth})`;
                                        }
                                        return `${context.dataset.label}: ${context.raw.toLocaleString()}`;
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: id === 'adEfficiencyChart' ? 'بازدهی تبلیغات: هزینه در مقابل فروش ماه بعد' : '',
                                font: { size: 16 }
                            }
                        },
                        scales: chartType === 'bar' ? {
                            x: { stacked: false },
                            y: { stacked: false, beginAtZero: true }
                        } : chartType === 'scatter' ? {
                            x: {
                                title: { display: true, text: 'هزینه تبلیغات (تومان)' },
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            },
                            y: {
                                title: { display: true, text: 'فروش ماه بعد (تومان)' },
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        } : chartType === 'line' ? {
                            x: { stacked: false },
                            y: { stacked: false, beginAtZero: true }
                        } : {} // برای doughnut هیچ مقیاسی اعمال نمی‌شود
                    }
                });
            }

            // رندر اولیه نمودارها
            createChart('salesChart', @json($salesData));
            createChart('salesProfitChart', @json($salesProfitData), 'bar');
            createChart('platformChart', @json($platformPerformance['chart']), 'bar');
            createChart('expensePieChart', @json($expenseBreakdown['pie']), 'doughnut');
            createChart('expenseTrendChart', @json($expenseBreakdown['trend']));
            createChart('adSpendPieChart', @json($adSpendByCategory), 'doughnut');
            createChart('adSpendTrendChart', @json($adSpendTrend));
            createChart('adEfficiencyChart', @json($adEfficiency), 'scatter');
            createChart('adToRevenueChart', @json($adToRevenueRatio), 'doughnut');
            createChart('beforeAfterChart', @json($beforeAfterAd), 'bar');
        });
    </script>

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
            // یک آرایه از دکمه‌ها و جدول‌های مرتبط
            const toggleItems = [
                { btnId: 'toggleTableBtn', tableId: 'detailsTable' },
                { btnId: 'costBtn',        tableId: 'costTable' },
                { btnId: 'adsBtn',         tableId: 'adsTable' },
                { btnId: 'profitBtn',      tableId: 'profitTable' },
                { btnId: 'debtBtn',        tableId: 'debtTable' },
                { btnId: 'taxBtn',         tableId: 'taxTable' },
                { btnId: 'comBtn',         tableId: 'comTable' },
                { btnId: 'logBtn',         tableId: 'logTable' },
                { btnId: 'capBtn',         tableId: 'capTable' }
            ];

            toggleItems.forEach(item => {
                const btn = document.getElementById(item.btnId);
                const table = document.getElementById(item.tableId);

                if (!btn || !table) return; // اگر وجود نداشت رد شو

                btn.addEventListener('click', function () {
                    if (table.style.display === 'none' || table.style.display === '') {
                        // نمایش جدول
                        table.style.display = 'block';
                        setTimeout(() => table.classList.add('show'), 10); // انیمیشن
                    } else {
                        // مخفی کردن جدول
                        table.classList.remove('show');
                        setTimeout(() => table.style.display = 'none', 400); // هماهنگ با transition
                    }
                });
            });
        });
    </script>
@endsection
