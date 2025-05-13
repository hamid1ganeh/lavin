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
                            {{ Breadcrumbs::render('numbers.edit',$number) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-phone page-icon"></i>
                            ویرایش شماره
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body" style="padding:70px;">

                            <form method="POST" action="{{ route('admin.numbers.update',$number) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                       <label for="firstname" class="col-md-12 col-form-label text-md-left IRANYekanRegular">نام :</label>
                                        <input id="firstname" type="text" class="form-control  @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') ?? $number->firstname }}"  autofocus placeholder="نام">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('firstname') }} </span>
                                    </div>

                                    <div class="col-md-6 col-">
                                       <label for="lastname" class="col-md-12 col-form-label text-md-left IRANYekanRegular">نام خانوادگی:</label>
                                        <input id="lastname" type="text" class="form-control  @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') ?? $number->lastname }}"  autofocus placeholder="نام خانوادگی">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('lastname') }} </span>
                                    </div>

                                    <div class="col-md-6 col-12">
                                       <label for="name" class="col-md-12 col-form-label text-md-left IRANYekanRegular"> موبایل:</label>
                                        <input id="name" type="text" class="form-control ltr  @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') ?? $number->mobile }}"  autofocus placeholder="0911*******">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('mobile') }} </span>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label for="account" class="col-form-label IRANYekanRegular">مرجع پذیرش</label>
                                        <select name="type" id="st" class="form-control dropdown IR">
                                            <option value="{{ App\Enums\NumberType::his }}" {{ App\Enums\NumberType::his==old('type')||App\Enums\NumberType::his==$number->type?'selected':'' }}>HIS</option>
                                            <option value="{{ App\Enums\NumberType::instagram }}" {{ App\Enums\NumberType::instagram==old('type')||App\Enums\NumberType::instagram==$number->type?'selected':'' }}>اینستاگرام</option>
                                            <option value="{{ App\Enums\NumberType::telegram }}" {{ App\Enums\NumberType::telegram==old('type')||App\Enums\NumberType::telegram==$number->type?'selected':'' }}>تلگرام</option>
                                            <option value="{{ App\Enums\NumberType::sms }}" {{ App\Enums\NumberType::sms==old('type')||App\Enums\NumberType::sms==$number->type?'selected':'' }}>پیامک</option>
                                            <option value="{{ App\Enums\NumberType::lahijan }}" {{ App\Enums\NumberType::lahijan==old('type')||App\Enums\NumberType::lahijan==$number->type?'selected':'' }}>شعبه لاهیجان</option>
                                            <option value="{{ App\Enums\NumberType::tehran }}" {{ App\Enums\NumberType::tehran==old('type')||App\Enums\NumberType::tehran==$number->type?'selected':'' }}>شعبه تهران</option>
                                            <option value="{{ App\Enums\NumberType::hozoori }}" {{ App\Enums\NumberType::call==old('type')||App\Enums\NumberType::hozoori==$number->type?'selected':'' }}>حضوری</option>
                                            <option value="{{ App\Enums\NumberType::call }}" {{ App\Enums\NumberType::call==old('type')||App\Enums\NumberType::call==$number->type?'selected':'' }}>تماس های ورودی</option>
                                            <option value="{{ App\Enums\NumberType::football }}" {{ App\Enums\NumberType::football==old('type')||App\Enums\NumberType::football==$number->type?'selected':'' }}>آکادمی فوتبال صبوری</option>
                                            <option value="{{ App\Enums\NumberType::system }}" {{ App\Enums\NumberType::system==old('type')||App\Enums\NumberType::system==$number->type?'selected':'' }}>معرفی سیستم</option>
                                            <option value="{{ App\Enums\NumberType::etc }}" {{ App\Enums\NumberType::system==old('type')||App\Enums\NumberType::etc==$number->type?'selected':'' }}>غیره...</option>
                                        </select>
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-1 text-right">
                                        <button type="submit" class="btn btn-primary">بروزرسانی</button>
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

