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
                                {{ Breadcrumbs::render('reserves.payment.invoice.cheque',$reserve,$invoice) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-dollar-sign page-icon"></i>
                             پرداختی های چک
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
{{--                                    @if(Auth::guard('admin')->user()->can('accounting.accounts.create'))--}}
                                    <div class="btn-group" >
                                            <a href="{{ route('admin.reserves.payment.cheque.create',[$reserve,$invoice]) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد پرداخت جدید</b>
                                        </a>
                                    </div>
{{--                                   @endif--}}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">نام صادرکننده</b></th>
                                        <th><b class="IRANYekanRegular">کدملی صادرکننده</b></th>
                                        <th><b class="IRANYekanRegular">شماره سریال</b></th>
                                        <th><b class="IRANYekanRegular">مبلغ(تومان)</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ صدرو</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ سررسید</b></th>
                                        <th><b class="IRANYekanRegular">صندوقدار</b></th>
                                        <th><b class="IRANYekanRegular">پاس در حساب</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $index=>$cheque)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->sender_full_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->sender_nation_code }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->serial_number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($cheque->price) }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->dateOfIssue() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->dueDate() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->cashier->fullname??'' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->passedByAccountInfo()  }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $cheque->description }}</strong></td>
                                            <td>

{{--                                                @if(Auth::guard('admin')->user()->can('accounting.accounts.edit'))--}}
                                                    <a class="btn  btn-icon" href="{{ route('admin.reserves.payment.cheque.edit', [$reserve,$invoice,$cheque]) }}" title="ویرایش">
                                                        <i class="fa fa-edit text-success font-20"></i>
                                                    </a>
{{--                                                @endif--}}

{{--                                                    @if(Auth::guard('admin')->user()->can('accounting.accounts.delete'))--}}
                                                    <a href="#remove{{ $cheque->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                        <i class="fa fa-trash text-danger font-20"></i>
                                                    </a>
                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $cheque->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف پرداختی</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که مخواهید این  پرداختی را حذف کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.reserves.payment.cheque.destroy', [$reserve,$invoice,$cheque]) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger px-8" title="حذف" >حذف</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
{{--                                                    @endif--}}
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
