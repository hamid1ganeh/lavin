@extends('admin.master')

@section('script')

    <script src="/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">

        const ck_config = {
            contentsLangDirection : 'rtl',
            toolbar: [
                ['Styles','Format','Font','FontSize'],
                ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Print'],
                ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Image','Table','-','Link','Flash','Smiley','TextColor','BGColor','Source'],
            ],
            filebrowserUploadUrl:"{{ route('admin.article.ckeditor', ['_token' => csrf_token() ]) }}",
            filebrowserUploadMethod: 'form'
        };
        CKEDITOR.replace('desc',ck_config);

    </script>

@endsection

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
                            {{ Breadcrumbs::render('services.detiles.create') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fa fa-info page-icon"></i>
                             ایجاد خدمت جدید
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

                                <form class="form-horizontal" action="{{ route('admin.details.store') }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}


                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="service" class="ol-form-label text-md-right IRANYekanRegular">سرگروه سرویس:</label>
                                            <select name="service" id="service" class="select2  text-right IRANYekanRegular" data-placeholder="انتخاب سرویس..." @error('serviceses') is-invalid @enderror>
                                                @foreach($allservices as $service)
                                                    <option value="{{ $service->id }}"  @if(old('service')==$service->id) {{ 'selected' }}  @endif>{{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('service') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="name" class="control-label IRANYekanRegular">عنوان</label>
                                            <input type="text" class="form-control input" name="name" id="name" placeholder="عنوان را وارد کنید..." value="{{ old('name') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('name') }} </span>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="price" class="control-label IRANYekanRegular">قیمت:</label>
                                            <input type="text" class="form-control input text-right" name="price" id="price" placeholder="قیمت را وارد کنید..." value="{{ old('price') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('price') }} </span>
                                        </div>
                                    </div>


                                    <div class="row mt-2">

                                        <div class="col-12 col-md-6">
                                            <label for="point" class="control-label IRANYekanRegular">امتیاز سرویس</label>
                                            <input type="number" class="form-control input text-center" name="point" id="point" placeholder="متیاز سرویس را وارد کنید..." value="{{ old('point') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('point') }} </span>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="porsant" class="control-label IRANYekanRegular">امتیاز معرف</label>
                                            <input type="number" class="form-control input text-center" name="porsant" id="porsant" placeholder="امتیاز معرف را وارد کنید..." value="{{ old('porsant') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('porsant') }} </span>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-12">
                                            <label for="breif" class="control-label IRANYekanRegular">توضیح کوتاه</label>
                                            <input type="text" class="form-control input" name="breif" id="breif" placeholder="توضیح کوتاه را وارد کنید..." value="{{ old('breif') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('breif') }} </span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="content" class="control-label IRANYekanRegular">توضیحات کامل</label>
                                            <textarea class="ckeditor form-control" row="100" class="form-control" name="desc" id="desc" placeholder="توضیحات را وارد کنید...">{{ old('desc') }}</textarea>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('desc') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="branches" class="ol-form-label text-md-right IRANYekanRegular"> شعبه ها:</label>
                                            <select name="branches[]" id="branches" class="select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="انتخاب شعبه ها..." @error('serviceses') is-invalid @enderror>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}"  @if(old('branches')!=null && in_array($branch->id,old('branches'))) {{ 'selected' }}  @endif>{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('branches') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="doctors" class="ol-form-label text-md-right IRANYekanRegular">پزشکان مرتبط:</label>
                                            <select name="doctors[]" id="doctors" class="select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="انتخاب نقش‌ها..." @error('serviceses') is-invalid @enderror>
                                                @foreach($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}"  @if(old('doctors')!=null && in_array($doctor->id,old('doctors'))) {{ 'selected' }}  @endif>{{ $doctor->fullname }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('doctors') }} </span>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="advisers" class="ol-form-label text-md-right IRANYekanRegular">مشاوران مرتبط:</label>
                                            <select name="advisers[]" id="advisers" class="select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="انتخاب مشاوران...">
                                                @foreach($advisers as $adviser)
                                                    <option value="{{ $adviser->id }}"  @if(old('advisers')!=null && in_array($adviser->id,old('advisers'))) {{ 'selected' }}  @endif>{{ $adviser->fullname }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('advisers') }} </span>
                                        </div>
                                    </div>


                                    <div class="row my-3">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" checked>
                                            &nbsp;
                                            <label for="active" class="IR">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}">
                                            &nbsp;
                                            <label for="deactive" class="IR">غیرفعال</label><br>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
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
