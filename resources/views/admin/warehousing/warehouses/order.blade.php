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
{{--                                {{ Breadcrumbs::render('warehousing.warehouses.index') }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                          <i class="fa fa-cube page-icon"></i>
                          موجودی حوالات انبار    {{ $warehouse->name }}
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
                                <div class="col-12 text-right">
{{--                                    @if(Auth::guard('admin')->user()->can('warehousing.warehouses.create'))--}}
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
                                                        <form action=""  method="POST" class="d-inline">
                                                            @csrf

                                                            <div class="row">
                                                                <div class="col-12" style="display:inherit;">
                                                                    <input type="radio" id="increase" name="type" value="increase" @if(old('type')!= 'increase') checked @endif>
                                                                    &nbsp;
                                                                    <label for="active" class="IRANYekanRegular">افزودن</label><br>
                                                                    &nbsp;&nbsp; &nbsp;
                                                                    <input type="radio" id="decrease" name="type" value="decrease" @if(old('type')== 'decrease') checked @endif>
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



                                                        </form>
                                                     </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary px-8" title="ثبت">ثبت</button>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
{{--                                   @endif--}}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">نام کالا</b></th>
                                        <th><b class="IRANYekanRegular">دسته اصلی</b></th>
                                        <th><b class="IRANYekanRegular">دسته فرعی</b></th>
                                        <th><b class="IRANYekanRegular">واحد</b></th>
                                        <th><b class="IRANYekanRegular">موجودی</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $index=>$order)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->good->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->good->main_category->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->good->sub_category->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->unit }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $order->stock }}</strong></td>
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
