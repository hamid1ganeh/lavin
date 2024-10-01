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
                          موجودی انبار   {{ $warehouse->name }}
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
                                        <a href="#change{{ $warehouse->id }}" data-toggle="modal" class="btn btn-primary" title="ایجاد تغییرات جدید">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد تغییرات جدید</b>
                                        </a>

                                        <!-- Change Modal -->
                                        <div class="modal fade" id="change{{ $warehouse->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xs">
                                                <div class="modal-content">
                                                    <div class="modal-header py-3">
                                                        <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">ایجاد تغییرات انبار</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <form action=""  method="POST" class="d-inline">
                                                            @csrf
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
                                    @foreach($stocks as $index=>$stock)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->good->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->good->main_category->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->good->sub_category->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->unit }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $warehouse->stock }}</strong></td>
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
