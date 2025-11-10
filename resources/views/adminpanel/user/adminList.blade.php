@extends('AdminPanel.layout.master')

@section('title')
    لیست ادمین ها
@endsection

@section('content')

        <div class="table overflow-auto" tabindex="8">
            <div class="form-group row">

            </div>
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                <tr>
                    <th class="text-center align-middle text-primary">ایدی</th>
                    <th class="text-center align-middle text-primary">نام</th>

                    <th class="text-center align-middle text-primary">نام کاربری</th>


                    <th class="text-center align-middle text-primary">تاریخ ایجاد</th>
                    <th class="text-center align-middle text-primary">وضعیت</th>
                    <th class="text-center align-middle text-primary">ویرایش</th>
                    <th class="text-center align-middle text-primary">عملیات</th>

                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="text-center align-middle">{{$user->id}}</td>


                        <td class="text-center align-middle">{{$user->name}}</td>
                        <td class="text-center align-middle">{{$user->username}}</td>

                        <td class="text-center align-middle">{{$user->created_at}}</td>
                        @if(($user->deleted_at)!=null)
                            <td class="text-center align-middle text-danger">
                                غیرفعال
                            </td>
                        @endif
                        @if(($user->deleted_at)==null)
                            <td class="text-center align-middle text-success">
                                فعال
                            </td>
                        @endif
                        <td class="text-center align-middle">
                            @if(($user->deleted_at)==null) <a class="btn btn-outline-info" href="{{route('EditAdmin',[$user->id])}}">
                                ویرایش
                            </a> @endif

                        </td>
                        <td class="text-center align-middle">
                            @if(($user->deleted_at)==null)
                                <form method="post" action="{{route('deleteA',[$user->id])}}">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" >
                                    غیرفعال
                                </button>
                            </form>
                            @endif
                            @if(($user->deleted_at)!=null)
                                    <form method="post" action="{{route('restoreA',[$user->id])}}">

                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" >
                                            بازگردانی
                                        </button>
                                    </form>
                                @endif


                        </td>

                    </tr>
                @endforeach


            </table>
            <div style="margin: 40px !important;"
                 class="pagination pagination-rounded pagination-sm d-flex justify-content-center">
            </div>
        </div>

@endsection
