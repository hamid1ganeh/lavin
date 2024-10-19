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
                           موجودی    {{ $warehouse->name }}
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
                                    @if(Auth::guard('admin')->user()->can('warehousing.warehouses.orders.index'))
                                    <div class="btn-group" >
                                        <div class="btn-group" >
                                            <a class="btn  btn-warning btn-icon" href="{{ route('admin.warehousing.warehouses.orders.index', $warehouse) }}" title="حوالات">
                                                <b class="IRANYekanRegular">حوالات</b>
                                            </a>
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
                                        <th><b class="IRANYekanRegular">نام تیوب</b></th>
                                        <th><b class="IRANYekanRegular">برند تیوب</b></th>
                                        <th><b class="IRANYekanRegular">شات</b></th>
                                        <th><b class="IRANYekanRegular">ضایعات تیوب قبلی</b></th>
                                        <th><b class="IRANYekanRegular">تعوضی کننده</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($histories as $index=>$history)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->good_title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->good_brand ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->good->main_category->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->good->sub_category->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->good->value_per_count.' '.$stock->good->unit.' در هر عدد ' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $stock->stock .' '.$stock->good->unit ?? '' }}</strong></td>
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
