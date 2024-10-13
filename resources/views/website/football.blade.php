@extends('layouts.master')

@section('content')
    <div class="position-relative pb-5">
        @include('layouts.header',['title'=>'طرح معرفی آکادمی فوتبال صبوری','background'=>'/images/front/service-bg.jpg'])
        <div class="px-lg-5 text-center w-100 position-absolute" style="bottom: 0">
{{--            <h1 class="h3 dima text-pink">درباره‌ی کلینیک لاوین</h1>--}}


            <div class="row mx-0 justify-content-center  mt-2 mb-3">
                <a class="badge bg-light rounded-circle d-flex mr-1" style="width: 30px; height: 30px;" href="#">
                    <i class="fab fa-facebook-f m-auto"></i>
                </a>
                <a class="badge bg-light rounded-circle d-flex mr-1" style="width: 30px; height: 30px;" href="#">
                    <i class="fab fa-twitter m-auto"></i>
                </a>
                <a class="badge bg-light rounded-circle d-flex mr-1" style="width: 30px; height: 30px;" href="#">
                    <i class="fab fa-linkedin m-auto"></i>
                </a>
                <a class="badge bg-light rounded-circle d-flex mr-1" style="width: 30px; height: 30px;" href="#">
                    <i class="fab fa-google-plus m-auto"></i>
                </a>
            </div>

        </div>

    </div>
    <section class="w-100 banner">


        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="IR">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="container py-5">
            <div class="rgba-black rounded-xl text-white p-2 p-xl-5">
                <form action="{{ route('website.football.register') }}" method="post" class="border border-accent rounded py-4 px-md-5" style="border-width:2px !important;">
                    @csrf
                    <h2 class="font-weight-bold h5" style="color:#FFFFFF">فرم معرفی افراد</h2>
                    <div class="text-right row mx-0">
                        <div class="col-12 col-md-6 form-control w-100 mt-2">
                            <label class="w-100 mb-0 small text-black" style="color:#FFFFFF">نام</label>
                            <input type="text" name="firstname" id="firstname" class="w-100 px-3" value="{{ old('firstname') }}" placeholder="نام" required>
                            <span class="form-text text-danger erroralarm"> {{ $errors->first('firstname') }} </span>
                        </div>
                        <div class="col-12 col-md-6 form-control w-100 mt-2">
                            <label class="w-100 mb-0 small text-black" style="color:#FFFFFF">نام خانوادگی</label>
                            <input type="text" name="lastname" id="lastname" class="w-100 px-3" value="{{ old('lastname') }}" placeholder="نام خانوادگی" required>
                            <span class="form-text text-danger erroralarm"> {{ $errors->first('lastname') }} </span>
                        </div>
                        <div class="col-12 col-md-6 form-control w-100 mt-2">
                            <label class="w-100 mb-0 small text-black" style="color:#FFFFFF">تلفن همراه</label>
                            <input type="text" name="mobile" id="mobile" class="w-100 px-3 text-left" value="{{ old('mobile') }}" placeholder="تلفن همراه" required>
                            <span class="form-text text-danger erroralarm"> {{ $errors->first('mobile') }} </span>
                        </div>
                        <div class="col-12 col-md-6 form-control w-100 mt-2">
                            <label class="w-100 mb-0 small text-black" style="color:#FFFFFF">کدملی</label>
                            <input type="text" name="nationcode" id="nationcode" class="w-100 px-3" value="{{ old('nationcode') }}" placeholder="کدملی" required>
                            <span class="form-text text-danger erroralarm"> {{ $errors->first('nationcode') }} </span>
                        </div>

                        <div class="col-12 col-md-6 form-control w-100 mt-2">
                            <label class="w-100 mb-0 small text-black" style="color:#FFFFFF">جنسیت</label>
                             <select name="gender" id="gender">
                                 <option value="{{ App\Enums\genderType::female }}" @if(old('gender') == App\Enums\genderType::female) selected @endif>زن</option>
                                 <option value="{{ App\Enums\genderType::male }}" @if(old('gender') == App\Enums\genderType::male) selected @endif>مرد</option>
                                 <option value="{{ App\Enums\genderType::LGBTQ }}" @if(old('gender') == App\Enums\genderType::LGBTQ) selected @endif>LGBTQ</option>
                             </select>
                            <span class="form-text text-danger erroralarm"> {{ $errors->first('gender') }} </span>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group text-center">
                                @if(config('recaptcha.api_site_key'))
                                    <div class="g-recaptcha"
                                         data-sitekey="{{config('recaptcha.api_site_key')}}">
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div class="col-12 mt-3 text-center">
                            <input type="submit" class="button button-primary" value="ارسال فرم">
                        </div>
                    </div>
                </form>
             </div>
        </div>
    </section>

@stop

@push('css')
    <style>

        .border-accent{
            border-color:#2ed3ae !important;
        }
        .banner{
            background-image: url('/images/front/football.jpg');
            background-size: cover;
            background-position: center;
        }
        .rgba-black{

        }

    </style>
@endpush
