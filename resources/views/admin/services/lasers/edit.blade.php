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
                                {{ Breadcrumbs::render('services.lasers.create',$laser) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-deaf page-icon"></i>
                            ویرایش سرویس لیزر
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

                                <form class="form-horizontal" action="{{ route('admin.services.lasers.update',$laser) }}" method="post">
                                     @csrf
                                     @method('PATCH')

                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="title" class="control-label IRANYekanRegular">نام سرویس</label>
                                            <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان سرویس را وارد کنید" value="{{ old('title') ?? $laser->title  }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="skin" class="control-label IRANYekanRegular">رنگ پوست</label>
                                            <input type="text" class="form-control input" name="skin" id="skin" placeholder="رنگ پوست را وارد کنید" value="{{ old('skin') ?? $laser->skin  }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('skin') }} </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12 col-md-6">
                                            <label for="weight" class="control-label IRANYekanRegular">رنج وزنی</label>
                                            <input type="text" class="form-control input text-right" name="weight" id="weight" placeholder="رنج وزنی را وارد کنید" value="{{ old('weight') ?? $laser->weight  }}" required>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('weight') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="shot" class="control-label IRANYekanRegular">تعداد شات</label>
                                            <input type="number" min="1" class="form-control input text-center" name="shot" id="shot" placeholder="تعداد شات را وارد کنید" value="{{ old('shot') ?? $laser->shot  }}" required>

                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('shot') }} </span>
                                        </div>
                                    </div>
                                    <div class="form-group mt-2">
                                        <div class="col-sm-12">
                                            <button type="submit" title="ثبت" class="btn btn-success">بروزرسانی</button>
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
