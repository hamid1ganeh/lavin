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
                                {{ Breadcrumbs::render('highlights.create') }}
                            </ol>

                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-image page-icon"></i>
                             ایجاد هایلایت جدید
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

                                <form class="form-horizontal" action="{{ route('admin.highlights.store') }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <div class="col-12">
                                            <label for="name" class="control-label IRANYekanRegular">عنوان</label>
                                            <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان هایلایت را وارد کنید" value="{{ old('title') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-12">
                                            <div class="fileupload btn btn-success waves-effect waves-light">
                                                <span><i class="mdi mdi-cloud-upload mr-1"></i>تصویر شاخص</span>
                                                <input type="file" class="upload" name="thumbnail" id="thumbnail"  value="{{ old('image') }}" accept="image/*">
                                                <output id="list"></output>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('thumbnail') }} </span>
                                        </div>
                                    </div>

                                    <div class="row mr-2">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" @if(old('status')!=App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="active" class="IR">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}" @if(old('status')==App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="deactive" class="IR">غیرفعال</label><br>
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

@section('script')
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
