@extends('admin.master')


@section('content')

<div class="content-page">

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

        <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0 IR">
                            {{ Breadcrumbs::render('found') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-cash-register page-icon"></i>
                             صندوق
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="IR">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                                <div class="row">
                                    <div class="form-group justify-content-center col-12 col-md-4">
                                        <form>
                                        <div class="form-group justify-content-center col-12">
                                            <label for="mobile-filter" class="control-label IRANYekanRegular">موبایل</label>
                                            <input type="text"  class="form-control input text-right" id="mobile-filter" name="mobile" placeholder="موبایل را وارد کنید" value="{{ request('mobile') }}">
                                        </div>

                                        <div class="form-group justify-content-center col-12">
                                            <label for="nation_code-filter" class="control-label IRANYekanRegular">شماره ملی</label>
                                            <input type="text"  class="form-control input text-right" id="nation_code-filter" name="nation_code" placeholder=" شماره ملی  را وارد کنید" value="{{ request('nation_code') }}">
                                        </div>


                                        <div class="form-group justify-content-center col-12">
                                            <label for="code-filter" class="control-label IRANYekanRegular">کد مراجعه</label>
                                            <input type="text"  class="form-control input text-right" id="code-filter" name="code" placeholder=" کد مراجعه  را وارد کنید" value="{{ request('code') }}">
                                        </div>


                                        <div class="form-group justify-content-center col-12">
                                            <button type="submit" class="btn btn-info  cursor-pointer">
                                                <i class="fa fa-search fa-sm"></i>
                                                <span class="pr-2">جستجو</span>
                                            </button>
                                            <a onclick="reset()" class="btn btn-light border border-secondary cursor-pointer">
                                                <i class="fas fa-undo fa-sm"></i>
                                                <span class="pr-2">پاک کردن</span>
                                            </a>
                                        </div>

                                        <script>
                                            function reset()
                                            {
                                                document.getElementById("mobile-filter").value = "";
                                                document.getElementById("nation_code-filter").value = "";
                                                document.getElementById("code-filter").value = "";
                                            }
                                        </script>
                                        </form>
                                    </div>
                                    <div class="form-group justify-content-center col-12 col-md-8">

                                        <div class="table-responsive">
                                            <table id="tech-companies-1" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th><b class="IRANYekanRegular">ردیف</b></th>
                                                    <th><b class="IRANYekanRegular">نام نام خانوادگی</b></th>
                                                    <th><b class="IRANYekanRegular">کدملی</b></th>
                                                    <th><b class="IRANYekanRegular">شماره موبایل</b></th>
                                                    <th><b class="IRANYekanRegular">کدمراجعه</b></th>
                                                    <th><b class="IRANYekanRegular">مسئول پذیرش</b></th>
                                                    <th><b class="IRANYekanRegular">شعبه</b></th>
                                                    <th><b class="IRANYekanRegular">وضعیت مراجعه</b></th>
                                                    <th><b class="IRANYekanRegular">وضعیت صندوق</b></th>
                                                    <th style="width:200px;"><b class="IRANYekanRegular">اقدامات</b></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($receptions as $index=>$reception)
                                                    <tr>
                                                        <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">
                                                                @if($reception->user)
                                                                    {{ $reception->user->firstname.' '.$reception->user->lastname }}
                                                                @endif
                                                            </strong>
                                                        </td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->user->nationcode }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->user->mobile }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->code }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->reception->fullname }}</strong></td>
                                                        <td><strong class="IRANYekanRegular">{{ $reception->branch->name }}</strong></td>
                                                        <td>
                                                            @if($reception->end)
                                                                <strong class="IRANYekanRegular">پایان مراجعه</strong>
                                                            @else
                                                                <strong class="IRANYekanRegular">باز</strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <strong class="IRANYekanRegular">
                                                                @if($reception->found_status ==   App\Enums\FoundStatus::pending)
                                                                    <span class="badge badge-warning IR p-1">بلاتکلیف</span>
                                                                @elseif($reception->found_status == App\Enums\FoundStatus::referred)
                                                                    <span class="badge badge-success IR p-1">ارجاع به صندوق</span>
                                                                @elseif($reception->found_status == App\Enums\FoundStatus::unobstructed)
                                                                    <span class="badge badge-primary IR p-1">بلامانع</span>
                                                                @elseif($reception->found_status == App\Enums\FoundStatus::unpaid)
                                                                    <span class="badge badge-danger IR p-1">عدم پرداخت</span>
                                                                @endif
                                                            </strong>
                                                        </td>

                                                        <td>

                                                            <!-- found Modal -->
                                                            <div class="modal fade" id="found{{ $reception->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xs">
                                                                    <div class="modal-content text-left">
                                                                        <div class="modal-header py-3">
                                                                            <h5 class="modal-title IR" id="newReviewLabel">تعیین وضعیت</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="{{ route('admin.accounting.reception.found.status',$reception) }}" method="POST" class="d-inline" id="status-form-{{ $reception->id }}">
                                                                                @csrf
                                                                                @method('patch')
                                                                                <label for="status{{ $reception->id }}" class="col-form-label IRANYekanRegular text-right">وضعیت</label>
                                                                                <select name="status" id="status{{ $reception->id }}" class="form-control dropdown IR" required>
                                                                                    <option value="">وضعیت صندوق را مشخص کنید.</option>
                                                                                    <option value="{{ App\Enums\FoundStatus::unobstructed }}" {{ App\Enums\FoundStatus::unobstructed==$reception->found_status?'selected':'' }}>بلامانع</option>
                                                                                    <option value="{{ App\Enums\FoundStatus::unpaid }}" {{ App\Enums\FoundStatus::unpaid==$reception->found_status?'selected':'' }}>عدم پرداخت</option>
                                                                                </select>
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit"  title="ثبت" class="btn btn-primary px-8" form="status-form-{{ $reception->id }}">ثبت</button>
                                                                            &nbsp;
                                                                            <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                                    <div class="dropdown-menu">
                                                                        @if(Auth::guard('admin')->user()->can('reception.invoices.show'))
                                                                            <a href="{{ route('admin.accounting.reception.invoices.show',$reception) }}" class="dropdown-item IR cursor-pointer" title="صورتحساب">
                                                                                <i class="fas fa-dollar-sign text-primary cursor-pointer"></i>
                                                                                <span class="p-1">صورتحساب پرداخت</span>
                                                                            </a>
                                                                        @endif

{{--                                                                            @if($reception->found_status ==   App\Enums\FoundStatus::pending)--}}
                                                                            <a class="dropdown-item IR cursor-pointer" href="#found{{ $reception->id }}" data-toggle="modal" title="تعیین وضعیت">
                                                                                <i class="fas fa-cash-register text-dark cursor-pointe"></i>
                                                                                <span class="p-1">تعیین وضعیت</span>
                                                                            </a>
{{--                                                                            @endif--}}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
