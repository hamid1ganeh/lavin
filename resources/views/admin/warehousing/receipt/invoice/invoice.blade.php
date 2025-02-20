@extends('admin.master')

@section('content')

<div class="content-page">

    <div class="content">
        <!-- Start Content-->
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0 IR">
{{--                            {{ Breadcrumbs::render('reserves.payment.invoice',$reserve) }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-file-invoice page-icon"></i>
                            صورتحساب پرداخت
                        </h4>
                    </div>
                </div>
            </div>

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
                <div class="card w-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h5 class="card-title IRANYekanRegular">مشخصات</h5>
                                <p class="card-text IRANYekanRegular"> شماره فاکتور:&nbsp; {{ $invoice->number }}</p>
                                <p class="card-text IRANYekanRegular"> تاریخ ایجاد:&nbsp; {{ $invoice->createdAt() }}</p>
                                <p class="card-text IRANYekanRegular"> طرف حساب:&nbsp; {{ $invoice->receipt->seller ?? '' }}</p>
                                <p class="card-text IRANYekanRegular"> فروشنده:&nbsp; {{ is_null($invoice->receipt->user)?'':$invoice->receipt->user->getFullName() }}</p>
                                <p class="card-text IRANYekanRegular">مبلغ:&nbsp; {{ number_format($invoice->price ?? 0) }}&nbsp;تومان</p>
                            </div>
                            <div class="col-12 col-md-6 text-right">
                                @if(Auth::guard('admin')->user()->can('invoice.pos.index'))
                                <a href="{{ route('admin.warehousing.receipts.invoice.pos.index',[$receipt,$invoice]) }}"  class="btn btn-danger cursor-pointer text-white" title="دستگاه پوز">دستگاه پوز</a>
                                @endif

                                @if(Auth::guard('admin')->user()->can('invoice.card.index'))
                                <a href="{{ route('admin.warehousing.receipts.invoice.card.index',[$receipt,$invoice]) }}"  class="btn btn-primary cursor-pointer text-white" title="کارت به کارت">کارت به کارت</a>
                                @endif

                                @if(Auth::guard('admin')->user()->can('invoice.cash.index'))
                                <a href="{{ route('admin.warehousing.receipts.invoice.cash.index',[$receipt,$invoice]) }}"  class="btn btn-success cursor-pointer text-white" title="نقدی">نقدی</a>
                                @endif

                                @if(Auth::guard('admin')->user()->can('invoice.cheque.index'))
                                <a href="{{ route('admin.warehousing.receipts.invoice.cheque.index',[$receipt,$invoice]) }}"  class="btn btn-warning cursor-pointer text-white" title="چک">چک</a>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p class="card-text IRANYekanRegular">تخفیف:&nbsp; {{ number_format($invoice->discount_price ?? 0) }}&nbsp;تومان</p>
                                <p class="card-text IRANYekanRegular"> توضیحات تخفیف:&nbsp; {{ $invoice->discount_description ?? ''}}</p>
                                <h3 class="card-text IRANYekanRegular">مبلغ قابل پرداخت:&nbsp; {{ number_format($invoice->final_price ?? 0) }}&nbsp;تومان</h3>
                                <h3 class="card-text IRANYekanRegular text-primary">مبلغ پرداخت شده:&nbsp; {{ number_format($sumPaid ?? 0) }}&nbsp;تومان</h3>
    {{--                            @if($remained >= 0)--}}
    {{--                                <h3 class="card-text IRANYekanRegular text-danger">بدهکار:&nbsp; {{ number_format($remained ?? 0) }}&nbsp;تومان</h3>--}}
    {{--                            @else--}}
    {{--                                <h3 class="card-text IRANYekanRegular text-success">بستانکار:&nbsp; {{ number_format($remained ?? 0) }}&nbsp;تومان</h3>--}}
    {{--                            @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
@endsection
