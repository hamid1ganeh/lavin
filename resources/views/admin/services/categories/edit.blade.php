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
                              {{ Breadcrumbs::render('services.cat.edit',$category) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-layer-group page-icon"></i>
                             ویرایش دسته بندی
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="margin:auto">

                                <form class="form-horizontal" action="{{ route('admin.services.categories.update',$category) }}" method="post" enctype="multipart/form-data">

                                    {{ csrf_field() }}
                                    @method('patch')

                                    <div class="form-group">
                                        <div class="co-12">
                                            <label for="name" class="control-label IRANYekanRegular">نام دسته</label>
                                            <input type="text" class="form-control input" name="name" id="name" placeholder="نام دسته را وارد کنید" value="{{ old('name') ?? $category->name }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('name') }} </span>
                                        </div>
                                    </div>

                                    <div class="row my-1">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" @if(old('status')==App\Enums\Status::Active || $category->status ==App\Enums\Status::Active) checked @endif>
                                            &nbsp;
                                            <label for="active" class="IR">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}" @if(old('status')==App\Enums\Status::Deactive || $category->status ==App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="deactive" class="IR">غیرفعال</label><br>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" title="ثبت" class="btn btn-info">بروزرسانی</button>
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
