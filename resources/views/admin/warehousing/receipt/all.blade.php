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
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                                <div class="col-12 col-md-6 text-right">
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

                            <div class="collapse" id="filter">
                                <div class="card card-body filter">
                                    <form id="filter-form">
                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="number-filter" class="control-label IRANYekanRegular"> شماره رسید</label>
                                                <input type="text" class="form-control input text-right" id="number-filter" name="number" placeholder=" شماره رسید را وارد کنید" value="{{ request('number') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="seller-filter" class="control-label IRANYekanRegular"> طرف حساب </label>
                                                <input type="text"  class="form-control input" id="seller-filter" name="seller" placeholder=" طرف حساب  را وارد کنید" value="{{ request('seller') }}">
                                            </div>

                                            <div class="form-group col-12 col-md-6">
                                                <label for="type-filter" class="control-label IRANYekanRegular">نوع رسید</label>
                                                  <select name="type[]" id="type-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... نوع رسید را انتخاب نمایید">
                                                    <option value="{{ App\Enums\ReceiptType::received }}" {{ !is_null(request('type'))  && in_array(App\Enums\ReceiptType::received,request('type'))?'selected':'' }}>دریافتی</option>
                                                    <option value="{{ App\Enums\ReceiptType::returned }}" {{ !is_null(request('type'))  && in_array(App\Enums\ReceiptType::returned,request('type'))?'selected':'' }}>مرجوعی</option>
                                                </select>
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('type') }} </span>
                                            </div>

                                            <div class="form-group col-12 col-md-6">
                                                <label for="seller-id-filter" class="control-label IRANYekanRegular">فروشنده</label>
                                                <select name="seller_id[]" id="seller-id-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... فروشنده های مورد نظر را انتخاب نمایید">
                                                    @foreach($sellers as $seller)
                                                    <option value="{{ $seller->id }}" {{ !is_null(request('seller_id'))  && in_array($seller->id,request('seller_id'))?'selected':'' }}>{{ $seller->getFullName() }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('seller_id') }} </span>
                                            </div>

                                        </diV>
                                        <div class="form-group col-12 d-flex justify-content-center mt-3">
                                            <button type="submit" class="btn btn-info col-lg-2 offset-lg-4 cursor-pointer">
                                                <i class="fa fa-filter fa-sm"></i>
                                                <span class="pr-2">فیلتر</span>
                                            </button>

                                            <div class="col-lg-2">
                                                <a onclick="reset()" class="btn btn-light border border-secondary cursor-pointer">
                                                    <i class="fas fa-undo fa-sm"></i>
                                                    <span class="pr-2">پاک کردن</span>
                                                </a>
                                            </div>

                                            <script>
                                                function reset()
                                                {
                                                    document.getElementById("number-filter").value = "";
                                                    document.getElementById("seller-filter").value = "";
                                                    $("#type-filter").val(null).trigger("change");
                                                    $("#seller-id-filter").val(null).trigger("change");
                                                }
                                            </script>


                                        </div>
                                    </form>
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
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
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
                                            <td><strong class="IRANYekanRegular">{{ $receipt->description }}</strong></td>
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
                                                                                    <td>@if(!is_null($good->good)) {{  $good->good->getGoodInfo()??'' }} @endif</td>
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
                                                                لیست کالاها
                                                            </a>
                                                            @if(Auth::guard('admin')->user()->can('warehousing.goods.receipts.edit') && is_null($receipt->invoic))
                                                            <a class="dropdown-item IR cursor-pointer" href="{{ route('admin.warehousing.receipts.edit',$receipt) }}" title="ویرایش">
                                                                <i class="fa fa-edit text-success font-20"></i>
                                                                ویرایش
                                                            </a>
                                                            @endif

                                                            <a href="{{ route('admin.warehousing.receipts.invoice.show',$receipt) }}" class="dropdown-item IR cursor-pointer" title="صورتحساب" target="_blank">
                                                                <i class="fas fa-dollar-sign text-primary cursor-pointer"></i>
                                                                صورتحساب
                                                            </a>

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
