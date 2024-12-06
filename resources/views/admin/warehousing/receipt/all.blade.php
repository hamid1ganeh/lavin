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
                                {{ Breadcrumbs::render('warehousing.receipts.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                          <i class="fa fa-file page-icon"></i>
                          رسید های انبار مرکزی
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
                                 @if(Auth::guard('admin')->user()->can('warehousing.goods.receipts.create'))
                                    <div class="btn-group" >
                                            <a href="{{ route('admin.warehousing.receipts.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد رسید جدید</b>
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
                                        <th><b class="IRANYekanRegular">نوع رسید</b></th>
                                        <th><b class="IRANYekanRegular">شماره رسید</b></th>
                                        <th><b class="IRANYekanRegular">طرف حساب</b></th>
                                        <th><b class="IRANYekanRegular">فروشنده</b></th>
                                        <th><b class="IRANYekanRegular">صادرکننده</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ رسید</b></th>
                                        <th><b class="IRANYekanRegular">مبلغ</b></th>
                                        <th><b class="IRANYekanRegular">تخفیف</b></th>
                                        <th><b class="IRANYekanRegular">مبلغ کل</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($receipts as $index=>$receipt)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($receipt->type == App\Enums\ReceiptType::received)
                                                        <span class="badge badge-primary IR p-1">دریافتی</span>
                                                    @elseif($receipt->type == App\Enums\ReceiptType::returned)
                                                        <span class="badge badge-danger IR p-1">مرجوعی</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $receipt->number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $receipt->seller }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ is_null($receipt->user)?'':$receipt->user->getFullName() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $receipt->exporter->fullname ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $receipt->createdAt() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($receipt->price ?? 0) }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($receipt->discount ?? 0) }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($receipt->total_cost ?? 0) }}</strong></td>

                                            <td>
                                                <!-- Info Modal -->
                                                <div class="modal fade" id="info{{ $receipt->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">لیست کالاها</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body IR text-left">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <table class="w-100 IR">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>ردیف</th>
                                                                                <th>کالا</th>
                                                                                <th>تعداد</th>
                                                                                <th>قیمت واحد</th>
                                                                                <th> قیمت کل</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($receipt->goods ?? [] as $index=>$good)
                                                                                <tr>
                                                                                    <td> {{ ++$index }}</td>
                                                                                    <td>{{  $good->good->getGoodInfo()??'' }}</td>
                                                                                    <td>{{ number_format($good->count ?? 0) }}</td>
                                                                                    <td>{{ number_format($good->unit_cost ?? 0) }}</td>
                                                                                    <td>{{ number_format($good->total_cost ?? 0) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" title="بستن" data-dismiss="modal">بستن</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                        <div class="dropdown-menu">
                                                            <a href="#info{{ $receipt->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="لیست کالاها">
                                                                <i class="fa fa-info text-dark font-16 cursor-pointer"></i>
                                                                <span class="p-1">لیست کالاها</span>
                                                            </a>
                                                            @if(Auth::guard('admin')->user()->can('warehousing.goods.receipts.edit'))
                                                            <a class="dropdown-item IR cursor-pointer" href="{{ route('admin.warehousing.receipts.edit',$receipt) }}" title="ویرایش">
                                                                <i class="fa fa-edit text-success font-20"></i>
                                                                ویرایش
                                                            </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    {!! $receipts->render() !!}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
