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
                                    {{ Breadcrumbs::render('warehousing.warehouses.stocks',$warehouse) }}
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <i class="fa fa-cube page-icon"></i>
                                موجودی {{ $warehouse->name }}
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
                                    <div class="col-12 text-right">
                                        @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.create'))
                                            <div class="btn-group">
                                                <a href="#recive{{ $warehouse->id }}" data-toggle="modal"
                                                   class="btn btn-primary" title="ایجاد حواله دریافتی">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">ایجاد حواله دریافتی</b>
                                                </a>

                                                <!-- receive Modal -->
                                                <div class="modal fade" id="recive{{ $warehouse->id }}" tabindex="-1"
                                                     aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular"
                                                                    id="newReviewLabel">ایجاد حواله دریافتی جدید</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <form
                                                                    action="{{ route('admin.warehousing.warehouses.orders.store',$warehouse) }}"
                                                                    method="POST" class="d-inline" id="recive">
                                                                    @csrf
                                                                    <input name="event" type="hidden" value="+">
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="good"
                                                                                   class="col-form-label IRANYekanRegular">کالا</label>
                                                                            <select name="good" id="good"
                                                                                    class="width-100 form-control IRANYekanRegular"
                                                                                    required>
                                                                                <option value="">کالا مورد نظر را انتخاب
                                                                                    کنید
                                                                                </option>
                                                                                @foreach($goods as $good)
                                                                                    <option
                                                                                        value="{{ $good->id }}" {{$good->id == old('good')?'selected':'' }}>{{ $good->showGoodInfo() }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12 col-md-6">
                                                                            <label for="count"
                                                                                   class="control-label IRANYekanRegular">تعداد</label>
                                                                            <input type="number"
                                                                                   class="form-control input text-center"
                                                                                   name="count" id="count"
                                                                                   placeholder="تعداد مورد نظر را وارد کنید"
                                                                                   value="{{ old('count')  }}">
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('count') }} </span>
                                                                        </div>

                                                                        <div class="col-12 col-md-6">
                                                                            <label for="value"
                                                                                   class="control-label IRANYekanRegular">واحد</label>
                                                                            <input type="number"
                                                                                   class="form-control input text-center"
                                                                                   name="value" id="value"
                                                                                   placeholder=" حجم واحد مورد نظر را وارد کنید"
                                                                                   value="{{ old('value')  }}">
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary px-8"
                                                                        title="ثبت" form="recive">ثبت
                                                                </button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary"
                                                                        title="انصراف" data-dismiss="modal">انصراف
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="btn-group">
                                                <a href="#return{{ $warehouse->id }}" data-toggle="modal"
                                                   class="btn btn-danger" title="ایجاد حواله مرجوعی">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">ایجاد حواله مرجوعی</b>
                                                </a>

                                                <!-- Return Modal -->
                                                <div class="modal fade" id="return{{ $warehouse->id }}" tabindex="-1"
                                                     aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular"
                                                                    id="newReviewLabel">ایجاد حواله مرجوعی جدید</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <form
                                                                    action="{{ route('admin.warehousing.warehouses.orders.store',$warehouse) }}"
                                                                    method="POST" class="d-inline" id="return">
                                                                    @csrf
                                                                    <input name="event" type="hidden" value="-">
                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="good"
                                                                                   class="col-form-label IRANYekanRegular">کالا</label>
                                                                            <select name="good" id="good"
                                                                                    class="width-100 form-control IRANYekanRegular"
                                                                                    required>
                                                                                <option value="">کالا مورد نظر را انتخاب
                                                                                    کنید
                                                                                </option>
                                                                                @foreach($warehousesGoods as $good)
                                                                                    <option
                                                                                        value="{{ $good->id }}" {{$good->id == old('good')?'selected':'' }}>{{ $good->showGoodInfo() }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12 col-md-6">
                                                                            <label for="count"
                                                                                   class="control-label IRANYekanRegular">تعداد</label>
                                                                            <input type="number"
                                                                                   class="form-control input text-center"
                                                                                   name="count" id="count"
                                                                                   placeholder="تعداد مورد نظر را وارد کنید"
                                                                                   value="{{ old('count')  }}">
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('count') }} </span>
                                                                        </div>

                                                                        <div class="col-12 col-md-6">
                                                                            <label for="value"
                                                                                   class="control-label IRANYekanRegular">واحد</label>
                                                                            <input type="number"
                                                                                   class="form-control input text-center"
                                                                                   name="value" id="value"
                                                                                   placeholder=" حجم واحد مورد نظر را وارد کنید"
                                                                                   value="{{ old('value')  }}">
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary px-8"
                                                                        title="ثبت" form="return">ثبت
                                                                </button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary"
                                                                        title="انصراف" data-dismiss="modal">انصراف
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="btn-group">
                                                <a href="#move{{ $warehouse->id }}" data-toggle="modal"
                                                   class="btn btn-info" title="ایجاد حواله انتقال">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">ایجاد حواله انتقال</b>
                                                </a>

                                                <!-- Move Modal -->
                                                <div class="modal fade" id="move{{ $warehouse->id }}" tabindex="-1"
                                                     aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular"
                                                                    id="newReviewLabel">ایجاد حواله انتقال جدید</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <form
                                                                    action="{{ route('admin.warehousing.warehouses.orders.store',$warehouse) }}"
                                                                    method="POST" class="d-inline" id="order">
                                                                    @csrf
                                                                    <input name="event" type="hidden" value="0">

                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="warehouse"
                                                                                   class="col-form-label IRANYekanRegular">ارسال
                                                                                به انبار دیگر</label>
                                                                            <select name="warehouse" id="warehouse"
                                                                                    class="width-100 form-control IRANYekanRegular"
                                                                                    onchange="send(this.value,'event')">
                                                                                <option value="">انبار مورد نظر را
                                                                                    انتخاب کنید
                                                                                </option>
                                                                                @foreach($warehouses as $ws)
                                                                                    <option
                                                                                        value="{{ $ws->id }}" {{$ws->id == old('warehouse')?'selected':'' }}>{{ $ws->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('warehouse') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="good"
                                                                                   class="col-form-label IRANYekanRegular">کالا</label>
                                                                            <select name="good" id="good"
                                                                                    class="width-100 form-control IRANYekanRegular"
                                                                                    required>
                                                                                <option value="">کالا مورد نظر را انتخاب
                                                                                    کنید
                                                                                </option>
                                                                                @foreach($warehousesGoods as $good)
                                                                                    <option
                                                                                        value="{{ $good->id }}" {{$good->id == old('good')?'selected':'' }}>{{ $good->showGoodInfo() }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12 col-md-6">
                                                                            <label for="count"
                                                                                   class="control-label IRANYekanRegular">تعداد</label>
                                                                            <input type="number"
                                                                                   class="form-control input text-center"
                                                                                   name="count" id="count"
                                                                                   placeholder="تعداد مورد نظر را وارد کنید"
                                                                                   value="{{ old('count')  }}">
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('count') }} </span>
                                                                        </div>

                                                                        <div class="col-12 col-md-6">
                                                                            <label for="value"
                                                                                   class="control-label IRANYekanRegular">واحد</label>
                                                                            <input type="number"
                                                                                   class="form-control input text-center"
                                                                                   name="value" id="value"
                                                                                   placeholder=" حجم واحد مورد نظر را وارد کنید"
                                                                                   value="{{ old('value')  }}">
                                                                            <span
                                                                                class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary px-8"
                                                                        title="ثبت" form="order">ثبت
                                                                </button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary"
                                                                        title="انصراف" data-dismiss="modal">انصراف
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.index'))
                                            <div class="btn-group">
                                                <a class="btn  btn-warning btn-icon"
                                                   href="{{ route('admin.warehousing.warehouses.orders.index', $warehouse) }}"
                                                   title="حوالات">
                                                    <b class="IRANYekanRegular">حوالات</b>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">نام کالا</b></th>
                                            <th><b class="IRANYekanRegular">برند</b></th>
                                            <th><b class="IRANYekanRegular">دسته اصلی</b></th>
                                            <th><b class="IRANYekanRegular">دسته فرعی</b></th>
                                            <th><b class="IRANYekanRegular">موجودی واحد در هر عدد</b></th>
                                            <th><b class="IRANYekanRegular">موجودی</b></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($stocks as $index=>$stock)
                                            <tr>
                                                <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                                <td><strong
                                                        class="IRANYekanRegular">{{ $stock->good->title ?? '' }}</strong>
                                                </td>
                                                <td><strong
                                                        class="IRANYekanRegular">{{ $stock->good->brand->name ?? '' }}</strong>
                                                </td>
                                                <td><strong
                                                        class="IRANYekanRegular">{{ $stock->good->main_category->title ?? '' }}</strong>
                                                </td>
                                                <td><strong
                                                        class="IRANYekanRegular">{{ $stock->good->sub_category->title ?? '' }}</strong>
                                                </td>
                                                <td><strong
                                                        class="IRANYekanRegular">{{ $stock->good->value_per_count.' '.$stock->good->unit.' در هر عدد ' }}</strong>
                                                </td>
                                                <td><strong
                                                        class="IRANYekanRegular">{{ $stock->stock() ?? '' }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{ $stocks->render() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

@endsection
