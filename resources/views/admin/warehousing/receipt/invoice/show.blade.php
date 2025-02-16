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
                            پیش نمایش صورتحساب
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
                                <h5 class="card-title IRANYekanRegular">مشخصات سررسید</h5>
                            </div>
                            <div class="col-12 mt-1">
                                 <h3 class="card-text IRANYekanRegular">مبلغ:&nbsp; {{ number_format($receipt->price ?? 0) }}&nbsp;تومان</h3>
                            </div>
                            <div class="col-12 mt-1">
                                <h3 class="card-text IRANYekanRegular">تخفیف:&nbsp; {{ number_format($receipt->discount ?? 0) }}&nbsp;تومان</h3>
                            </div>
                            <div class="col-12 mt-1">
                                <h3 class="card-text IRANYekanRegular">مبلغ قابل پرداخت:&nbsp; {{ number_format($receipt->total_cost ?? 0) }}&nbsp;تومان</h3>
                            </div>
                            <div class="col-12 mt-1">
                                <h3 class="card-text IRANYekanRegular">توضیحات:&nbsp; {{ $receipt->description }}&nbsp</h3>
                            </div>
                        </div>

                        <div class="row">
                            <form class="form-horizontal" action="{{ route('admin.warehousing.receipts.invoice.create',$receipt)  }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="card-body">
                                        <label for="number" class="control-label IRANYekanRegular">شماره فاکتور</label>&nbsp;
                                        <input type="text" class="form-check-input text-right" id="number" name="number" value="{{ old('number') }}" required>
                                        <div class="row mt-2">
                                            <div class="col-sm-12">
                                                <button type="submit" title="ثبت" class="btn btn-primary">صورتحساب پرداخت</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
