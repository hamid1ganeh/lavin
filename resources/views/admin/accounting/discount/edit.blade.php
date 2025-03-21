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
                              {{ Breadcrumbs::render('accounting.discounts.edit',$discount) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-percent page-icon"></i>
                            ویرایش کد تخفیف
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="margin:auto">

                                <form class="form-horizontal" action="{{ route('admin.accounting.discounts.update',$discount) }}" method="post">
                                    {{ csrf_field() }}
                                    @method('PATCH')

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="code" class="control-label IRANYekanRegular"> کد تخفیف:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-right" name="code" id="code"  aria-describedby="basic-addon2" placeholder="کد تخفیف" value="{{ old('code') ?? $discount->code }}">
                                                <div class="input-group-append">
                                                  <button class="btn btn-warning" type="button" onclick="create()">ایجاد</button>
                                                </div>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('code') }} </span>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="code" class="control-label IRANYekanRegular">انقضاء:</label>
                                            <div class="input-group">
                                                <input type="text"   class="form-control text-center" id="expire" name="expire"  readonly placeholder="انقضاء"  value="{{ old('expire') ?? $discount->expire!=null?\Morilog\Jalali\CalendarUtils::convertNumbers(\Morilog\Jalali\CalendarUtils::strftime('Y/m/d H:i:s',strtotime($discount->expire))):'' }}">
                                                <div class="input-group-append">
                                                  <button class="btn btn-danger" type="button" onclick="reserexpire()">
                                                  <i class="fa fa-trash"></i>
                                                </button>
                                                </div>
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('	expire') }} </span>
                                        </div>

                                    </div>

                                     <div class="row">
                                        <div class="col-6 col-md-6">
                                            <label for="unit" class="col-form-label IRANYekanRegular">واحد:</label>
                                             <select name="unit" id="unit" class="form-control dropdown IR" onchange="changeUnit(this.value)">
                                                <option value="{{ App\Enums\DiscountType::percet }}" {{ App\Enums\DiscountType::percet==old('unit') || App\Enums\DiscountType::percet==$discount->unit?'selected':'' }}>درصد</option>
                                                <option value="{{ App\Enums\DiscountType::toman }}" {{ App\Enums\DiscountType::toman==old('unit') || App\Enums\DiscountType::toman==$discount->unit?'selected':'' }}>تومان</option>
                                             </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>
                                        </div>

                                        <div class="col-6 col-md-6">
                                            <label for="value" id="valueLabel" class="col-form-label IRANYekanRegular">مبلغ(تومان):</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-right" id="value" name="value"   placeholder="مبلغ یا درصد"  value="{{ old('value') ?? $discount->value }}">
                                            </div>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
                                        </div>

                                    </div>


                                    <div class="row my-2">
                                        <div class="col-12" style="display:inherit;">
                                            <input type="radio" id="active" name="status" value="{{ App\Enums\Status::Active }}" @if(App\Enums\Status::Active==old('status') || App\Enums\Status::Active==$discount->status) checked @endif>
                                            &nbsp;
                                            <label for="active" class="IR">فعال</label><br>
                                            &nbsp;&nbsp; &nbsp;
                                            <input type="radio" id="deactive" name="status" value="{{ App\Enums\Status::Deactive }}" @if(App\Enums\Status::Deactive==old('status') || App\Enums\Status::Deactive==$discount->status) checked @endif>
                                            &nbsp;
                                            <label for="deactive" class="IR">غیرفعال</label><br>
                                        </div>
                                    </div>


                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <button type="submit" title="بروزرسانی" class="btn btn-info">بروزرسانی</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection



@section('script')

    <script type="text/javascript">
        $("#expire").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#expire",
            textFormat: "yyyy/MM/dd HH:mm:ss",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
            console.log(param1);
            }
        });

        function reserexpire()
        {
            document.getElementById('expire').value='';
        }

        function changeUnit(unit)
        {
            var percet = {{ App\Enums\DiscountType::percet }};
            var toman = {{ App\Enums\DiscountType::toman }};

            if(unit==percet)
            {
                document.getElementById('valueLabel').innerHTML ='درصد تخفیف(%)';
                document.getElementById("value").placeholder = "درصد تخفیف(%)";
            }
            else if(unit==toman)
            {
                document.getElementById('valueLabel').innerHTML ='مبلغ تخفیف(تومان)';
                document.getElementById("value").placeholder = "مبلغ تخفیف(تومان)";
            }
        }


    </script>

@endsection
