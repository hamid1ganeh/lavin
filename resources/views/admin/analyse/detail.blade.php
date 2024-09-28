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
                            {{ $analise->title  }}
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="row">
                                    <div class="col-12 alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li class="IR">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                                <div class="row">
                                    <div class="col-12 col-md-3">
                                    @if($analise->thumbnail != null)
                                        <img src="{{  $analise->thumbnail->getImagePath('medium') }}">
                                    @endIf
                                    </div>

                                    <div class="col-12 col-md-7 p-3">
                                        <p class="IR"> توضیحات:{{ $analise->description  }}</p>
                                        <p class="IR"> حداقل قیمت:{{ $analise->min_price  }}</p>
                                        <p class="IR"> حداکثر قیمت:{{ $analise->max_price  }}</p>
                                    </div>
                                    <div class="col-12 col-md-2 text-right">
                                        @if(Auth::guard('admin')->user()->can('analysis.image.store'))
                                        <div class="btn-group mb-3" >
                                            <a    href="#add" data-toggle="modal" title="افزودن تصویر نمونه جدید"  class="font18 btn btn-sm btn-primary">
                                                <i class="fa fa-image  plusiconfont"></i>
                                                <b class="IRANYekanRegular">افزودن تصویر نمونه جدید</b>
                                            </a>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Add Modal -->
                                    <div class="modal fade" id="add" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xs">
                                            <div class="modal-content">
                                                <div class="modal-header py-3">
                                                    <h5 class="modal-title IR" id="newReviewLabel">افزودن تصویر نمونه جدید</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal" id="image-form" action="{{ route('admin.analysis.images',$analise) }}" method="post" enctype="multipart/form-data">
                                                        {{ csrf_field() }}

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <label for="title" class="control-label IRANYekanRegular">عنوان تصویر</label>
                                                                <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان تصویر را وارد کنید" value="{{ old('title') }}">
                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <label for="description" class="control-label IRANYekanRegular">توضیحات</label>
                                                                <textarea class="form-control" row="100"  name="description" id="content" placeholder="توضیحات  را وارد کنید...">{{ old('description') }}</textarea>
                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('description') }} </span>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label for="attach" class="col-3 col-form-label  IRANYekanRegular">تصویر نمونه:</label>
                                                            <div class="fileupload btn btn-success waves-effect waves-light mb-3">
                                                                <span><i class="mdi mdi-cloud-upload mr-1"></i>ضمیمه</span>
                                                                <input type="file" class="upload" name="thumbnail" id="thumbnail"  value="{{ old('thumbnail') }}"  accept="image/*">
                                                                <output id="list"></output>
                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('thumbnail') }} </span>
                                                            </div>
                                                        </div>


                                                        <div class="row mt-2">
                                                            <div class="col-12" style="display:inherit;">
                                                                <input type="radio" id="required" name="required" value="1" @if(old('required')!='0') checked @endif>
                                                                &nbsp;
                                                                <label for="required" class="IR">الزامی</label><br>
                                                                &nbsp;&nbsp; &nbsp;
                                                                <input type="radio" id="optional" name="required" value="0" @if(old('status')=='0') checked @endif>
                                                                &nbsp;
                                                                <label for="optional" class="IR">اختیاری</label><br>
                                                            </div>
                                                        </div>

                                                    </form>

                                                 </div>
                                                <div class="modal-footer">
                                                   <button type="submit"  title="ثبت" class="btn btn-primary px-8" form="image-form">ثبت</button>
                                                    &nbsp;
                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="table-responsive">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">عنوان</b></th>
                                            <th><b class="IRANYekanRegular">توضیحات</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">تصویر نمونه</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($images as $index=>$image)
                                            <tr>
                                                <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $image->title }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $image->description ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{  $image->required==true?'الزامی':'اختیاری' }}</strong></td>
                                                <td>
                                                    @if($image->thumbnail != null)
                                                        <img src="{{  $image->thumbnail->getImagePath('thumbnail') }}" >
                                                    @endIf
                                                </td>
                                                <td style="text-align: right !important;">
                                                    @if(Auth::guard('admin')->user()->can('analysis.image.update'))
                                                    <a href="#edit{{ $image->id }}" data-toggle="modal" class="font18 m-1" title="ویرایش">
                                                        <i class="fa fa-edit text-success"></i>
                                                    </a>

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="edit{{ $image->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ویرایش تصویر سرویس آنالیز</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <form class="form-horizontal" id="image-form-update{{ $image->id  }}" action="{{ route('admin.analysis.image.update',$image) }}" method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PATCH')

                                                                        <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                                <label for="title" class="control-label IRANYekanRegular">عنوان تصویر</label>
                                                                                <input type="text" class="form-control input" name="title" id="title" placeholder="عنوان تصویر را وارد کنید" value="{{ old('title') ?? $image->title }}">
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('title') }} </span>
                                                                            </div>
                                                                        </div>


                                                                        <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                                <label for="description" class="control-label IRANYekanRegular">توضیحات</label>
                                                                                <textarea class="form-control" row="100"  name="description" id="content" placeholder="توضیحات  را وارد کنید...">{{ old('description') ?? $image->description }}</textarea>
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('description') }} </span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <label for="attach" class="col-3 col-form-label  IRANYekanRegular">تصویر نمونه:</label>
                                                                            <div class="fileupload btn btn-success waves-effect waves-light mb-3">
                                                                                <span><i class="mdi mdi-cloud-upload mr-1"></i>ضمیمه</span>
                                                                                <input type="file" class="upload" name="thumbnail" id="thumbnail"  value="{{ old('thumbnail') }}"  accept="image/*">
                                                                                <output id="list"></output>
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('thumbnail') }} </span>
                                                                            </div>
                                                                        </div>

                                                                        @if($analise->thumbnail != null)
                                                                            <img src="{{  $image->thumbnail->getImagePath('thumbnail') }}">
                                                                        @endIf


                                                                        <div class="row mt-2">
                                                                            <div class="col-12" style="display:inherit;">
                                                                                <input type="radio" id="required" name="required" value="1" @if(old('required') || $image->required) checked @endif>
                                                                                &nbsp;
                                                                                <label for="required" class="IR">الزامی</label><br>
                                                                                &nbsp;&nbsp; &nbsp;
                                                                                <input type="radio" id="optional" name="required" value="0" @if(old('status')=='0' || !$image->required) checked @endif>
                                                                                &nbsp;
                                                                                <label for="optional" class="IR">اختیاری</label><br>
                                                                            </div>
                                                                        </div>

                                                                    </form>

                                                                 </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success px-8" title="بروزرسانی" form="image-form-update{{ $image->id  }}">بروزرسانی</button>
                                                                     &nbsp
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif


                                                   @if(Auth::guard('admin')->user()->can('analysis.image.delete'))
                                                    <a href="#remove{{ $image->id }}" data-toggle="modal" class="font18 m-1" title="حذف">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </a>

                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $image->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف تصویر سرویس آنالیز</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید سرویس آنالیز {{ $image->title }} را حذف کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.analysis.image.delete',$image) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger px-8" title="حذف" >حذف</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

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
