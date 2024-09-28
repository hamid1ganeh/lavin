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
{{--                             {{ Breadcrumbs::render('article.create') }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fa fa-spinner page-icon"></i>
                             ویرایش سرویس آنالیز
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

                                <form class="form-horizontal" action="{{ route('admin.analysis.update', $analise) }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    @method('patch')

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="title" class="control-label IRANYekanRegular">عنوان سرویس</label>
                                            <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان را وارد کنید" value="{{ old('analyse') ?? $analise->title }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="description" class="control-label IRANYekanRegular">توضیحات</label>
                                             <textarea class="form-control" row="100"  name="description" id="content" placeholder="توضیحات  را وارد کنید...">{{ old('description') ?? $analise->description }}</textarea>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('description') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="price" class="control-label IRANYekanRegular">حداقل قیمت:</label>
                                            <input type="text" class="form-control input text-right" name="min_price" id="min_price" placeholder="حداقل قیمت را وارد کنید..." value="{{ old('min_price') ?? $analise->min_price }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('min_price') }} </span>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="price" class="control-label IRANYekanRegular"> حداکثر قیمت:</label>
                                            <input type="text" class="form-control input text-right" name="max_price" id="max_price" placeholder="حداکثر قیمت را وارد کنید..." value="{{ old('max_price') ?? $analise->max_price }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('max_price') }} </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="attach" class="col-1 col-form-label  IRANYekanRegular">تصویر شاخص:</label>
                                        <div class="fileupload btn btn-success waves-effect waves-light mb-3">
                                            <span><i class="mdi mdi-cloud-upload mr-1"></i>ضمیمه</span>
                                            <input type="file" class="upload" name="thumbnail" id="thumbnail"  value="{{ old('thumbnail') }}"  accept="image/*">
                                            <output id="list"></output>
                                        </div>

                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('thumbnail') }} </span>
                                    </div>

                                    <div class="form-group col-md-4">
                                        @if($analise->thumbnail != null)
                                            <img src="{{  $analise->thumbnail->getImagePath('medium') }}" width="200">
                                        @endif
                                    </div>


                                    <div class="row mt-2 p-2">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" @if(old('status')!=App\Enums\Status::Deactive &&  $analise->status!=App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="active">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}" @if(old('status')==App\Enums\Status::Deactive || $analise->status==App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="deactive">غیرفعال</label><br>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">ثبت</button>
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


<script>
    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object

        // files is a FileList of File objects. List some properties.
        var output = [];
        for (var i = 0, f; f = files[i]; i++) {
            output.push('<li><strong>',
                escape(f.name),
                '</strong>',
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="remove()">',
                '<span aria-hidden="true">&times;</span>',
                '</button>',
                '</li>');
        }
        document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
    }

    document.getElementById('thumbnail').addEventListener('change', handleFileSelect, false);

    function remove()
    {
        document.getElementById('thumbnail').value = "";
        document.getElementById('list').innerHTML ='';
    }
</script>

@endsection
