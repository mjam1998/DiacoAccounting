@extends('AdminPanel.layout.master')

@section('title')
    طلب های قسطی
@endsection

@section('content')
    <style>
        .table-container {
            overflow-x: auto;
        }

        .datatable {
            font-size: 0.85rem !important;
            white-space: normal;
            width: 100% !important;
            border-collapse: collapse;
        }

        .datatable th,
        .datatable td {
            padding: 0.5rem 0.6rem !important;
            text-align: center !important;
            vertical-align: middle !important;
            min-width: 80px;
        }

        .datatable thead th {
            background-color: #000 !important;
            color: #fff !important;
            font-weight: 700;
            font-size: 0.8rem;
            border: none !important;
        }

        .badge {
            padding: 0.25em 0.5em;
            font-size: 0.7rem;
            border-radius: 0.25rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .badge-paid {
            background-color: #28a745;
            color: white;
        }

        .badge-unpaid {
            background-color: #dc3545;
            color: white;
        }

        .btn-pay {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        .dataTables_empty {
            font-size: 0.9rem;
            color: #6c757d;
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }

        @media (max-width: 768px) {
            .datatable {
                font-size: 0.75rem !important;
            }

            .datatable th,
            .datatable td {
                padding: 0.4rem 0.3rem !important;
                min-width: 60px;
            }
        }
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
            <div class="table-responsive">
                <div class="table-container">
                    <h5 class="text-warning">لیست طلب‌های سررسید (حداقل یک قسط معوق با سررسید گذشته یا امروز)</h5>
                    <table id="overdueDebtsTable" class=" table table-striped table-bordered table-hover">
                        <thead>
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

    <!-- جدول طلب‌های پرداخت نشده -->
    <div class="row" style="margin-top: 40px">
        <div class="col-12">
            <div class="table-responsive">
                <div class="table-container">
                    <h5 class="text-danger">لیست طلب‌های پرداخت نشده (حداقل یک قسط پرداخت نشده)</h5>
                    <table id="unpaidDebtsTable" class="datatable table table-striped table-bordered table-hover">
                        <thead>
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
                        @forelse($unpaidDebts as $debt)
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
                                <td colspan="14" class="text-center text-muted">هیچ طلب پرداخت نشده‌ای وجود ندارد.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول طلب‌های پرداخت شده -->
    <div class="row" style="margin-top: 100px">
        <div class="col-12">
            <div class="table-responsive">
                <div class="table-container">
                    <h5 class="text-success">لیست طلب‌های پرداخت شده (همه قسط‌ها پرداخت شده)</h5>
                    <table id="paidDebtsTable" class="datatable table table-striped table-bordered table-hover">
                        <thead>
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
                        @forelse($paidDebts as $debt)
                            <tr>
                                <td>{{ $debt->id }}</td>
                                <td>{{ Str::limit($debt->category->name ?? 'نامشخص', 12) }}</td>
                                <td>{{ number_format($debt->debt1) }}</td>
                                <td>{{ $debt->debt1_time ? jdate($debt->debt1_time)->format('y/m/d') : '-' }}</td>
                                <td><span class="badge badge-paid">پرداخت</span></td>
                                <td>{{ $debt->debt2 ? number_format($debt->debt2) : '-' }}</td>
                                <td>{{ $debt->debt2_time ? jdate($debt->debt2_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt2)
                                        <span class="badge badge-paid">پرداخت</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $debt->debt3 ? number_format($debt->debt3) : '-' }}</td>
                                <td>{{ $debt->debt3_time ? jdate($debt->debt3_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt3)
                                        <span class="badge badge-paid">پرداخت</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $debt->debt4 ? number_format($debt->debt4) : '-' }}</td>
                                <td>{{ $debt->debt4_time ? jdate($debt->debt4_time)->format('y/m/d') : '-' }}</td>
                                <td>
                                    @if($debt->debt4)
                                        <span class="badge badge-paid">پرداخت</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted">هیچ طلب تسویه شده‌ای وجود ندارد.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
