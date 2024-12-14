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
                             {{ Breadcrumbs::render('accounting.accounts.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="mdi mdi-file-document-box page-icon"></i>
                             حساب های مالی
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 text-right">
                                    @if(Auth::guard('admin')->user()->can('accounting.accounts.create'))
                                    <div class="btn-group" >
                                            <a href="{{ route('admin.accounting.accounts.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد حساب جدید</b>
                                        </a>
                                    </div>
                                   @endif
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">نام و نام خانوادگی</b></th>
                                        <th><b class="IRANYekanRegular">نام بانک</b></th>
                                        <th><b class="IRANYekanRegular">نوع حساب</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ افتتاح حساب</b></th>
                                        <th><b class="IRANYekanRegular">شماره حساب</b></th>
                                        <th><b class="IRANYekanRegular">شماره کارت</b></th>
                                        <th><b class="IRANYekanRegular">شماره شبا</b></th>
                                        <th><b class="IRANYekanRegular">دستگاه پوز</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($accounts as $index=>$account)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $account->full_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $account->bank_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $account->account_type }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $account->openedAt() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $account->account_number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $account->cart_number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $account->shaba_number }}</strong></td>
                                            <td>
                                                @if($account->pos)
                                                <i class="fa fa-check-circle text-primary"></i> </td>
                                                @else
                                                <i class="fa fa-times-circle text-danger"></i> </td>
                                                @endif
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($account->status == App\Enums\Status::Active)
                                                        <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($account->status == App\Enums\Status::Deactive)
                                                        <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>
                                               @if($account->trashed())
                                                    @if(Auth::guard('admin')->user()->can('accounting.accounts.recycle'))
                                                    <a class="font18" href="#recycle{{ $account->id }}" data-toggle="modal" title="بازیابی">
                                                        <i class="fa fa-recycle text-danger"></i>
                                                    </a>
                                                    <!-- Recycle Modal -->
                                                    <div class="modal fade" id="recycle{{ $account->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">بازیابی حساب مالی</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواید این حساب مالی را بازیابی کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.accounting.accounts.recycle', $account) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('patch')
                                                                        <button type="submit" title="بازیابی" class="btn btn-info px-8">بازیابی</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                             @else
                                                    @if(Auth::guard('admin')->user()->can('accounting.accounts.edit'))
                                                        <a class="btn  btn-icon" href="{{ route('admin.accounting.accounts.edit', $account) }}" title="ویرایش">
                                                            <i class="fa fa-edit text-success font-20"></i>
                                                        </a>
                                                    @endif

                                                    @if(Auth::guard('admin')->user()->can('accounting.accounts.delete'))
                                                    <a href="#remove{{ $account->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                        <i class="fa fa-trash text-danger font-20"></i>
                                                    </a>
                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $account->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف حساب مالی</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که مخواهید این  حساب مالی را حذف کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.accounting.accounts.destroy', $account) }}"  method="POST" class="d-inline">
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
@endsection
