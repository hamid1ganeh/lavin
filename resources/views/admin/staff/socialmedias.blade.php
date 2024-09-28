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
                                {{ Breadcrumbs::render('staff.documents.socialmedias') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <img   width="30px" src="{{ url('images/front/instagram.png') }}" alt="شبکه های اجتماعی">
                           شبکه های اجتماعی
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
                            <div class="row">
                                <div class="col-12 text-right">
                                    <div class="btn-group" >

                                        <a href="#create" data-toggle="modal"  title="ایجاد" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد شبکه اجتماعی جدید</b>
                                        </a>

                                        <div class="modal fade" id="create" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد شبکه اجتماعی جدید</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.staff.documents.socialmedias.store') }}"  method="POST" class="d-inline" id="create-form">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-12 text-left">
                                                                    <label for="name" class="control-label IRANYekanRegular">نام</label>
                                                                    <input type="text" class="form-control input" name="name" id="name" placeholder="نام شبکه اجتماعی را وارد کنید" value="{{ old('name') }}" required>
                                                                </div>
                                                                <div class="col-12  text-left mt-2">
                                                                    <label for="link" class="control-label IRANYekanRegular">لینک یا شناسه</label>
                                                                    <input type="text" class="form-control input text-right" name="link" id="link" placeholder="لینک  یا شناسه را وارد کنید" value="{{ old('link') }}" required>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <div class="modal-footer">
                                                       <button type="submit" title="حذف" class="btn btn-primary px-8" form="create-form">ثبت</button>
                                                        &nbsp;&nbsp;
                                                        <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                    </div>
                                                </div>
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
                                            <th><b class="IRANYekanRegular">نام</b></th>
                                            <th><b class="IRANYekanRegular">لینک یا شناسه</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($medias as $index=>$media)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $media->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $media->link }}</strong></td>
                                            <td>

                                                <a href="#edit{{ $media->id }}" data-toggle="modal" class="font18 m-1" title="ویرایش">
                                                    <i class="fa fa-edit text-success"></i>
                                                </a>

                                                <div class="modal fade" id="edit{{ $media->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ویرایش رشته</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <form action="{{ route('admin.staff.documents.socialmedias.update',$media) }}"  method="POST" class="d-inline" id="update-form{{ $media->id }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                        <div class="col-12 text-left">
                                                                            <label for="name" class="control-label IRANYekanRegular">نام</label>
                                                                            <input type="text" class="form-control input" name="name" id="name{{ $media->id }}" placeholder="نام شبکه اجتماعی را وارد کنید" value="{{ old('name') ?? $media->name }}" required>
                                                                        </div>
                                                                        <div class="col-12 text-left mt-2">
                                                                            <label for="link" class="control-label IRANYekanRegular">لینک یا شناسه</label>
                                                                            <input type="text" class="form-control input text-right" name="link" id="link{{ $media->id }}" placeholder="لینک  یا شناسه را وارد کنید" value="{{ old('link') ?? $media->link  }}" required>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" title="حذف" class="btn btn-success px-8" form="update-form{{ $media->id }}">بروزرسانی</button>
                                                                &nbsp;&nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                              <a href="#remove{{ $media->id }}" data-toggle="modal" class="font18 m-1" title="حذف">
                                                 <i class="fa fa-trash text-danger"></i>
                                               </a>

                                               <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $media->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف شبکه اجتماعی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید شبکه اجتماعی {{ $media->name }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.staff.documents.socialmedias.delete',$media) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" title="حذف" class="btn btn-danger px-8">حذف</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

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
@endsection
