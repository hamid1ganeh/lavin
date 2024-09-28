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
                                {{ Breadcrumbs::render('admins.staff.documents.banks',$admin) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <img   width="30px" src="{{ url('images/front/bank.png') }}" alt="مشخات بانکی">
                            مشخصات بانکی {{ $admin->fullname }}
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
                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">نام</b></th>
                                            <th><b class="IRANYekanRegular">شماره کارت</b></th>
                                            <th><b class="IRANYekanRegular">شماره شبا</b></th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($banks as $index=>$bank)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $bank->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $bank->number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $bank->shaba }}</strong></td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12">

                                    @if(Auth::guard('admin')->user()->can('admins.documents.confirm'))
                                    <!-- Confirm Modal -->
                                    <div class="modal fade" id="confirm" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xs">
                                            <div class="modal-content">
                                                <div class="modal-header py-3">
                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">تایید مشخصات بانکی</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید مشخصات بانکی را تایید نمایید؟</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.admins.staff.banks.confirm',$admin)  }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('patch')
                                                        <button type="submit" title="تایید" class="btn btn-primary px-8">تایید</button>
                                                    </form>
                                                    &nbsp;
                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a class="font18" href="#confirm" data-toggle="modal" title="تایید">
                                        <i class="fas fa-thumbs-up text-primary"></i>
                                    </a>
                                    &nbsp;
                                    @endif
                                    @if(Auth::guard('admin')->user()->can('admins.documents.confirm'))
                                    <a class="font18" href="#reject" data-toggle="modal" title="رد">
                                        <i class="fas fa-thumbs-down text-danger"></i>
                                    </a>
                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="reject" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xs">
                                            <div class="modal-content">
                                                <div class="modal-header py-3">
                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">رد مشخصات بانکی</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <h5 class="IRANYekanRegular">لطفا علت رد کردن مشخصات بانکی را بنویسید.</h5>
                                                    <form action="{{ route('admin.admins.staff.banks.reject',$admin)  }}" method="POST" class="d-inline" id="reject-form">
                                                        <input class="w-100 p-1" name="desc" value="{{ $admin->document->bank_desc ?? '' }}" required maxlength="256">
                                                        @csrf
                                                        @method('PATCH')

                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" title="رد" class="btn btn-danger px-8" form="reject-form">رد</button>
                                                    &nbsp;
                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
