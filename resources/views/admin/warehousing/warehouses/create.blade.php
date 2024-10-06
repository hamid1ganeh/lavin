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
                                {{ Breadcrumbs::render('warehousing.warehouses.create') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="mdi mdi-city page-icon"></i>
                             ایجاد انبار جدید
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

                                <form class="form-horizontal" action="{{ route('admin.warehousing.warehouses.store') }}" method="post">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <div class="col-12">
                                            <label for="name" class="control-label IRANYekanRegular">عنوان</label>
                                            <input type="text" class="form-control input" name="name" id="name" placeholder="عنوان انبار را وارد کنید" value="{{ old('name') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('name') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-12">
                                            <label for="description" class="control-label IRANYekanRegular">توضیحات</label>
                                            <input type="text" class="form-control input" name="description" id="description" placeholder="توضیحات انبار را وارد کنید" value="{{ old('description') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('description') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-12">
                                            <label for="admins" class="control-label IRANYekanRegular">مسئولین:</label>
                                            <select name="admins[]" id="admins" class="select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="انتخاب مسئولین انبار...">
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}">{{ $admin->fullname }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('admins') }} </span>
                                        </div>
                                    </div>


                                    <div class="row mt-2 p-2">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" @if(old('status')!=App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="active" class="IRANYekanRegular">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}" @if(old('status')==App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="deactive" class="IRANYekanRegular">غیرفعال</label><br>
                                        </div>
                                    </div>


                                    <div class="form-group mt-2">
                                        <div class="col-sm-12">
                                            <button type="submit" title="ثبت" class="btn btn-primary">ثبت</button>
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
