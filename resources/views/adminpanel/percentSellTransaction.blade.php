@extends('AdminPanel.layout.master')

@section('title')
    درصد های دسته بندی تراکنش فروش
@endsection

@section('content')
    <div class="container">
        <h4 class="card-title">درصد های دسته بندی تراکنش فروش</h4>
        @if(session()->has('submitPercent'))
            <p class="alert alert-success">{{ session('submitPercent') }}</p>
        @endif

        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">دسته بندی</th>
                            <th scope="col">کمیسیون(درصد)</th>
                            <th scope="col">مالیات(درصد)</th>
                            <th scope="col">لجستیک(تومان)</th>
                            <th scope="col">تغییر</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <form method="post" action="{{ route('percentTransactionCategory.submit') }}">
                                    @csrf
                                    <input type="hidden" value="{{ $category->id }}" name="id">
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <input type="text" value="{{ $category->commission }}" name="commission" placeholder="درصد" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $category->tax }}" name="tax" placeholder="درصد" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text"
                                               value="{{ $category->logistics ? number_format($category->logistics) : '' }}"
                                               class="form-control money-display"
                                               placeholder="مقدار به تومان">
                                        <input type="hidden" value="{{ $category->logistics ?: '' }}" name="logistics" class="money-value">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success">ذخیره</button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moneyDisplays = document.querySelectorAll('.money-display');

            moneyDisplays.forEach(function(moneyDisplay) {
                const moneyValue = moneyDisplay.closest('td').querySelector('.money-value');

                moneyDisplay.addEventListener('input', function(e) {
                    // حذف کاراکترهای غیر عددی
                    let value = this.value.replace(/[^\d]/g, '');

                    // ذخیره مقدار عددی در فیلد مخفی
                    moneyValue.value = value;

                    // نمایش فرمت شده در فیلد نمایشی
                    if (value) {
                        this.value = formatNumber(value);
                    } else {
                        this.value = ''; // اگر خالی شد، مقدار را پاک کن
                    }
                });

                // مقدار اولیه را فرمت کن (فقط اگر مقدار داشت)
                if (moneyDisplay.value && moneyDisplay.value.trim() !== '') {
                    let value = moneyDisplay.value.replace(/[^\d]/g, '');
                    if (value) {
                        moneyDisplay.value = formatNumber(value);
                    }
                }
            });

            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }
        });
    </script>
@endsection
