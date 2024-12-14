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
<!--                                {{--                            {{ Breadcrumbs::render('reserves.create') }}--}}-->
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
                                <p class="card-text IRANYekanRegular"> نام و نام خانوادگی:&nbsp; {{ $invoice->reserve->user->getFullName() ?? '' }}</p>
                                <p class="card-text IRANYekanRegular"> شماره تماس:&nbsp; {{ $invoice->reserve->user->mobile ?? '' }}</p>
                                <p class="card-text IRANYekanRegular"> عنوان سرویس:&nbsp; {{ $invoice->reserve->detail_name ?? ''}}</p>
                                <p class="card-text IRANYekanRegular">مبلغ:&nbsp; {{ number_format($invoice->price ?? 0) }}&nbsp;تومان</p>
                            </div>
                            <div class="col-12 col-md-6 text-right">
                                <a href="{{ route('admin.reserves.payment.pos.index',[$reserve,$invoice]) }}"  class="btn btn-danger cursor-pointer text-white">دستگاه پویز</a>
                                <a href="#"  class="btn btn-primary cursor-pointer text-white">کارت به کارت</a>
                                <a href="{{ route('admin.reserves.payment.cash.index',[$reserve,$invoice]) }}"  class="btn btn-success cursor-pointer text-white">نقدی</a>
                                <a href="#"  class="btn btn-warning cursor-pointer text-white">چک</a>
                            </div>
                        </div>
                        @if(count($reserve->confirmedUpgrades))
                            <div class="row mt-2 mb-2">
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
                                            @foreach($reserve->confirmedUpgrades as $index=>$upgrade)
                                                <tr>
                                                    <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                    <td><strong class="IRANYekanRegular">{{ $upgrade->detail_name }}</strong></td>
                                                    <td><strong class="IRANYekanRegular">{{ number_format($upgrade->price) }}</strong></td>
                                                    <td><strong class="IRANYekanRegular">{{ $upgrade->desc ?? '' }}</strong></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12">
                                        <h3 class="card-text IRANYekanRegular">مبلغ کل ارتقاء:&nbsp; {{ number_format($invoice->sum_upgrades_price) }}&nbsp;تومان</h3>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <p class="card-text IRANYekanRegular">تخفیف:&nbsp; {{ number_format($invoice->discount_price ?? 0) }}&nbsp;تومان</p>
                            <p class="card-text IRANYekanRegular"> توضیحات تخفیف:&nbsp; {{ $invoice->discount_description ?? ''}}</p>
                            <h3 class="card-text IRANYekanRegular text-danger">مبلغ قابل پرداخت:&nbsp; {{ number_format($invoice->final_price ?? 0) }}&nbsp;تومان</h3>
                        </div>
                    </div>
                </div>
            </div>

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
