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
                                {{ Breadcrumbs::render('users.create') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fa fa-user page-icon"></i>
                            افزودن کاربر جدید
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">

                            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group row mt-3">
                                    <div class="col-12 col-md-6">
                                        <label for="firstname" class="IRANYekanRegular">نام:</label>
                                        <input id="firstname" type="text" class="form-control  @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}"  autofocus placeholder="نام">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('firstname') }} </span>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="lastname" class="IRANYekanRegular">نام خانوادگی:</label>
                                        <input id="lastname" type="text" class="form-control  @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}"  autofocus placeholder="نام خانودگی">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('lastname') }} </span>
                                    </div>

                                </div>


                                <div class="form-group row">
                                    <div class="col-12 col-md-6">
                                        <label for="mobile" class="IRANYekanRegular">موبایل:</label>
                                        <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror ltr" name="mobile"  value="{{ old('mobile') }}" placeholder="موبایل">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('mobile') }} </span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="introduced" class="IRANYekanRegular">کد ملی:</label>
                                        <input id="introduced" type="text" class="form-control @error('nationcode') is-invalid @enderror ltr"  name="nationcode" value="{{ old('nationcode') }}"   placeholder="کد ملی">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('nationcode') }} </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-6">
                                        <label for="introduced" class="IRANYekanRegular">کد معرف:</label>
                                        <input id="introduced" type="text" class="form-control @error('introduced') is-invalid @enderror ltr"  name="introduced" value="{{ old('introduced') ?? request('introduced') ?? '' }}"   placeholder="کد معرف">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('introduced') }} </span>
                                    </div>

                                    <div class="col-12 col-md-2 mt-3">
                                        <label for="gender" class="IRANYekanRegular">جنسیت:</label>
                                        <select name="gender" id="gender" class="dropdown text-center IRANYekanRegular"  data-placeholder="انتخاب جنسیت...">
                                            <option value="{{ App\Enums\genderType::female }}">زن</option>
                                            <option value="{{ App\Enums\genderType::male }}">مرد</option>
                                            <option value="{{ App\Enums\genderType::LGBTQ }}">LGBTQ</option>
                                        </select>
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('gender') }} </span>
                                    </div>

                                    <div class="col-12 col-md-2 mt-3">
                                        <input class="form-check-input cursor-pointer" type="checkbox" name="seller" value="seller" id="seller">
                                        <label class="form-check-label IR" for="seller" style="margin-right: 20px !important;">
                                             فروشنده
                                        </label>
                                    </div>

                                </div>


                                <div class="form-group row">

                                </div>


                                <div class="form-group row">
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-primary">ثبت</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div>
</div>

@endsection

