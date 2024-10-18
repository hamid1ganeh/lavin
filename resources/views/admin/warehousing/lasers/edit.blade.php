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
                                {{ Breadcrumbs::render('warehousing.lasers.edit',$laser) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-deaf page-icon"></i>
                             ویرایش دستگاه دستگاه
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

                            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="margin:auto">

                                <form class="form-horizontal" action="{{ route('admin.warehousing.lasers.update',$laser) }}" method="post">
                                     @csrf
                                     @method('PATCH')

                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="name" class="control-label IRANYekanRegular">نام دستگاه</label>
                                            <input type="text" class="form-control input" name="name" id="name" placeholder="عنوان دستگاه را وارد کنید" value="{{ old('name') ?? $laser->name }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('name') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="brand" class="control-label IRANYekanRegular">برند</label>
                                            <input type="text" class="form-control input" name="brand" id="brand" placeholder="برند دستگاه را وارد کنید" value="{{ old('brand') ?? $laser->brand  }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('brand') }} </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="model" class="control-label IRANYekanRegular">مدل دستگاه</label>
                                            <input type="text" class="form-control input text-right" name="model" id="model" placeholder="مدل دستگاه را وارد کنید" value="{{ old('model') ?? $laser->model }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('model') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="code" class="control-label IRANYekanRegular">کد دستگاه</label>
                                            <input type="text" class="form-control input text-right" name="code" id="code" placeholder="کد دستگاه را وارد کنید" value="{{ old('code') ?? $laser->code }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('code') }} </span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-12 col-md-1">
                                            <label for="year" class="control-label IRANYekanRegular">سال ساخت</label>
                                            <input type="text"   class="form-control text-center" id="year" name="year"  value="{{ old('year')  ?? $laser->year  }}" placeholder="سال " maxlength="4" minlength="4" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('year') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <div class="col-sm-12">
                                            <button type="submit" title="بروزرسانی" class="btn btn-success">بروزرسانی</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
