@extends('admin.master')


@section('script')

    <script type="text/javascript">
        $("#end").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#end",
            textFormat: "yyyy/MM/dd HH:mm:ss",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });
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
{{--                                {{ Breadcrumbs::render('branchs.create') }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="ti-wand page-ico page-icon"></i>
                             ویرایش جشنواره
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

                                <form class="form-horizontal" action="{{ route('admin.festivals.update',$festival) }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    @method('patch')

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="title" class="control-label IRANYekanRegular">عنوان جشنواره</label>
                                            <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان جشنوراه را وارد کنید" value="{{ old('title') ?? $festival->title }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="end" class="col-form-label IRANYekanRegular">پایان</label>
                                            <input type="text"   class="form-control text-center" id="end" name="end"
                                             value="{{ \Morilog\Jalali\CalendarUtils::convertNumbers(\Morilog\Jalali\CalendarUtils::strftime('Y/m/d H:i:s',strtotime($festival->end))) }}" readonly>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('end') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <label for="content" class="control-label IRANYekanRegular">توضیحات</label>
                                            <textarea class="ckeditor form-control" row="500" class="form-control" name="description" id="description" placeholder="توضیحات را وارد کنید...">{{ old('description') ?? $festival->description }}</textarea>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('description') }} </span>
                                        </div>
                                    </div>

                                    <div class="row mt-2 p-2">
                                        <div class="form-group col-6">
                                            <div class="fileupload btn btn-success waves-effect waves-light mb-3">
                                                <span><i class="mdi mdi-cloud-upload mr-1"></i>ضمیمه</span>
                                                <input type="file" class="upload" name="thumbnail" id="thumbnail"  value="{{ old('thumbnail') }}"  accept="image/*">
                                                <output id="list"></output>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('thumbnail') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" @if($festival->status!=App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="active" class="IR">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}" @if($festival->status==App\Enums\Status::Deactive) checked @endif>
                                            &nbsp;
                                            <label for="deactive" class="IR">غیرفعال</label><br>
                                        </div>
                                    </div>


                                    <div class="form-group mt-2">
                                        <div class="col-sm-12">
                                            <button type="submit" title="ثبت" class="btn btn-primary">بروزرسانی</button>
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
