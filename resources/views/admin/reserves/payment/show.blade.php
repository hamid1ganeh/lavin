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
                                {{--                            {{ Breadcrumbs::render('reserves.create') }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-dollar-sign page-icon"></i>
                            پرداخت
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
                            <div class="col-12">
                                <h5 class="card-title IRANYekanRegular">مشخصات سرویس</h5>
                            </div>
                            <div class="col-12">
                                <p class="card-text IRANYekanRegular"> عنوان سرویس:&nbsp; {{ $reserve->detail_name ?? ''}}</p>
                            </div>
                            <div class="col-12 mt-1">
                                 <h3 class="card-text IRANYekanRegular">مبلغ:&nbsp; {{ number_format($reserve->total_price ?? 0) }}&nbsp;تومان</h3>
                            </div>
                        </div>
                        @if(count($reserve->confirmedUpgrades))
                        <div class="row mt-2">
                            <div class="col-12 text-center">
                                 <h5 class="card-title IRANYekanRegular">لیست ارتقاءها</h5>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive"  style="min-height: 100px !important;">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><b class="IRANYekanRegular">ردیف</b></th>
                                                <th><b class="IRANYekanRegular">سرویس</b></th>
                                                <th><b class="IRANYekanRegular">قیمت</b></th>
                                                <th><b class="IRANYekanRegular">توضیحات</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $SumUpgrades = 0; @endphp
                                            @foreach($reserve->confirmedUpgrades as $index=>$upgrade)
                                                <tr>
                                                    <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                    <td><strong class="IRANYekanRegular">{{ $upgrade->detail_name }}</strong></td>
                                                    <td><strong class="IRANYekanRegular">{{ number_format($upgrade->price) }}</strong></td>
                                                    <td><strong class="IRANYekanRegular">{{ $upgrade->desc ?? '' }}</strong></td>
                                                </tr>
                                                @php $SumUpgrades += $upgrade->price; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12">
                                <h3 class="card-text IRANYekanRegular">مبلغ کل ارتقاء:&nbsp; {{ number_format($SumUpgrades) }}&nbsp;تومان</h3>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>


        <form class="form-horizontal" action="{{ route('admin.reserves.payment.create',$reserve) }}" method="post">
            @csrf
                @if((count($discounts)))
                <div class="row">
                    <div class="card w-100" Style="height: 220px">
                        <div class="card-body">
                            <h5 class="card-title IRANYekanRegular">تخفیف</h5>
                            <div class="mt-1">
                                <input type="radio" class="form-check-input cursor-pointer" id="code-1" name="discount_code" value="-1" onclick="discount(-1);" checked>
                                <label class="form-check-label ml-3" for="code-1">هیچکدام</label>
                            </div>
                            <div class="mt-1">
                                <input type="radio" class="form-check-input cursor-pointer" id="code0" name="discount_code" value="0" onclick="discount(0);">
                                <label class="form-check-label ml-3" for="code0">تخفیف ویژه</label>
                                <input class="text-center" type="number"  id="discount_price"  name="discount_price" placeholder="مبلغ (تومان)" disabled>
                                <input class="text-left" Style="width:250px" type="text" id="discount_description" name="discount_description" placeholder="توضیحات" disabled>
                            </div>
                            @foreach($discounts as $index=>$discount)
                            <div class="mt-1">
                                <input type="radio" class="form-check-input cursor-pointer" id="code{{ $discount->id }}" name="discount_code" value="{{ $discount->id }}" onclick="discount({{ $discount->id }});">
                                <label class="form-check-label ml-3" for="code{{ $discount->id }}">
                                    {{ $discount->code }}
                                    @if($discount->unit==App\Enums\DiscountType::percet)
                                       {{ ' ('.$discount->value.'درصد)'  }}
                                    @elseif($discount->unit==App\Enums\DiscountType::toman)
                                        {{ ' ('.$discount->value.' تومان)'  }}
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <button type="submit" title="ثبت" class="btn btn-primary">صورتحساب پرداخت</button>
                    </div>
                </div>
       </form>



    </div>
</div>


<script>
    function discount(id)
    {
        if(id==0){
            document.getElementById("discount_price").required = true;
            document.getElementById("discount_description").required = true;
            document.getElementById("discount_price").disabled= false;
            document.getElementById("discount_description").disabled = false;
        }else{
            document.getElementById("discount_price").required = false;
            document.getElementById("discount_description").required = false;
            document.getElementById("discount_price").disabled= true;
            document.getElementById("discount_description").disabled = true;
            document.getElementById("discount_price").value = '';
            document.getElementById("discount_description").value  = '';
        }
    }
</script>
@endsection
