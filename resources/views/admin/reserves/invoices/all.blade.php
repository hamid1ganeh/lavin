@extends('admin.master')


@section('script')
    <script type="text/javascript">
        $("#since-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since-filter",
            textFormat: "yyyy/MM/dd",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: false,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });

        $("#until-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until-filter",
            textFormat: "yyyy/MM/dd",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: false,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });

    </script>
@endsection

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
                            {{ Breadcrumbs::render('accounting.accounts.invoices') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-cash-register"></i>
                             صندوق
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
                                <div class="col-12">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="collapse" id="filter">
                                <div class="card card-body filter">
                                    <form id="filter-form">
                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="user-filter" class="control-label IRANYekanRegular">کاربر</label>
                                                    <input type="text"  class="form-control input" id="user-filter" name="user" placeholder="نام یا نام خانوادگی یا شماره موبایل را وارد کنید" value="{{ request('user') }}">
                                                </div>
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="name" class="control-label IRANYekanRegular">سرویسها</label>
                                                     <select name="services[]" id="service-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سرویس‌های مورد نظر را انتخاب نمایید">
                                                        @foreach($serviceDetails as $service)
                                                        <option value="{{ $service->id }}" @if(request('services')!=null) {{ in_array($service->id,request('services'))?'selected':'' }} @endif>{{ $service->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </diV>

                                            <div class="row">
                                                 <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="code-filter" class="control-label IRANYekanRegular">کد مراجعه</label>
                                                    <input type="text"  class="form-control input text-right" id="code-filter" name="code" placeholder="کد مراجعه را وارد کنید" value="{{ request('code') }}">
                                                </div>
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="name" class="control-label IRANYekanRegular">شعبه ها</label>
                                                    <select name="branches[]" id="branches-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... شعبه های مورد نظر را انتخاب نمایید">
                                                        @foreach($branches as $branch)
                                                            <option value="{{ $branch->id }}" @if(request('branches')!=null) {{ in_array($branch->id,request('branches'))?'selected':'' }} @endif>{{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since-filter" class="control-label IRANYekanRegular">تاریخ ایجاد فاکتور از</label>
                                                <input type="text"   class="form-control text-center" id="since-filter" name="since" value="{{ request('since') }}" readonly>
                                            </div>
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="until-filter" class="control-label IRANYekanRegular">تاریخ ایجاد فاکتور تا</label>
                                                <input type="text"   class="form-control text-center" id="until-filter" name="until" value="{{ request('until') }}" readonly>
                                            </div>
                                        </diV>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="number-filter" class="control-label IRANYekanRegular">شماره فاکتور</label>
                                                <input type="text"  class="form-control input" id="number-filter" name="number" placeholder="شماره فاکتور را وارد کنید" value="{{ request('number') }}">
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
                                                    document.getElementById("user-filter").value = "";
                                                    document.getElementById("code-filter").value = "";
                                                    document.getElementById("number-filter").value = "";
                                                    document.getElementById("since-filter").value = "";
                                                    document.getElementById("until-filter").value = "";
                                                    $("#service-filter").val(null).trigger("change");
                                                    $("#branches-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">کاربر</b></th>
                                            <th><b class="IRANYekanRegular">کد مراجعه</b></th>
                                            <th><b class="IRANYekanRegular">شعبه</b></th>
                                            <th><b class="IRANYekanRegular">سرویس</b></th>
                                            <th><b class="IRANYekanRegular">شماره فاکتور</b></th>
                                            <th><b class="IRANYekanRegular">مبلغ قابل پرداخت</b></th>
                                            <th><b class="IRANYekanRegular">تاریخ ایجاد</b></th>
                                            <th style="width:200px;"><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices as $index=>$invoice)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">
                                                    @if($invoice->reserve->user)
                                                    {{ $invoice->reserve->user->getFullName().' ('.$invoice->reserve->user->mobile.')' }}
                                                    @endif
                                                </strong>
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $invoice->reserve->reception->code ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $invoice->reserve->branch->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $invoice->reserve->detail_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $invoice->number }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($invoice->final_price ?? 0 ) }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $invoice->createdAt() }}</strong></td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                        <div class="dropdown-menu">
                                                             @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.show'))
                                                              <a class="dropdown-item IR cursor-pointer" href="{{ route('admin.reserves.payment.show', $invoice->reserve) }}" title="نمایش صورتحساب" target="_blank">
                                                                  <i class="fas fa-dollar-sign text-dark cursor-pointer"></i>
                                                                <span class="p-1">صورتحساب</span>
                                                              </a>
                                                              @endif

                                                            @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.pos.index'))
                                                            <a class="dropdown-item IR cursor-pointer" href="{{ route('admin.reserves.payment.pos.index', [$invoice->reserve,$invoice]) }}" title="دستگاه پوز" target="_blank">
                                                                <i class="fas fa-dollar-sign text-danger cursor-pointer"></i>
                                                                <span class="p-1">دستگاه پوز</span>
                                                            </a>
                                                            @endif

                                                           @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.card.index'))
                                                            <a class="dropdown-item IR cursor-pointer" href="{{ route('admin.reserves.payment.card.index', [$invoice->reserve,$invoice]) }}" title="کارت به کارت" target="_blank">
                                                                <i class="fas fa-dollar-sign text-primary cursor-pointer"></i>
                                                                <span class="p-1">کارت به کارت</span>
                                                            </a>
                                                            @endif

                                                           @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.cash.index'))
                                                            <a class="dropdown-item IR cursor-pointer" href="{{ route('admin.reserves.payment.cash.index', [$invoice->reserve,$invoice]) }}" title="نقدی" target="_blank">
                                                                <i class="fas fa-dollar-sign text-success cursor-pointer"></i>
                                                                <span class="p-1">نقدی</span>
                                                            </a>
                                                            @endif

                                                            @if(Auth::guard('admin')->user()->can('reserves.payment.invoice.cheque.index'))
                                                            <a class="dropdown-item IR cursor-pointer" href="{{ route('admin.reserves.payment.cheque.index', [$invoice->reserve,$invoice]) }}" title="چک" target="_blank">
                                                                <i class="fas fa-dollar-sign text-warning cursor-pointer"></i>
                                                                <span class="p-1">چک</span>
                                                            </a>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $invoices->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
