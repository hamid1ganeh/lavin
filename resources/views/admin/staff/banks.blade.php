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
                                {{ Breadcrumbs::render('staff.documents.banks') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <img   width="30px" src="{{ url('images/front/bank.png') }}" alt="مشخات بانکی">
                            مشخصات بانکی
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
                                            <b class="IRANYekanRegular">ایجاد مشخصات بانکی جدید</b>
                                        </a>

                                        <div class="modal fade" id="create" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد  مشخصات بانکی جدید</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.staff.documents.banks.store') }}"  method="POST" class="d-inline" id="create-form">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-12 text-left">
                                                                    <label for="name" class="control-label IRANYekanRegular">نام</label>
                                                                    <input type="text" class="form-control input" name="name" id="name" placeholder="نام  بانک را وارد کنید" value="{{ old('name') }}" required>
                                                                </div>
                                                                <div class="col-12  text-left mt-2">
                                                                    <label for="link" class="control-label IRANYekanRegular">شماره کارت</label>
                                                                    <input type="text" class="form-control input text-right" name="number" id="number" placeholder="شماره کارت" value="{{ old('number') }}" required minlength="16" maxlength="16">
                                                                </div>

                                                                <div class="col-12 text-left">
                                                                    <div class="form-group">
                                                                        <label for="link" class="control-label IRANYekanRegular">شماره شبا</label>
                                                                        <div class="input-group mb-3">
                                                                            <input type="text" class="form-control input text-right" name="shaba" id="shaba" placeholder="شماره شبا" value="{{ old('shaba') }}" required>

                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">IR</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                                            <th><b class="IRANYekanRegular">شماره کارت</b></th>
                                            <th><b class="IRANYekanRegular">شماره شبا</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($banks as $index=>$bank)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $bank->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $bank->number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $bank->shaba }}</strong></td>
                                            <td>

                                                <a href="#edit{{ $bank->id }}" data-toggle="modal" class="font18 m-1" title="ویرایش">
                                                    <i class="fa fa-edit text-success"></i>
                                                </a>

                                                <div class="modal fade" id="edit{{ $bank->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ویرایش مشخصات بانکی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <form action="{{ route('admin.staff.documents.banks.update',$bank) }}"  method="POST" class="d-inline" id="update-form{{ $bank->id }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                        <div class="col-12 text-left">
                                                                            <label for="name" class="control-label IRANYekanRegular">نام</label>
                                                                            <input type="text" class="form-control input" name="name" id="name{{ $bank->id }}" placeholder="نام  بانک را وارد کنید" value="{{ old('name') ?? $bank->name }}" required>
                                                                        </div>
                                                                        <div class="col-12 text-left mt-2">
                                                                            <label for="link" class="control-label IRANYekanRegular">شماره کارت</label>
                                                                            <input type="text" class="form-control input text-right" name="number" id="number{{ $bank->id }}" placeholder="شماره کارت را وارد کنید" value="{{ old('number') ?? $bank->number  }}"  required minlength="16" maxlength="16">
                                                                        </div>
                                                                        <div class="col-12 text-left">
                                                                            <div class="form-group">
                                                                                <label for="link" class="control-label IRANYekanRegular">شماره شبا</label>
                                                                                <div class="input-group mb-3">
                                                                                    <input type="text" class="form-control input text-right" name="shaba" id="shaba" placeholder="شماره شبا" value="{{ old('shaba') ?? $bank->shaba }}" required>

                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">IR</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" title="حذف" class="btn btn-success px-8" form="update-form{{ $bank->id }}">بروزرسانی</button>
                                                                &nbsp;&nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                              <a href="#remove{{ $bank->id }}" data-toggle="modal" class="font18 m-1" title="حذف">
                                                 <i class="fa fa-trash text-danger"></i>
                                               </a>

                                               <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $bank->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف مشخصات بانکی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید مشخصات بانکی {{ $bank->name }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.staff.documents.banks.delete',$bank) }}"  method="POST" class="d-inline">
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
