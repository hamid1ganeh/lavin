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
            enableTimePicker: true,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });

        $("#until-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until-filter",
            textFormat: "yyyy/MM/d",
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
                                    {{ Breadcrumbs::render('warehousing.orders.index') }}
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <i class="fa fa-cube page-icon"></i>
                                حوالات انبار مرکزی
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
                                    <div class="col-12 col-md-6">
                                        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
{{--                                        @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.create'))--}}
                                            <div class="btn-group" >
                                                <a href="#receive" data-toggle="modal" class="btn btn-primary" title="ایجاد حواله">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">ایجاد حواله</b>
                                                </a>

                                                <!-- receive Modal -->
                                                <div class="modal fade" id="receive" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد حواله  جدید</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <form action="{{ route('admin.warehousing.orders.store') }}"  method="POST" class="d-inline" id="receive-form">
                                                                    @csrf
                                                                    <input name="event" type="hidden" value="+">

                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="warehouse" class="col-form-label IRANYekanRegular">انبار مقصد</label>
                                                                            <select name="warehouse" id="warehouse"  class="width-100 form-control IRANYekanRegular" required>
                                                                                <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                                @foreach($warehouses as $warehouse)
                                                                                    <option value="{{ $warehouse->id }}" {{$warehouse->id == old('good')?'selected':'' }}>{{ $warehouse->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="good" class="col-form-label IRANYekanRegular">کالا</label>
                                                                            <select name="good" id="good"  class="width-100 form-control IRANYekanRegular" required>
                                                                                <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                                @foreach($goods as $good)
                                                                                    <option value="{{ $good->id }}" {{$good->id == old('good')?'selected':'' }}>{{ $good->title.' ('.$good->brand->name.')' }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12 col-md-6">
                                                                            <label for="count" class="control-label IRANYekanRegular">تعداد</label>
                                                                            <input type="number" class="form-control input text-center" name="count" id="count" placeholder="تعداد مورد نظر را وارد کنید" value="{{ old('count')  }}">
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('count') }} </span>
                                                                        </div>

                                                                        <div class="col-12 col-md-6">
                                                                            <label for="value" class="control-label IRANYekanRegular">واحد</label>
                                                                            <input type="number" class="form-control input text-center" name="value" id="value" placeholder=" حجم واحد مورد نظر را وارد کنید" value="{{ old('value')  }}">
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary px-8" title="ثبت" form="receive-form">ثبت</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="collapse" id="filter">
                                    <div class="card card-body filter">
                                        <form id="filter-form">
                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="status-filter" class="control-label IRANYekanRegular">کالا</label>
                                                    <select name="goods[]" id="goods-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...   برندها  را انتخاب نمایید">
                                                        @foreach($goods as  $good)
                                                            <option value="{{ $good->id }}" @if(request('goods')!=null) {{ in_array($good->id,request('goods'))?'selected':'' }} @endif>{{ $good->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="event-filter" class="control-label IRANYekanRegular">نوع حواله</label>
                                                    <select name="event[]" id="event-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...   برندها  را انتخاب نمایید">
                                                        <option value="+" @if(request('event')!=null) {{ in_array('+',request('event'))?'selected':'' }} @endif>دریافتی</option>
                                                        <option value="-" @if(request('event')!=null) {{ in_array('-',request('event'))?'selected':'' }} @endif>مرجوعی</option>
                                                    </select>
                                                </div>
                                            </diV>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="since" class="control-label IRANYekanRegular">از تاریخ</label>
                                                    <input type="text"   class="form-control text-center" id="since-filter" name="since" value="{{ request('since') }}" readonly>
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="since" class="control-label IRANYekanRegular">تا تاریخ</label>
                                                    <input type="text"   class="form-control text-center" id="until-filter" name="until" value="{{ request('until') }}" readonly>
                                                </div>
                                            </diV>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="number-filter" class="control-label IRANYekanRegular">شماره حواله</label>
                                                    <input type="text"  class="form-control input" id="number-filter" name="number" placeholder="شماره حواله را وارد کنید" value="{{ request('number') }}">
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="less-result-filter" class="control-label IRANYekanRegular">نتیجه مغایرت</label>
                                                    <select name="less_result[]" id="less-result-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... نتیجه مغایرت ها انتخاب نمایید">
                                                        <option value="0">تایید شده</option>
                                                        <option value="1">هزینه</option>
                                                        <option value="2">صدور سند مالی</option>
                                                    </select>
                                                    <span class="form-text text-danger erroralarm"> {{ $errors->first('less') }} </span>
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
                                                        document.getElementById("since-filter").value = "";
                                                        document.getElementById("until-filter").value = "";
                                                        document.getElementById("number-filter").value = "";
                                                        $("#goods-filter").val(null).trigger("change");
                                                        $("#event-filter").val(null).trigger("change");
                                                        $("#less-result-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">شماره حواله</b></th>
                                            <th><b class="IRANYekanRegular">نام کالا</b></th>
                                            <th><b class="IRANYekanRegular">برند</b></th>
                                            <th><b class="IRANYekanRegular">دسته اصلی</b></th>
                                            <th><b class="IRANYekanRegular">دسته فرعی</b></th>
                                            <th><b class="IRANYekanRegular">موجودی واحد در هر عدد</b></th>
                                            <th><b class="IRANYekanRegular">موجودی</b></th>
                                            <th><b class="IRANYekanRegular">مغایرت</b></th>
                                            <th><b class="IRANYekanRegular">نتیجه مغایرت</b></th>
                                            <th><b class="IRANYekanRegular">نوع حواله</b></th>
                                            <th><b class="IRANYekanRegular">انبار تحویل گیرنده</b></th>
                                            <th><b class="IRANYekanRegular">ایجاد کننده</b></th>
                                            <th><b class="IRANYekanRegular">تاریخ ایجاد</b></th>
                                            <th><b class="IRANYekanRegular">تحویل گیرنده</b></th>
                                            <th><b class="IRANYekanRegular">زمان تحویل</b></th>
                                            <th><b class="IRANYekanRegular">تایید انبار مرکزی</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $index=>$order)
                                            <tr>
                                                <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->number }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->good->title ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->good->brand->name ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->good->main_category->title ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->good->sub_category->title ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->good->value_per_count.' '.$order->good->unit.' در هر عدد ' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->stock() ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->less() ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->getLessResult() ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->event() }}</strong></td>
                                                <td>

                                                    @if(!is_null($order->moved_warehouse_id) && $order->event == '0')
                                                        <strong class="IRANYekanRegular">{{ $order->movedWarehose->name  }}</strong>
                                                    @elseif($order->event == '+')
                                                        <strong class="IRANYekanRegular">{{ $order->warehouse->name  }}</strong>
                                                    @elseif($order->event == '-')
                                                        <strong class="IRANYekanRegular">انبار مرکزی</strong>
                                                    @endif
                                                </td>
                                                <td><strong class="IRANYekanRegular">{{ $order->createdBy->fullname ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->createdAt() }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->deliveredBy->fullname ?? '' }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $order->deliveredAt() }}</strong></td>
                                                <td>
                                                    @if($order->event != '0')
                                                        @if(is_null($order->confirmed_by))
                                                            <span class="badge badge-danger IR p-1">تایید نشده</span>
                                                        @else
                                                            <span class="badge badge-primary IR p-1">تایید شد</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                <!-- Delivery Modal -->
                                                <div class="modal fade" id="delivery{{ $order->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">رسید حواله</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <form action="{{ route('admin.warehousing.orders.deliver',$order) }}"  method="POST" class="d-inline" id="deliver{{ $order->id }}">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <h5 class="IRANYekanRegular text-danger"> مقدار مغایرت را  وارد کنید</h5>
                                                                    <div class="form-group row">
                                                                        <div class="col-6">
                                                                            <label for="less_count{{$order->id}}" class="col-form-label IRANYekanRegular">تعداد</label>
                                                                            <input type="number" class="form-control input text-center" name="less_count" id="less_count{{$order->id}}" placeholder="مقدار براساس تعداد" value="{{ old('value')  }}">
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('less') }} </span>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label for="less_unit{{$order->id}}" class="col-form-label IRANYekanRegular">{{$order->good->unit}}</label>
                                                                            <input type="number" class="form-control input text-center" name="less_unit" id="less_unit{{$order->id}}" placeholder="مقدار براساس واحد" value="{{ old('value')  }}">
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('less') }} </span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-info px-8" title="تحویل" form="deliver{{ $order->id }}">تحویل</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Less Modal -->
                                                <div class="modal fade" id="less{{ $order->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">تعیین وضعیت مغایرت</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.warehousing.orders.less',$order) }}"  method="POST" class="d-inline" id="less-form{{ $order->id }}">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="less-result{{ $order->id }}" class="col-form-label IRANYekanRegular">نتیجه</label>
                                                                            <select name="result" id="less-result{{ $order->id }}"  class="width-100 form-control IRANYekanRegular" required>
                                                                                <option value="">نتیجه مغایرت  را انتخاب کنید</option>
                                                                                <option value="0">تایید شده</option>
                                                                                <option value="1">هزینه</option>
                                                                                <option value="2">صدور سند مالی</option>
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('result') }} </span>
                                                                        </div>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary px-8" title="ثبت" form="less-form{{ $order->id }}">ثبت</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Confirm Modal -->
                                                <div class="modal fade" id="confirm{{ $order->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">تعیین وضعیت مغایرت</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <h5 class="IRANYekanRegular">آیا انبار مرکزی این حواله را تایید می کند؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.warehousing.orders.confirm',$order) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('patch')
                                                                     <button type="submit" class="btn btn-primary px-8" title="ثبت">ثبت</button>
                                                                </form>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                        <div class="dropdown-menu">
                                                            @if(is_null($order->delivered_by) && $order->event == '-' && !is_null($order->confirmed_by))
                                                                @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.delivery'))
                                                                    <a href="#delivery{{ $order->id }}" data-toggle="modal" class="dropdown-item IR cursor" title="تحویل">
                                                                        <i class="ti-thumb-up  text-info"></i>
                                                                        رسید
                                                                    </a>
                                                                @endif
                                                            @endif

                                                            @if($order->less>0 && !is_null($order->delivered_by) &&
                                                                Auth::guard('admin')->user()->can('warehousing.warehouses.orders.less'))
                                                            <a href="#less{{ $order->id }}" data-toggle="modal" class="dropdown-item IR cursor" title="وضعیت مقایرت">
                                                                <i class="fa fa-window-minimize  text-danger"></i>
                                                                وضعیت مغایرت
                                                            </a>
                                                            @endif

                                                            @if($order->event != '0' && is_null($order->confirmed_by) &&
                                                            Auth::guard('admin')->user()->can('warehousing.warehouses.orders.confirm'))
                                                                <a href="#confirm{{ $order->id }}" data-toggle="modal" class="dropdown-item IR cursor" title="تایید انبار مرکزی">
                                                                    <i class="ti-thumb-up  text-primary"></i>
                                                                    تایید انبار مرکزی
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
                                    {{ $orders->render() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

{{--@section('script')--}}
{{--        <script type="text/javascript">--}}
{{--            function send(value,id)--}}
{{--            {--}}
{{--                if(value=='')--}}
{{--                {--}}
{{--                    document.getElementById(id).value = "+";--}}
{{--                }--}}

{{--                if(value!='')--}}
{{--                {--}}
{{--                    document.getElementById(id).value = "0";--}}
{{--                }--}}

{{--            }--}}

{{--            function order(value,id)--}}
{{--            {--}}
{{--                if(value!='0')--}}
{{--                {--}}
{{--                    document.getElementById(id).value = "";--}}
{{--                    document.getElementById(id).removeAttribute("required");--}}
{{--                }--}}
{{--                else--}}
{{--                {--}}
{{--                    document.getElementById(id).required = true;--}}
{{--                }--}}
{{--            }--}}
{{--        </script>--}}

{{--@endsection--}}
