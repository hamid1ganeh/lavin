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
                            {{ Breadcrumbs::render('receptions') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="ti-pencil-alt page-icon"></i>
                             پذیرش
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

                            <div class="row" style="margin-bottom: 20px;">
                                <div class="col-12 text-right">
                                    @if(Auth::guard('admin')->user()->can('reception.medical.document.create'))

                                        <a href="#create" data-toggle="modal"   title="ایجاد پرونده پرسنلی جدید" class="btn btn-sm btn-primary font18 m-1">
                                            <b class="IRANYekanRegular">پرونده پزشکی جدید</b>
                                            <i class="fa fa-plus plusiconfont"></i>
                                        </a>

                                        <!-- Create Modal -->
                                        <div class="modal fade" id="create" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد پرونده پزشکی جدید</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.receptions.store') }}"  method="POST" class="d-inline" id="create-fom">
                                                          @csrf
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label for="firstname" class="col-md-12 col-form-label text-md-left IRANYekanRegular" max="100">نام:</label>
                                                                    <input type="text" class="form-control w-100 ltr" name="firstname"  required maxlength="255" id="firstname"  placeholder="نام" value="{{ old('firstname') }}" >
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="lastname" class="col-md-12 col-form-label text-md-left IRANYekanRegular" max="100">نام خانوادگی:</label>
                                                                    <input type="text" class="form-control w-100 ltr" name="lastname"   required maxlength="255" id="lastname"  placeholder="نام خانوادگی" value="{{ old('lastname') }}" >
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label for="mobile" class="col-md-12 col-form-label text-md-left IRANYekanRegular" max="100">شماره موبایل:</label>
                                                                    <input type="text" class="form-control w-100 ltr" name="mobile" minlenght="11" required maxlength="11" id="mobile"  placeholder="شماره موبایل" value="{{ old('mobile') }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="nationcode" class="col-md-12 col-form-label text-md-left IRANYekanRegular" max="100">شماره ملی:</label>
                                                                    <input type="text" class="form-control w-100 ltr" name="nationcode" minlenght="10" required maxlength="10" id="nationcode"  placeholder="شماره ملی" value="{{ old('nationcode') }}">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label for="gender" class="col-md-12 col-form-label text-md-left IRANYekanRegular">جنسیت:</label>
                                                                    <select name="gender" class="form-control  IRANYekanRegular" id="gender">
                                                                        <option value="{{ App\Enums\genderType::male }}"  {{  App\Enums\genderType::male==old('stattus')?'selected':'' }}>مرد</option>
                                                                        <option value="{{ App\Enums\genderType::female }}"  {{  App\Enums\genderType::female==old('status')?'selected':'' }}>زن</option>
                                                                        <option value="{{ App\Enums\genderType::LGBTQ }}"  {{  App\Enums\genderType::LGBTQ==old('status')?'selected':'' }}>LGBTQ</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" title="ثبت" class="btn btn-primary px-8" form="create-fom">ثبت</button>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

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
                                                        <td>
                                                            @if($reception->end)
                                                                @if(is_null($reception->hasOpenReferCode()) &&  Auth::guard('admin')->user()->can('reception.start'))
                                                                <a class="dropdown-item IR cusrsor" href="#start{{ $reception->id }}" data-toggle="modal"  title="باز کردن مراجعه" style="display: contents">
                                                                    <i class="ti-arrow-circle-right text-warning"></i>
                                                                </a>
                                                                @endif
                                                                <strong class="IRANYekanRegular">پایان مراجعه</strong>
                                                                <div class="modal fade" id="start{{ $reception->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xs">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header py-3">
                                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">پایان مراجعه</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این مراجعه را مجداد باز کنید؟</h5>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <form action="{{ route('admin.receptions.start',$reception) }}"  method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('PATCH')
                                                                                    <button type="submit" title="پایان" class="btn btn-info px-8">باز</button>
                                                                                </form>
                                                                                &nbsp;
                                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                @if(Auth::guard('admin')->user()->can('reception.end'))
                                                                <a class="dropdown-item IR cusrsor" href="#end{{ $reception->id }}" data-toggle="modal"  title="پایان مراجعه" style="display: contents">
                                                                    <i class="mdi mdi-logout text-dark"></i>
                                                                </a>
                                                                @endif
                                                                <strong class="IRANYekanRegular">باز</strong>

                                                                <div class="modal fade" id="end{{ $reception->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xs">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header py-3">
                                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">پایان مراجعه</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که این مراجعه پایان یافته است؟</h5>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <form action="{{ route('admin.receptions.end',$reception) }}"  method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('PATCH')
                                                                                    <button type="submit" title="پایان" class="btn btn-info px-8">پایان</button>
                                                                                </form>
                                                                                &nbsp;
                                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
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
                                                            <a href="{{ route('admin.reserves.index',['code'=>$reception->code]) }}"  target="_blank" class="btn btn-icon" title="نمایش روزرها">
                                                                <i class="fa fa-eye text-success font-16"></i>
                                                            </a>

                                                            @if($reception->found_status ==   App\Enums\FoundStatus::pending)
                                                            <a class="font18 m-1" href="#found{{ $reception->id }}" data-toggle="modal" title="ارجاع به صندوق">
                                                                <i class="fas fa-cash-register text-dark font-16"></i>
                                                            </a>

                                                            <!-- found Modal -->
                                                            <div class="modal fade" id="found{{ $reception->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xs">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header py-3">
                                                                            <h5 class="modal-title IR" id="newReviewLabel">ارجاع به صندوق</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <h5 class="IRANYekanRegular"> آیا مطمئن هستید که میخواهید این پذیرش را به صندوق ارجاع دهید؟</h5>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <form action="{{ route('admin.receptions.found',$reception) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('patch')
                                                                                <button type="submit"  title="بازیابی" class="btn btn-primary px-8">ارجاع</button>
                                                                            </form>
                                                                            <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif

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
