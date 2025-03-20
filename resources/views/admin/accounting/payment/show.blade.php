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
                                {{ Breadcrumbs::render('accounting.reception.invoices.show',$reception) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-dollar-sign page-icon"></i>
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
                        @if(count($reserveInvoices))
                            @if(in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()) && !is_null($invoice))
                                <div class="row">
                                    <div class="col-12  text-right">
                                        @if(Auth::guard('admin')->user()->can('invoice.pos.index'))
                                        <a href="{{ route('admin.accounting.reception.invoices.pos.index',[$reception,$invoice]) }}"  class="btn btn-danger cursor-pointer text-white" title="پرداختی های دستگاه پوز">پرداختی های دستگاه پوز</a>
                                        @endif
                                        @if(Auth::guard('admin')->user()->can('invoice.card.index'))
                                        <a href="{{ route('admin.accounting.reception.invoices.card.index',[$reception,$invoice]) }}"  class="btn btn-primary cursor-pointer text-white" title="پرداختی های کارت به کارت">پرداختی های کارت به کارت</a>
                                        @endif
                                        @if(Auth::guard('admin')->user()->can('invoice.cash.index'))
                                        <a href="{{ route('admin.accounting.reception.invoices.cash.index',[$reception,$invoice]) }}"  class="btn btn-success cursor-pointer text-white" title="پرداختی های نقدی">پرداختی های نقدی</a>
                                        @endif
                                        @if(Auth::guard('admin')->user()->can('invoice.cheque.index'))
                                        <a href="{{ route('admin.accounting.reception.invoices.cheque.index',[$reception,$invoice]) }}"  class="btn btn-warning cursor-pointer text-white" title="پرداختی های چک">پرداختی های چک</a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive"  style="min-height: 100px !important;">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">سرویس</b></th>
                                            <th><b class="IRANYekanRegular">قیمت</b></th>
                                            <th><b class="IRANYekanRegular">تخفیف</b></th>
                                            <th><b class="IRANYekanRegular">توضیحات تخفیف</b></th>
                                            <th><b class="IRANYekanRegular">مبلغ ارتقاء</b></th>
                                            <th><b class="IRANYekanRegular">مبلغ کل</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reserveInvoices as $index=>$reserveInvoice)
                                            <tr>
                                                <td>
                                                    @if(Auth::guard('admin')->user()->can('invoice.create'))
                                                    <form method="post" action="{{ route('admin.accounting.reception.invoices.reserve.delete',[$reception,$reserveInvoice]) }}">
                                                        @if(is_null($invoice))
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="ویرایش" class="fa fa-edit text-success button-icon btn-light">
                                                        </button>
                                                        @endif
                                                        <strong class="IRANYekanRegular">{{ ++$index }}</strong>
                                                    </form>
                                                    @endif

                                                </td>
                                                <td><strong class="IRANYekanRegular">{{ $reserveInvoice->reserve->service_name}}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ number_format( $reserveInvoice->price??0) }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ number_format( $reserveInvoice->discount_price??0) }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $reserveInvoice->discount_description ?? '' }}</strong></td>
                                                <td>
                                                    @if(count($reserveInvoice->reserve->upgrades))
                                                        <a href="#upgrade{{ $reserveInvoice->id }}" class="dropdown-item IR cursor" data-toggle="modal" title="لیست ارتقاءها">
                                                            <strong class="IRANYekanRegular">{{ number_format( $reserveInvoice->sum_upgrades_price??0) }}</strong>
                                                        </a>

                                                        <div class="modal fade" id="upgrade{{ $reserveInvoice->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header py-3">
                                                                        <h5 class="modal-title IR" id="newReviewLabel">لیست ارتقاءها</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row mt-2">
                                                                            <div class="col-12">
                                                                                <table class="w-100 IR">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>ردیف</th>
                                                                                        <th>سرویس</th>
                                                                                        <th>مبلغ</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @foreach($reserveInvoice->reserve->upgrades as $row=>$upgrade)
                                                                                        <tr>
                                                                                            <td>{{ ++$row }}</td>
                                                                                            <td>{{ $upgrade->service_name }}</td>
                                                                                            <td>{{ number_format( $upgrade->price??0) }}</td>
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

                                                    @else
                                                        <strong class="IRANYekanRegular">0</strong>

                                                    @endif

                                                </td>
                                                <td><strong class="IRANYekanRegular">{{ number_format( $reserveInvoice->final_price??0) }}</strong></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                            @if(is_null($invoice) && Auth::guard('admin')->user()->can('invoice.create'))
                            <form class="form-horizontal" action="{{ route('admin.accounting.reception.invoices.store',$reception) }}" method="post">
                             @csrf
                            <div class="row">
                                <div class="col-12 col-md-2">
                                    <label for="number" class="control-label IRANYekanRegular">شماره فاکتور</label>&nbsp;
                                    <input type="text" class="form-check-input text-right" id="number" name="number" value="{{ old('number') }}" required>
                                </div>
                                <div class="col-12 col-md-10">
                                    <button type="submit" title="ثبت" class="btn btn-primary">ثبت</button>
                                </div>

                             </div>
                            </form>
                            @endif
                        @endif
                        @if(count($reserves))
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="table-responsive"  style="min-height: 100px !important;">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">سرویس</b></th>
                                            <th><b class="IRANYekanRegular">قیمت</b></th>
                                            <th><b class="IRANYekanRegular">توضیحات</b></th>
                                            <th><b class="IRANYekanRegular">تخفیف</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reserves as $index=>$reserve)
                                            <tr>
                                                <td>
                                                    <strong class="IRANYekanRegular">{{ ++$index }}</strong>
                                                </td>
                                                <td><strong class="IRANYekanRegular">{{ $reserve->detail_name }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ number_format( $reserve->total_price??0) }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $reserve->price_description ?? '' }}</strong></td>
                                                <td style="text-align: right !important;width: 200px;">
                                                    <form class="form-horizontal" action="{{ route('admin.accounting.reception.invoices.reserve.store',$reception) }}" method="post">
                                                        @csrf
                                                        <input name="reserve" type="hidden" value="{{ $reserve->id }}"
                                                        @php  $discounts=$reserve->detail->validUserDiscounts($reserve->user_id) @endphp
                                                            <div class="mt-1">
                                                                <input type="radio" class="form-check-input cursor-pointer" id="code-0" name="discount_code" value="-1" onclick="discount(false,{{ $reserve->id }});" checked>
                                                                <label class="form-check-label ml-3" for="code-1">هیچکدام</label>
                                                            </div>
                                                        @if(count($discounts)>0)
                                                            @foreach($discounts as $index=>$discount)
                                                                <div class="mt-1">
                                                                    <input type="radio" class="form-check-input cursor-pointer" id="code{{ $reserve->id }}" name="discount_code" value="{{ $discount->id }}" onclick="discount(false,{{ $reserve->id }});">
                                                                    <label class="form-check-label ml-3" for="code{{ $reserve->id }}">
                                                                        {{ $discount->code }}
                                                                        @if($discount->unit==App\Enums\DiscountType::percet)
                                                                            {{ ' ('.$discount->value.'درصد)'  }}
                                                                        @elseif($discount->unit==App\Enums\DiscountType::toman)
                                                                            {{ ' ('.$discount->value.' تومان)'  }}
                                                                        @endif
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        <div class="mt-1">
                                                            <input type="radio" class="form-check-input cursor-pointer" id="code{{ $reserve->id }}" name="discount_code" value="0" onclick="discount(true,{{ $reserve->id }});">
                                                            <label class="form-check-label ml-3" for="code0">تخفیف ویژه</label>
                                                            <input class="text-center" type="number"  id="discount_price{{ $reserve->id }}"  name="discount_price" placeholder="مبلغ (تومان)" disabled>
                                                            <input class="text-left" Style="width:250px" type="text" id="discount_description{{ $reserve->id }}" name="discount_description" placeholder="توضیحات" disabled>
                                                        </div>
                                                        <div class="mt-1">
                                                            <button type="submit" title="ثبت" class="btn btn-primary">ثبت</button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                            @foreach($reserve->confirmedUpgrades as $key=>$upgrade)
                                                <tr>
                                                    <td><i class="fas fa-level-up-alt text-info"></i></td>
                                                    <td><strong class="IRANYekanRegular">{{ $upgrade->detail_name }}</strong></td>
                                                    <td><strong class="IRANYekanRegular">{{ number_format( $upgrade->price??0) }}</strong></td>
                                                    <td><strong class="IRANYekanRegular">{{ $upgrade->description ?? '' }}</strong></td>
                                                    <td></td>
                                                    </tr>
                                            @endforeach
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!is_null($invoice))
                            <div class="row">
                                <div class="col-12">
                                    <p class="card-text IRANYekanRegular">شماره فاکتور:&nbsp; {{ $invoice->number}}</p>
                                </div>
                                <div class="col-12">
                                    <p class="card-text IRANYekanRegular"> تاریخ ایجاد فاکتور:&nbsp; {{ $invoice->createdAt()}}</p>
                                </div>
                                <div class="col-12 mt-1">
                                    <h3 class="card-text IRANYekanRegular">مبلغ کل سرویس ها:&nbsp; {{ number_format($invoice->sum_price ?? 0) }}&nbsp;تومان</h3>
                                </div>
                                <div class="col-12 mt-1">
                                    <h3 class="card-text IRANYekanRegular"> مبلغ کل ارتقاء:&nbsp; {{ number_format($invoice->sum_upgrades_price ?? 0) }}&nbsp;تومان</h3>
                                </div>
                                <div class="col-12 mt-1">
                                    <h3 class="card-text IRANYekanRegular"> مبلغ کل تخفیف ها:&nbsp; {{ number_format($invoice->sum_discount_price ?? 0) }}&nbsp;تومان</h3>
                                </div>
                                <div class="col-12 mt-1">
                                    <h3 class="card-text IRANYekanRegular  text-success">مبلغ کل قابل پرداخت&nbsp; {{ number_format($invoice->final_price ?? 0) }}&nbsp;تومان</h3>
                                </div>
                                <div class="col-12 mt-1">
                                    <h3 class="card-text IRANYekanRegular  text-primary">مبلغ کل پرداخت شده:&nbsp; {{ number_format($invoice->amount_paid ?? 0) }}&nbsp;تومان</h3>
                                </div>
                                @if($invoice->amount_debt>0)
                                <div class="col-12 mt-1">
                                    <h3 class="card-text IRANYekanRegular text-danger">مبلغ کل بدهکاری:&nbsp; {{ number_format($invoice->amount_debt ?? 0) }}&nbsp;تومان</h3>
                                </div>
                                @else
                                    <div class="col-12 mt-1">
                                        <h3 class="card-text IRANYekanRegular text-warning">مبلغ کل بستانکاری:&nbsp; {{ number_format((($invoice->amount_debt)*(-1)) ?? 0) }}&nbsp;تومان</h3>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>

                </div>
            </div>

        </div>
    </div>


    <script>
        function discount(costume,id)
        {
            if(costume){
                document.getElementById("discount_price"+id).required = true;
                document.getElementById("discount_description"+id).required = true;
                document.getElementById("discount_price"+id).disabled= false;
                document.getElementById("discount_description"+id).disabled = false;
            }else{
                document.getElementById("discount_price"+id).required = false;
                document.getElementById("discount_description"+id).required = false;
                document.getElementById("discount_price"+id).disabled= true;
                document.getElementById("discount_description"+id).disabled = true;
                document.getElementById("discount_price"+id).value = '';
                document.getElementById("discount_description"+id).value  = '';
            }
        }
    </script>
@endsection
