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
                                {{ Breadcrumbs::render('highlights.stories.create') }}
                            </ol>

                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-image page-icon"></i>
                             ایجاد استوری جدید
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

                                <form class="form-horizontal" action="{{ route('admin.stories.store') }}" method="post" id="form" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="name" class="control-label IRANYekanRegular">عنوان</label>
                                            <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان استوری را وارد کنید" value="{{ old('title') }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="highlight" class="control-label IRANYekanRegular">هایلایت</label>
                                            <select name="highlight" id="highlight" class="form-control dropdown IR">
                                                <option value="">استوری های روزانه</option>
                                                @foreach($highlights as $highlight)
                                                <option value="{{ $highlight->id  }}">{{ $highlight->title }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('highlight') }} </span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <label for="attach" class="col-1 col-form-label  IRANYekanRegular"> تصویر:</label>
                                        <div class="fileupload btn btn-success waves-effect waves-light mb-3">
                                            <span><i class="mdi mdi-cloud-upload mr-1"></i>تصویر</span>
                                            <input type="file" class="upload" name="image" id="image"  value="{{ old('image') }}" accept="image/*">
                                            <output id="list"></output>
                                        </div>
                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('image') }} </span>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <label for="link" class="control-label IRANYekanRegular">لینک ویدئو</label>
                                            <input type="text" class="form-control input text-right" name="link" id="link" placeholder="لینک ویدئو را وارد کنید" value="{{ old('link')  }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('link') }} </span>
                                        </div>
                                    </div>

                                    <div class="row mt-2 p-2">
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
                                </form>


                                <form method="post" id="uploadform" action="{{ route('admin.upload.video') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <div class="fileupload btn btn-success waves-effect waves-light m-4">
                                                <span><i class="mdi mdi-cloud-upload mr-1"></i>آپلود ویدئو</span>
                                                <input type="file" class="video" name="video" value="" accept="video/*">
                                                <div class="input-group-append">
                                                    <button class="btn btn-warning" type="submite">آپلود</button>
                                                </div>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('video') }} </span>
                                        </div>
                                    </div>
                                </form>

                                <div class="row mt-2">
                                    <div class="progress" id="progress">
                                        <div class="bar" id="bar"></div >
                                        <div class="percent" id="percent">0%</div >
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-12">
                                        <input type="submit"  value="ثبت" class="btn btn-primary" form="form">
                                    </div>
                                </div>

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

        $(function()
        {
            $(document).ready(function()
            {
                var bar = $('.bar');
                var percent = $('.percent');

                $('#uploadform').ajaxForm({

                    beforeSend: function()
                    {
                        document.getElementById('progress').style.display = 'inline-flex';
                        var percentVal = '0%';
                        bar.width(percentVal)
                        percent.html(percentVal);
                    },
                    uploadProgress: function(event, position, total, percentComplete)
                    {
                        var percentVal = percentComplete + '%';
                        bar.width(percentVal)
                        percent.html(percentVal);
                    },
                    complete: function(xhr)
                    {
                        document.getElementById('percent').style.color = '#fff';
                        document.getElementById('bar').style.background = '#95dd80';
                        var response = JSON.parse(xhr.responseText);
                        document.getElementById('link').value = response.url;
                    }
                });

            });

        });

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

        document.getElementById('image').addEventListener('change', handleFileSelect, false);

        function remove()
        {
            document.getElementById('image').value = "";
            document.getElementById('list').innerHTML ='';
        }

    </script>


@endsection

