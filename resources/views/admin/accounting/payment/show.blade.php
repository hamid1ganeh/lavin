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
                                {{--                           {{ Breadcrumbs::render('reserves.payment.show',$reserve) }}--}}
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
                                                <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $reserveInvoice->reserve->service_name}}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ number_format( $reserveInvoice->price??0) }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ number_format( $reserveInvoice->discount_price??0) }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $reserveInvoice->discount_description ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ number_format( $reserveInvoice->sum_upgrades_price??0) }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ number_format( $reserveInvoice->final_price??0) }}</strong></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                            @if(is_null($invoice))
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
                                                <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
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
                                                {{--                                                @php $SumUpgrades += $upgrade->price; @endphp--}}
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
                                <div class="col-12 mt-1">
                                    <h3 class="card-text IRANYekanRegular text-danger">مبلغ کل نسیه:&nbsp; {{ number_format($invoice->amount_debt ?? 0) }}&nbsp;تومان</h3>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>
            </div>



            {{--            @csrf--}}
            {{--                <div class="row">--}}
            {{--                    <div class="card w-100" Style="height: 220px">--}}
            {{--                        <div class="card-body">--}}
            {{--                            <h5 class="card-title IRANYekanRegular">تخفیف</h5>--}}
            {{--                            <div class="mt-1">--}}
            {{--                                <input type="radio" class="form-check-input cursor-pointer" id="code-1" name="discount_code" value="-1" onclick="discount(-1);" checked>--}}
            {{--                                <label class="form-check-label ml-3" for="code-1">هیچکدام</label>--}}
            {{--                            </div>--}}
            {{--                            <div class="mt-1">--}}
            {{--                                <input type="radio" class="form-check-input cursor-pointer" id="code0" name="discount_code" value="0" onclick="discount(0);">--}}
            {{--                                <label class="form-check-label ml-3" for="code0">تخفیف ویژه</label>--}}
            {{--                                <input class="text-center" type="number"  id="discount_price"  name="discount_price" placeholder="مبلغ (تومان)" disabled>--}}
            {{--                                <input class="text-left" Style="width:250px" type="text" id="discount_description" name="discount_description" placeholder="توضیحات" disabled>--}}
            {{--                            </div>--}}
            {{--                            @if(count($discounts))--}}
            {{--                            @foreach($discounts as $index=>$discount)--}}
            {{--                            <div class="mt-1">--}}
            {{--                                <input type="radio" class="form-check-input cursor-pointer" id="code{{ $discount->id }}" name="discount_code" value="{{ $discount->id }}" onclick="discount({{ $discount->id }});">--}}
            {{--                                <label class="form-check-label ml-3" for="code{{ $discount->id }}">--}}
            {{--                                    {{ $discount->code }}--}}
            {{--                                    @if($discount->unit==App\Enums\DiscountType::percet)--}}
            {{--                                       {{ ' ('.$discount->value.'درصد)'  }}--}}
            {{--                                    @elseif($discount->unit==App\Enums\DiscountType::toman)--}}
            {{--                                        {{ ' ('.$discount->value.' تومان)'  }}--}}
            {{--                                    @endif--}}
            {{--                                </label>--}}
            {{--                            </div>--}}
            {{--                            @endforeach--}}
            {{--                            @endif--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}

{{--                        @if(Auth::guard('admin')->user()->can('reserves.payment.create') &&--}}
{{--                            in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))--}}

{{--                       @endif--}}
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
