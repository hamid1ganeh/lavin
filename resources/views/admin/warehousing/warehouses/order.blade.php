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
                                <div class="col-12 text-right">
                                    @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.create'))
                                    <div class="btn-group" >
                                        <a href="#change{{ $warehouse->id }}" data-toggle="modal" class="btn btn-primary" title="ایجاد حواله جدید">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد حواله جدید</b>
                                        </a>

                                        <!-- Change Modal -->
                                        <div class="modal fade" id="change{{ $warehouse->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد حواله جدید</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <form action="{{ route('admin.warehousing.warehouses.orders.store',$warehouse) }}"  method="POST" class="d-inline" id="order">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-12" style="display:inherit;">
                                                                    <input type="radio" id="increase" name="event" value="+" @if(old('event')!= '-') checked @endif>
                                                                    &nbsp;
                                                                    <label for="active" class="IRANYekanRegular">افزودن</label><br>
                                                                    &nbsp;&nbsp; &nbsp;
                                                                    <input type="radio" id="decrease" name="event" value="-" @if(old('event')== '-') checked @endif>
                                                                    &nbsp;
                                                                    <label for="deactive" class="IRANYekanRegular">مرجوعی</label><br>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-12">
                                                                    <label for="good" class="col-form-label IRANYekanRegular">کالا</label>
                                                                    <select name="good" id="good"  class="width-100 form-control select2">
                                                                        <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                        @foreach($goods as $good)
                                                                            <option value="{{ $good->id }}" {{$good->id == old('good')?'selected':'' }}>{{ $good->title }}</option>
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

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">نام کالا</b></th>
                                        <th><b class="IRANYekanRegular">برند</b></th>
                                        <th><b class="IRANYekanRegular">دسته اصلی</b></th>
                                        <th><b class="IRANYekanRegular">دسته فرعی</b></th>
                                        <th><b class="IRANYekanRegular">تعداد</b></th>
                                        <th><b class="IRANYekanRegular">حجم واحد</b></th>
                                        <th><b class="IRANYekanRegular">نوع</b></th>
                                        <th><b class="IRANYekanRegular">تحویل گیرنده</b></th>
                                        <th><b class="IRANYekanRegular">زمان تحویل</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $index=>$order)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->good->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->good->brand ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->good->main_category->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->good->sub_category->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->count }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->value.' '.$order->unit }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->event() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->deliveredBy->fullname ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->delivered_at() }}</strong></td>
                                            <td>
                                                <!-- Delivery Modal -->
                                                <div class="modal fade" id="delivery{{ $order->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">تحویل حواله</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این حواله را تحویل بگیرید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.warehousing.warehouses.orders.deliver', [$warehouse,$order]) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <button type="submit" class="btn btn-info px-8" title="تحویل" >تحویل</button>
                                                                </form>
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
                                                                <form action="{{ route('admin.warehousing.warehouses.orders.update',$warehouse) }}"  method="POST" class="d-inline" id="edit{{ $order->id }}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-12" style="display:inherit;">
                                                                            <input type="radio" id="increase" name="event" value="+" @if(old('event')== '+' || $order->event=='+') checked @endif>
                                                                            &nbsp;
                                                                            <label for="active" class="IRANYekanRegular">افزودن</label><br>
                                                                            &nbsp;&nbsp; &nbsp;
                                                                            <input type="radio" id="decrease" name="event" value="-" @if(old('event')== '-' || $order->event=='-') checked @endif>
                                                                            &nbsp;
                                                                            <label for="deactive" class="IRANYekanRegular">مرجوعی</label><br>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12">
                                                                            <label for="good" class="col-form-label IRANYekanRegular">کالا</label>
                                                                            <select name="good" id="good"  class="width-100 form-control select2">
                                                                                <option value="">کالا مورد نظر را انتخاب کنید</option>
                                                                                @foreach($goods as $good)
                                                                                    <option value="{{ $good->id }}" {{$good->id == old('good') || $good->id == $order->goods_id?'selected':'' }}>{{ $good->title }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('good') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-12 col-md-6">
                                                                            <label for="count" class="control-label IRANYekanRegular">تعداد</label>
                                                                            <input type="number" class="form-control input text-center" name="count" id="count" placeholder="تعداد مورد نظر را وارد کنید" value="{{ old('count') ?? $order->count  }}">
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('count') }} </span>
                                                                        </div>

                                                                        <div class="col-12 col-md-6">
                                                                            <label for="value" class="control-label IRANYekanRegular">واحد</label>
                                                                            <input type="number" class="form-control input text-center" name="value" id="value" placeholder=" حجم واحد مورد نظر را وارد کنید" value="{{ old('value') ?? $order->value  }}">
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('value') }} </span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success px-8" title="بروزرسانی" form="edit{{ $order->id }}">بروزرسانی</button>
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
                                                            @if(is_null($order->delivered_by) && (in_array(Auth::guard('admin')->id(),$warehouse->admins->pluck('id')->toArray())))
                                                                @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.delivery'))
                                                                    <a href="#delivery{{ $order->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="تحویل">
                                                                        <i class="ti-thumb-up  text-info"></i>
                                                                        تحویل
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

@endsection
