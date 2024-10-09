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
                                {{ Breadcrumbs::render('numbers.create') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-phone page-icon"></i>
                            افزودن شماره جدید
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body" style="padding:70px;">

                            <form method="POST" action="{{ route('admin.numbers.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                       <label for="firstname" class="col-md-12 col-form-label text-md-left IRANYekanRegular">نام :</label>
                                        <input id="firstname" type="text" class="form-control  @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}"  autofocus placeholder="نام">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('firstname') }} </span>
                                    </div>

                                    <div class="col-md-6 col-12">
                                       <label for="lastname" class="col-md-12 col-form-label text-md-left IRANYekanRegular">نام خانوادگی :</label>
                                        <input id="lastname" type="text" class="form-control  @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}"  autofocus placeholder="نام خانوادگی">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('lastname') }} </span>
                                    </div>

                                    <div class="col-md-6 col-12">
                                       <label for="name" class="col-md-12 col-form-label text-md-left IRANYekanRegular"> موبایل:</label>
                                        <input id="name" type="text" class="form-control ltr  @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}"  autofocus placeholder="0911*******">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('mobile') }} </span>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label for="account" class="col-form-label IRANYekanRegular">مرجع پذیرش</label>
                                        <select name="type" id="st" class="form-control dropdown IR">
                                            <option value="{{ App\Enums\NumberType::his }}" {{ App\Enums\NumberType::his==old('type')?'selected':'' }}>HIS</option>
                                            <option value="{{ App\Enums\NumberType::instagram }}" {{ App\Enums\NumberType::instagram==old('type')?'selected':'' }}>اینستاگرام</option>
                                            <option value="{{ App\Enums\NumberType::telegram }}" {{ App\Enums\NumberType::telegram==old('type')?'selected':'' }}>تلگرام</option>
                                            <option value="{{ App\Enums\NumberType::sms }}" {{ App\Enums\NumberType::sms==old('type')?'selected':'' }}>پیامک</option>
                                            <option value="{{ App\Enums\NumberType::lahijan }}" {{ App\Enums\NumberType::tehran==old('type')?'selected':'' }}>شعبه لاهیجان</option>
                                            <option value="{{ App\Enums\NumberType::tehran }}" {{ App\Enums\NumberType::tehran==old('type')?'selected':'' }}>شعبه تهران</option>
                                            <option value="{{ App\Enums\NumberType::call }}" {{ App\Enums\NumberType::call==old('type')?'selected':'' }}>تماس های ورودی</option>
                                        </select>
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-1 text-right">
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

