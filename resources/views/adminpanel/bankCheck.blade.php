@extends('AdminPanel.layout.master')

@section('title')
    چک ها
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
                            <option value="1" > حساب نقدی ها </option>
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
                            <th scope="col"> وضعیت</th>
                            <th scope="col"> توضیحات</th>
                            <th scope="col">تاریخ ثبت</th>
                            <th scope="col"> حذف</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($checks as $check)
                            <tr class="{{ $check->is_paid == 0 ? 'table-danger' : '' }}">
                                <td>{{ $check->id }}</td>
                                <td>{{ number_format($check->check_amount) }} تومان</td>
                                <td>{{ $check->persianDate }}</td>
                                <td>
                                    @if($check->bankAccount_id == 1)
                                        حساب نقدی ها
                                    @else
                                        بانک {{ $check->bankAccount->bank_name }} به نام {{ $check->bankAccount->name }}
                                    @endif
                                </td>
                                <td>
                                    @if($check->is_paid == 1)
                                        <span class="badge bg-success">پرداخت شده</span>
                                    @else
                                        <span class="badge bg-danger">معوق</span>

                                        <!-- دکمه تایید پرداخت فقط برای چک‌های معوق -->
                                        <button type="button" class="btn btn-sm btn-primary mt-1 confirm-payment-btn"
                                                data-id="{{ $check->id }}"
                                                data-amount="{{ number_format($check->check_amount) }}">
                                            <i class="ti-check"></i> تایید پرداخت
                                        </button>
                                    @endif
                                </td>
                                <td>{{ $check->description ?? '-' }}</td>
                                <td>{{ $check->persianCreate }}</td>
                                <td>
                                    <a href="{{ route('bankCheck.delete', ['id' => $check->id]) }}"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('آیا از حذف چک مطمئن هستید؟')">
                                        حذف
                                    </a>
                                </td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.confirm-payment-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const checkId = this.dataset.id;
                    const amount = this.dataset.amount;

                    Swal.fire({
                        title: 'تایید پرداخت چک',
                        html: `<p>آیا از پرداخت چک به مبلغ <strong>${amount} تومان</strong> مطمئن هستید؟</p>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'بله، پرداخت شد',
                        cancelButtonText: 'لغو',
                        reverseButtons: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#dc3545'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch("{{ url('/admin/bankcheck/paid') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ check_id: checkId })
                            })
                                .then(response => {
                                    // این خط خیلی مهمه! اول چک کن response.ok باشه
                                    if (!response.ok) {
                                        throw new Error(`HTTP ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('موفق!', 'چک با موفقیت پرداخت شد', 'success')
                                            .then(() => location.reload());
                                    } else {
                                        Swal.fire('خطا', data.message || 'عملیات ناموفق بود', 'error');
                                    }
                                })
                                .catch(err => {
                                    console.error('Ajax Error:', err);
                                    Swal.fire('خطا', 'ارتباط با سرور برقرار نشد یا خطایی رخ داد.', 'error');
                                });
                        }
                    });
                });
            });
        });
    </script>
@endsection


