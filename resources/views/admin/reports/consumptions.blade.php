@extends('admin.master')


    @section('script')
        <script type="text/javascript">
            $("#since-filter").MdPersianDateTimePicker({
                targetDateSelector: "#showDate_class",
                targetTextSelector: "#since-filter",
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

            $("#until-filter").MdPersianDateTimePicker({
                targetDateSelector: "#showDate_class",
                targetTextSelector: "#until-filter",
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
                              {{ Breadcrumbs::render('reports.consumptions') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-shopping-cart page-icon"></i>
                             گزارش مواد مصرفی
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
                                <div class="col-12 col-md-11">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>


                                <div class="col-12 col-md-1 text-right">
                                    <div class="btn-group" >
                                        <a href="{{ route('admin.reports.lasers') }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-deaf plusiconfont"></i>
                                            <b class="IRANYekanRegular">لیزر</b>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="collapse" id="filter">
                                <div class="card card-body filter">
                                    <form id="filter-form">
                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="fullname-filter" class="control-label IRANYekanRegular">نام یا نام خانوادگی</label>
                                                <input type="text"  class="form-control input" id="fullname-filter" name="fullname" placeholder="نام یا شماره موبایل را وارد کنید" value="{{ request('fullname') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="res_code" class="control-label IRANYekanRegular">موبایل</label>
                                                <input type="text"  class="form-control input" id="mobile-filter" name="mobile" placeholder="موبایل" value="{{ request('mobile') }}">
                                            </div>

                                        </diV>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="status-filter" class="control-label IRANYekanRegular">کالاها</label>
                                                 <select name="goods[]" id="goods-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... کالاها را انتخاب نمایید">
                                                   @foreach($goods as $good)
                                                    <option value="{{ $good->id }}" @if(request('goods')!=null) {{ in_array($good->id,request('goods'))?'selected':'' }} @endif>{{ $good->title }}</option>
                                                   @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="status-filter" class="control-label IRANYekanRegular">انبارها</label>
                                                <select name="warehouses[]" id="warehouses-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... انبارها را انتخاب نمایید">
                                                    @foreach($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}" @if(request('warehouses')!=null) {{ in_array($warehouse->id,request('warehouses'))?'selected':'' }} @endif>{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </diV>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since-order" class="control-label IRANYekanRegular">زمان مصرف از تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="since-filter" name="since" value="{{ request('since') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since-order" class="control-label IRANYekanRegular">زمان مصرف تا تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="until-filter" name="until" value="{{ request('until') }}" readonly>
                                            </div>
                                        </div>



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
                                                    document.getElementById("fullname-filter").value = "";
                                                    document.getElementById("mobile-filter").value = "";
                                                    document.getElementById("since-filter").value = "";
                                                    document.getElementById("until-filter").value = "";
                                                    $("#goods-filter").val(null).trigger("change");
                                                    $("#warehouses-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">کالا</b></th>
                                            <th><b class="IRANYekanRegular">انبار</b></th>
                                            <th><b class="IRANYekanRegular">مقدار مصرفی</b></th>
                                            <th><b class="IRANYekanRegular">مبلغ واحد (تومان)</b></th>
                                            <th><b class="IRANYekanRegular">مبلغ کل (تومان)</b></th>
                                            <th><b class="IRANYekanRegular">زمان مصرف</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($consumptions as $index=>$consumption)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->reserve->user->getFullName().' ('.$consumption->reserve->user->mobile.')' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->good->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->warehouse->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->value.' '.$consumption->unit }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($consumption->price_per_unit)  }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($consumption->total_price)  }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $consumption->createdAt() }}</strong></td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $consumptions->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
