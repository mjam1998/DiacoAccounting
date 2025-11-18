@extends('adminpanel.layout.master')

@section('title')
    طلب های قسطی
@endsection

@section('content')
   <style>
       /* کاملاً حذف کردیم .table-responsive رو که اسکرول رو می‌خورد */
       .listjs-table-wrapper {
           overflow-x: auto !important;
           -webkit-overflow-scrolling: touch;
           margin: 20px 0;
           border-radius: 8px;
           box-shadow: 0 4px 15px rgba(0,0,0,0.1);
           background: white;
       }

       .listjs-table {
           min-width: 1300px !important;   /* بزرگتر کردم که ۱۰۰٪ اسکرول بده */
           width: 100%;
           white-space: nowrap;
           border-collapse: separate;
           border-spacing: 0;
       }

       .listjs-table th {
           background: #000 !important;
           color: white !important;
           position: sticky;
           top: 0;
           z-index: 10;
           text-align: center;
           padding: 12px 8px !important;
           font-weight: bold;
       }

       .listjs-table td {
           padding: 10px 8px !important;
           vertical-align: middle;
       }

       /* صفحه‌بندی حرفه‌ای و همیشه نمایش داده بشه */
       .listjs-pagination {
           display: flex !important;
           justify-content: center;
           flex-wrap: wrap;
           gap: 8px;
           margin: 30px 0;
           padding: 0;
           list-style: none;
       }

       .listjs-pagination li {
           margin: 0;
       }

       .listjs-pagination a,
       .listjs-pagination span {
           display: block;
           padding: 10px 16px;
           border-radius: 8px;
           background: #f8f9fa;
           color: #333;
           text-decoration: none;
           border: 1px solid #dee2e6;
           min-width: 44px;
           text-align: center;
           font-weight: 500;
           transition: all 0.2s;
       }

       .listjs-pagination .active a,
       .listjs-pagination .active span {
           background: #007bff;
           color: white;
           border-color: #007bff;
       }

       .listjs-pagination a:hover:not(.active) {
           background: #e9ecef;
       }
   </style>
   </style>

    <h4 class="card-title">طلب های قسطی</h4>

    <!-- پیام‌های موفقیت/خطا -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif

    <!-- جدول طلب‌های سررسید -->
   <div class="row" style="margin-top: 40px">
       <div class="col-12">
           <h5 class="text-warning mb-4">لیست طلب‌های سررسید (حداقل یک قسط معوق با سررسید گذشته یا امروز)</h5>

           <!-- List.js Wrapper -->
           <div id=" overdueDebtsTable">
               <!-- کادر جستجو -->
               <div class="mb-4">
                   <input type="text" class="form-control search w-100" placeholder="جستجو در آیدی، دسته، مبلغ..." />
               </div>

               <!-- جدول با اسکرول افقی در موبایل -->
               <div class=" listjs-table-wrapper">
                   <table class="listjs-table table table-striped table-bordered table-hover">
                       <thead style="background:#000;color:white">
                        <tr>
                            <th>آیدی</th>
                            <th>دسته</th>
                            <th>قسط ۱</th>
                            <th>سررسید</th>
                            <th>وضعیت</th>
                            <th>قسط ۲</th>
                            <th>سررسید</th>
                            <th>وضعیت</th>
                            <th>قسط ۳</th>
                            <th>سررسید</th>
                            <th>وضعیت</th>
                            <th>قسط ۴</th>
                            <th>سررسید</th>
                            <th>وضعیت</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($overdueDebts as $debt)
                            <tr>
                                <td>{{ $debt->id }}</td>
                                <td>{{ Str::limit($debt->category->name ?? 'نامشخص', 12) }}</td>
                                <td>{{ number_format($debt->debt1) }}</td>
                                <td>{{ $debt->debt1_time ? jdate($debt->debt1_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt1_isPaid)
                                        <span class="badge badge-paid">پرداخت</span>
                                    @else
                                        <span class="badge badge-unpaid">معوق</span>
                                        <form action="{{ route('admin.debt.pay', $debt->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="installment" value="1">
                                            <button type="submit" class="btn btn-primary btn-pay btn-sm"
                                                    onclick="return confirm('آیا قسط اول پرداخت شده است؟')">
                                                تایید
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>{{ $debt->debt2 ? number_format($debt->debt2) : '-' }}</td>
                                <td>{{ $debt->debt2_time ? jdate($debt->debt2_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt2)
                                        @if($debt->debt2_isPaid)
                                            <span class="badge badge-paid">پرداخت</span>
                                        @else
                                            <span class="badge badge-unpaid">معوق</span>
                                            <form action="{{ route('admin.debt.pay', $debt->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="installment" value="2">
                                                <button type="submit" class="btn btn-primary btn-pay btn-sm"
                                                        onclick="return confirm('آیا قسط دوم پرداخت شده است؟')">
                                                    تایید
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $debt->debt3 ? number_format($debt->debt3) : '-' }}</td>
                                <td>{{ $debt->debt3_time ? jdate($debt->debt3_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt3)
                                        @if($debt->debt3_isPaid)
                                            <span class="badge badge-paid">پرداخت</span>
                                        @else
                                            <span class="badge badge-unpaid">معوق</span>
                                            <form action="{{ route('admin.debt.pay', $debt->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="installment" value="3">
                                                <button type="submit" class="btn btn-primary btn-pay btn-sm"
                                                        onclick="return confirm('آیا قسط سوم پرداخت شده است؟')">
                                                    تایید
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $debt->debt4 ? number_format($debt->debt4) : '-' }}</td>
                                <td>{{ $debt->debt4_time ? jdate($debt->debt4_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt4)
                                        @if($debt->debt4_isPaid)
                                            <span class="badge badge-paid">پرداخت</span>
                                        @else
                                            <span class="badge badge-unpaid">معوق</span>
                                            <form action="{{ route('admin.debt.pay', $debt->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="installment" value="4">
                                                <button type="submit" class="btn btn-primary btn-pay btn-sm"
                                                        onclick="return confirm('آیا قسط چهارم پرداخت شده است؟')">
                                                    تایید
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted">هیچ طلب سررسیدی وجود ندارد.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== جدول طلب‌های پرداخت نشده (با List.js) ====== -->
    <div class="row" style="margin-top: 40px">
        <div class="col-12">
            <h5 class="text-danger mb-4">لیست طلب‌های پرداخت نشده (حداقل یک قسط پرداخت نشده)</h5>

            <!-- List.js Wrapper -->
            <div id="unpaid-list">
                <!-- کادر جستجو -->
                <div class="mb-4">
                    <input type="text" class="form-control search w-100" placeholder="جستجو در آیدی، دسته، مبلغ..." />
                </div>

                <!-- جدول با اسکرول افقی در موبایل -->
                <div class=" listjs-table-wrapper">
                    <table class="listjs-table table table-striped table-bordered table-hover">
                        <thead style="background:#000;color:white">
                        <tr>
                            <th class="sort" data-sort="id">آیدی</th>
                            <th class="sort" data-sort="category">دسته</th>
                            <th class="sort" data-sort="debt1">قسط ۱</th>
                            <th>سررسید ۱</th>
                            <th>وضعیت ۱</th>
                            <th>قسط ۲</th>
                            <th>سررسید ۲</th>
                            <th>وضعیت ۲</th>
                            <th>قسط ۳</th>
                            <th>سررسید ۳</th>
                            <th>وضعیت ۳</th>
                            <th>قسط ۴</th>
                            <th>سررسید ۴</th>
                            <th>وضعیت ۴</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($unpaidDebts as $debt)
                            <tr>
                                <td class="id">{{ $debt->id }}</td>
                                <td class="category">{{ Str::limit($debt->category->name ?? 'نامشخص', 12) }}</td>
                                <td class="debt1">{{ number_format($debt->debt1) }}</td>
                                <td>{{ $debt->debt1_time ? jdate($debt->debt1_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt1_isPaid)
                                        <span class="badge badge-paid">پرداخت</span>
                                    @else
                                        <span class="badge badge-unpaid">معوق</span>
                                        <form action="{{ route('admin.debt.pay', $debt->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="installment" value="1">
                                            <button type="submit" class="btn btn-primary btn-pay btn-sm mt-1"
                                                    onclick="return confirm('آیا قسط اول پرداخت شده است؟')">
                                                تایید
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>{{ $debt->debt2 ? number_format($debt->debt2) : '-' }}</td>
                                <td>{{ $debt->debt2_time ? jdate($debt->debt2_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt2)
                                        @if($debt->debt2_isPaid)
                                            <span class="badge badge-paid">پرداخت</span>
                                        @else
                                            <span class="badge badge-unpaid">معوق</span>
                                            <form action="{{ route('admin.debt.pay', $debt->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="installment" value="2">
                                                <button type="submit" class="btn btn-primary btn-pay btn-sm mt-1"
                                                        onclick="return confirm('آیا قسط دوم پرداخت شده است؟')">
                                                    تایید
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $debt->debt3 ? number_format($debt->debt3) : '-' }}</td>
                                <td>{{ $debt->debt3_time ? jdate($debt->debt3_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt3)
                                        @if($debt->debt3_isPaid)
                                            <span class="badge badge-paid">پرداخت</span>
                                        @else
                                            <span class="badge badge-unpaid">معوق</span>
                                            <form action="{{ route('admin.debt.pay', $debt->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="installment" value="3">
                                                <button type="submit" class="btn btn-primary btn-pay btn-sm mt-1"
                                                        onclick="return confirm('آیا قسط سوم پرداخت شده است؟')">
                                                    تایید
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $debt->debt4 ? number_format($debt->debt4) : '-' }}</td>
                                <td>{{ $debt->debt4_time ? jdate($debt->debt4_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt4)
                                        @if($debt->debt4_isPaid)
                                            <span class="badge badge-paid">پرداخت</span>
                                        @else
                                            <span class="badge badge-unpaid">معوق</span>
                                            <form action="{{ route('admin.debt.pay', $debt->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="installment" value="4">
                                                <button type="submit" class="btn btn-primary btn-pay btn-sm mt-1"
                                                        onclick="return confirm('آیا قسط چهارم پرداخت شده است؟')">
                                                    تایید
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted py-4">هیچ طلب پرداخت نشده‌ای وجود ندارد.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- صفحه‌بندی زیبا -->
                <div class="text-center mt-4">
                    <ul class="pagination listjs-pagination"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== جدول طلب‌های پرداخت شده (با List.js) ====== -->
    <div class="row" style="margin-top: 100px">
        <div class="col-12">
            <h5 class="text-success mb-4">لیست طلب‌های پرداخت شده (همه قسط‌ها پرداخت شده)</h5>

            <div id="paid-list">
                <div class="mb-4">
                    <input type="text" class="form-control search w-100" placeholder="جستجو در آیدی، دسته، مبلغ..." />
                </div>

                <div class=" listjs-table-wrapper">
                    <table class="listjs-table table table-striped table-bordered table-hover">
                        <thead style="background:#000;color:white">
                        <tr>
                            <th class="sort" data-sort="id">آیدی</th>
                            <th class="sort" data-sort="category">دسته</th>
                            <th class="sort" data-sort="debt1">قسط ۱</th>
                            <th>سررسید ۱</th>
                            <th>وضعیت</th>
                            <th>قسط ۲</th>
                            <th>سررسید ۲</th>
                            <th>وضعیت</th>
                            <th>قسط ۳</th>
                            <th>سررسید ۳</th>
                            <th>وضعیت</th>
                            <th>قسط ۴</th>
                            <th>سررسید ۴</th>
                            <th>وضعیت</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($paidDebts as $debt)
                            <tr>
                                <td class="id">{{ $debt->id }}</td>
                                <td class="category">{{ Str::limit($debt->category->name ?? 'نامشخص', 12) }}</td>
                                <td class="debt1">{{ number_format($debt->debt1) }}</td>
                                <td>{{ $debt->debt1_time ? jdate($debt->debt1_time)->format('y/m/d') : '-' }}</td>
                                <td><span class="badge badge-paid">پرداخت</span></td>
                                <td>{{ $debt->debt2 ? number_format($debt->debt2) : '-' }}</td>
                                <td>{{ $debt->debt2_time ? jdate($debt->debt2_time)->format('y/m/d') : '-' }}</td>
                                <td>@if($debt->debt2)<span class="badge badge-paid">پرداخت</span>@else — @endif</td>
                                <td>{{ $debt->debt3 ? number_format($debt->debt3) : '-' }}</td>
                                <td>{{ $debt->debt3_time ? jdate($debt->debt3_time)->format('y/m/d') : '-' }}</td>
                                <td>@if($debt->debt3)<span class="badge badge-paid">پرداخت</span>@else — @endif</td>
                                <td>{{ $debt->debt4 ? number_format($debt->debt4) : '-' }}</td>
                                <td>{{ $debt->debt4_time ? jdate($debt->debt4_time)->format('y/m/d') : '-' }}</td>
                                <td>@if($debt->debt4)<span class="badge badge-paid">پرداخت</span>@else — @endif</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted py-4">هیچ طلب تسویه شده‌ای وجود ندارد.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <ul class="pagination listjs-pagination"></ul>
                </div>
            </div>
        </div>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // جدول پرداخت نشده
            new List('unpaid-list', {
                valueNames: ['id', 'category', 'debt1'],
                page: 10,                    // کمتر کردم که زودتر pagination نشون بده
                pagination: {
                    innerWindow: 2,
                    outerWindow: 1,
                    left: 1,
                    right: 1
                }
            });

            // جدول پرداخت شده
            new List('paid-list', {
                valueNames: ['id', 'category', 'debt1'],
                page: 10,
                pagination: {
                    innerWindow: 2,
                    outerWindow: 1
                }
            });
            new List(' overdueDebtsTable', {
                valueNames: ['id', 'category', 'debt1'],
                page: 10,
                pagination: {
                    innerWindow: 2,
                    outerWindow: 1
                }
            });


            // اجبار به نمایش pagination حتی اگه یه صفحه باشه
            setTimeout(function() {
                document.querySelectorAll('.listjs-pagination').forEach(function(pagination) {
                    if (pagination.children.length === 0) {
                        pagination.innerHTML = '<li class="active"><a href="#">1</a></li>';
                    }
                    pagination.style.display = 'flex';
                });
            }, 300);
        });
    </script>
@endsection
