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
                                {{ Breadcrumbs::render('reserves.payment.invoice.card',$reserve,$invoice) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-dollar-sign page-icon"></i>
                             پرداختی های کارت به کارت
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
                                            <a href="{{ route('admin.reserves.payment.card.create',[$reserve,$invoice]) }}" class="btn btn-sm btn-primary">
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
                                        <th><b class="IRANYekanRegular">شماره تراکنش</b></th>
                                        <th><b class="IRANYekanRegular">حساب واریز شده</b></th>
                                        <th><b class="IRANYekanRegular">مبلغ(تومان)</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ پرداخت</b></th>
                                        <th><b class="IRANYekanRegular">صندوقدار</b></th>
                                        <th><b class="IRANYekanRegular">نام صاحب کارت واریز کننده</b></th>
                                        <th><b class="IRANYekanRegular">شماره کارت واریزی</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $index=>$card)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $card->transaction_number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $card->reciverAccount->bank_name.' ('.$card->reciverAccount->full_name.')' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($card->price) }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $card->paidAt() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $card->cashier->fullname??'' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $card->sender_full_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $card->sender_cart_number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $card->description }}</strong></td>
                                            <td>

{{--                                                @if(Auth::guard('admin')->user()->can('accounting.accounts.edit'))--}}
                                                    <a class="btn  btn-icon" href="{{ route('admin.reserves.payment.card.edit', [$reserve,$invoice,$card]) }}" title="ویرایش">
                                                        <i class="fa fa-edit text-success font-20"></i>
                                                    </a>
{{--                                                @endif--}}

{{--                                                    @if(Auth::guard('admin')->user()->can('accounting.accounts.delete'))--}}
                                                    <a href="#remove{{ $card->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                        <i class="fa fa-trash text-danger font-20"></i>
                                                    </a>
                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $card->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
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
                                                                    <form action="{{ route('admin.reserves.payment.card.destroy', [$reserve,$invoice,$card]) }}"  method="POST" class="d-inline">
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
