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
                                    {{ Breadcrumbs::render('warehousing.warehouses.orders.index',$warehouse) }}
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <i class="fa fa-cube page-icon"></i>
                                حوالات    {{ $warehouse->name }}
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
                                        @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.create'))
                                            <div class="btn-group" >
                                                <a href="#recive{{ $warehouse->id }}" data-toggle="modal" class="btn btn-primary" title="ایجاد حواله دریافتی">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">ایجاد حواله دریافتی</b>
                                                </a>

                                                <!-- receive Modal -->
                                                <div class="modal fade" id="recive{{ $warehouse->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد حواله دریافتی جدید</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <form action="{{ route('admin.warehousing.warehouses.orders.store',$warehouse) }}"  method="POST" class="d-inline" id="recive">
                                                                    @csrf
                                                                    <input name="event" type="hidden" value="+">
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
                                                                <button type="submit" class="btn btn-primary px-8" title="ثبت" form="recive">ثبت</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="btn-group" >
                                                <a href="#return{{ $warehouse->id }}" data-toggle="modal" class="btn btn-warning" title="ایجاد حواله مرجوعی">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">ایجاد حواله مرجوعی</b>
                                                </a>

                                                <!-- Return Modal -->
                                                <div class="modal fade" id="return{{ $warehouse->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد حواله مرجوعی جدید</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <form action="{{ route('admin.warehousing.warehouses.orders.store',$warehouse) }}"  method="POST" class="d-inline" id="return">
                                                                    @csrf
                                                                    <input name="event" type="hidden" value="-">
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="good" class="col-form-label IRANYekanRegular">کالا</label>
                                                                            <select name="good" id="good"  class="width-100 form-control IRANYekanRegular" required>
                                                                                <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                                @foreach($warehousesGoods as $good)
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
                                                                <button type="submit" class="btn btn-primary px-8" title="ثبت" form="return">ثبت</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="btn-group" >
                                                <a href="#move{{ $warehouse->id }}" data-toggle="modal" class="btn btn-info" title="ایجاد حواله انتقال">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">ایجاد حواله انتقال</b>
                                                </a>

                                                <!-- Move Modal -->
                                                <div class="modal fade" id="move{{ $warehouse->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد حواله انتقال جدید</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <form action="{{ route('admin.warehousing.warehouses.orders.store',$warehouse) }}"  method="POST" class="d-inline" id="order">
                                                                    @csrf
                                                                    <input name="event" type="hidden" value="0">

                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="warehouse" class="col-form-label IRANYekanRegular">ارسال به انبار دیگر</label>
                                                                            <select name="warehouse" id="warehouse"  class="width-100 form-control IRANYekanRegular" onchange="send(this.value,'event')">
                                                                                <option value="">انبار مورد نظر را انتخاب کنید</option>
                                                                                @foreach($warehouses as $ws)
                                                                                    <option value="{{ $ws->id }}" {{$ws->id == old('warehouse')?'selected':'' }}>{{ $ws->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('warehouse') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="good" class="col-form-label IRANYekanRegular">کالا</label>
                                                                            <select name="good" id="good"  class="width-100 form-control IRANYekanRegular" required>
                                                                                <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                                @foreach($warehousesGoods as $good)
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
                                                                <button type="submit" class="btn btn-primary px-8" title="ثبت" form="order">ثبت</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="collapse" id="filter">
                                    <div class="card card-body filter">
                                        <form id="filter-form">
                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="status-filter" class="control-label IRANYekanRegular">کالا</label>
                                                    <select name="goods[]" id="goods-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...   برندها  را انتخاب نمایید">
                                                        @foreach($warehousesGoods as  $good)
                                                            <option value="{{ $good->id }}" @if(request('goods')!=null) {{ in_array($good->id,request('goods'))?'selected':'' }} @endif>{{ $good->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="event-filter" class="control-label IRANYekanRegular">نوع حواله</label>
                                                    <select name="event[]" id="event-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...   برندها  را انتخاب نمایید">
                                                        <option value="+" @if(request('event')!=null) {{ in_array('+',request('event'))?'selected':'' }} @endif>دریافتی</option>
                                                        <option value="-" @if(request('event')!=null) {{ in_array('-',request('event'))?'selected':'' }} @endif>مرجوعی</option>
                                                        <option value="0" @if(request('event')!=null) {{ in_array('0',request('event'))?'selected':'' }} @endif>ارسالی</option>

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
                                                        document.getElementById("name-filter").value = "";
                                                        document.getElementById("brand-filter").value = "";
                                                        document.getElementById("factor-number-filter").value = "";
                                                        document.getElementById("code-filter").value = "";
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
                                                                    <form action="{{ route('admin.warehousing.warehouses.orders.deliver', [$warehouse,$order]) }}"  method="POST" class="d-inline" id="deliver{{ $order->id }}">
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

                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $order->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف حواله</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-text">
                                                                    <p class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این حواله را حذف کنید؟</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.warehousing.warehouses.orders.destroy',[$warehouse,$order]) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger px-8" title="حذف">حذف</button>
                                                                        &nbsp;&nbsp;
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="edit{{ $order->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ویرایش حواله</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-left">
                                                                    <form action="{{ route('admin.warehousing.warehouses.orders.update',[$warehouse,$order]) }}"  method="POST" class="d-inline" id="update{{ $order->id }}">
                                                                        @csrf
                                                                        @method('PATCH')

                                                                        @if($order->event=='0')
                                                                        <div class="form-group row">
                                                                            <div class="col-12">
                                                                                <label for="warehouse{{$order->id}}" class="col-form-label IRANYekanRegular">ارسال به انبار دیگر</label>
                                                                                <select name="warehouse" id="warehouse{{$order->id}}"  class="width-100 form-control IRANYekanRegular">
                                                                                    <option value="">انبار مورد نظر را انتخاب کنید</option>
                                                                                    @foreach($warehouses as $ws)
                                                                                        <option value="{{ $ws->id }}" {{ $ws->id == old('warehouse') || $ws->id ==  $order->moved_warehouse_id ?'selected':'' }}>{{ $ws->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('warehouse') }} </span>
                                                                            </div>
                                                                        </div>
                                                                        @endif

                                                                        <div class="form-group row">
                                                                            <div class="col-12">
                                                                                <label for="good" class="col-form-label IRANYekanRegular">کالا</label>
                                                                                <select name="good" id="good{{$order->id}}"  class="width-100 form-control IRANYekanRegular">
                                                                                    <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                                    @foreach($warehousesGoods as $good)
                                                                                        <option value="{{ $good->id }}" {{$good->id == old('good') || $good->id == $order->goods_id ?'selected':'' }}>{{ $good->title }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <div class="col-12 col-md-6">
                                                                                <label for="count{{$order->id}}" class="control-label IRANYekanRegular">تعداد</label>
                                                                                <input type="number" class="form-control input text-center" name="count" id="count{{$order->id}}" placeholder="تعداد مورد نظر را وارد کنید" value="{{ old('count') ?? $order->countStock()  }}">
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('count') }} </span>
                                                                            </div>

                                                                            <div class="col-12 col-md-6">
                                                                                <label for="value{{$order->id}}" class="control-label IRANYekanRegular">واحد</label>
                                                                                <input type="number" class="form-control input text-center" name="value" id="value{{$order->id}}" placeholder=" حجم واحد مورد نظر را وارد کنید" value="{{ old('value') ?? $order->remainderStock()  }}">
                                                                                <span class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
                                                                            </div>
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success px-8" form="update{{ $order->id }}" title="بروزرسانی">بروزرسانی</button>
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
                                                                @if(is_null($order->delivered_by) &&
                                                                    (in_array(Auth::guard('admin')->id(),$warehouse->admins->pluck('id')->toArray())) &&
                                                                    (($order->event == '+' && !is_null($order->confirmed_by)) || ($order->event == '0' && $order->moved_warehouse_id==$warehouse->id)))
                                                                    @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.delivery'))
                                                                        <a href="#delivery{{ $order->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="تحویل">
                                                                            <i class="ti-thumb-up  text-info"></i>
                                                                            رسید
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                                @if(is_null($order->delivered_by))
                                                                    @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.edit'))
                                                                        <a href="#edit{{ $order->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="ویرایش">
                                                                            <i class="fa fa-edit text-success"></i>
                                                                            ویرایش
                                                                        </a>
                                                                    @endif
                                                                    @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.destroy'))
                                                                        <a href="#remove{{ $order->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="حذف">
                                                                            <i class="fa fa-trash text-danger"></i>
                                                                            حذف
                                                                        </a>
                                                                    @endif
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
