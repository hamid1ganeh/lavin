@extends('crm.master')

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
                              
                            </ol>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body" style="padding:70px;">

                            <form method="POST" action="{{ route('website.account.numbers.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-4 col-12">
                                       <label for="firstname" class="col-md-12 col-form-label text-md-left IRANYekanRegular">نام :</label>
                                        <input id="firstname" type="text" class="form-control  @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}"  autofocus placeholder="نام">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('firstname') }} </span>
                                    </div>

                                    <div class="col-md-4 col-12">
                                       <label for="lastname" class="col-md-12 col-form-label text-md-left IRANYekanRegular">نام خانوادگی :</label>
                                        <input id="lastname" type="text" class="form-control  @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}"  autofocus placeholder="نام خانوادگی">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('lastname') }} </span>
                                    </div>
                                      
                                    <div class="col-md-4 col-12">
                                       <label for="name" class="col-md-12 col-form-label text-md-left IRANYekanRegular"> موبایل:</label>
                                        <input id="name" type="text" class="form-control ltr  @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}"  autofocus placeholder="0911*******">
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('mobile') }} </span>
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