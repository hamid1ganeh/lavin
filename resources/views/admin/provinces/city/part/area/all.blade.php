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
                                {{ Breadcrumbs::render('provinces.cities.parts.areas.index',$province,$city,$part) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-map-marker page-icon"></i>
                            محلات  {{ $part->name }}  شهر {{ $city->name }}
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row" style="margin-bottom: 20px;">
                                <div class="col-12 text-right">
{{--                                    @if(Auth::guard('admin')->user()->can('provinces.cities.areas.create'))--}}
                                    <div class="btn-group" >
                                        <a href="{{ route('admin.provinces.cities.parts.areas.create', [$province,$city,$part]) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد محله جدید</b>
                                        </a>
                                    </div>
{{--                                    @endif--}}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">عنوان</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($areas as $index=>$area)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $area->name }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($area->status == App\Enums\Status::Active)
                                                    <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($area->status == App\Enums\Status::Deactive)
                                                    <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>

                                                @if($area->trashed())
                                                    @if(Auth::guard('admin')->user()->can('provinces.cities.parts.areas.delete'))
                                                    <a href="#recycle{{ $area->id }}" data-toggle="modal" class="btn  btn-icon"  title="بازیابی">
                                                        <i class="fa fa-recycle text-danger font-20"></i>
                                                    </a>
                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="recycle{{ $area->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">بازیابی محله</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">  آیا مطمئن هستید که میخواهید این محله را بازیابی نمایید؟ </h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.provinces.cities.parts.areas.recycle',[$province,$city,$part,$area]) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('patch')
                                                                        <button type="submit" title="حذف" class="btn btn-success px-8">بازیابی</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @else
                                                    @if(Auth::guard('admin')->user()->can('provinces.cities.parts.areas.edit'))
                                                      <a class="btn  btn-icon" href="{{ route('admin.provinces.cities.parts.areas.edit',[$province,$city,$part,$area]) }}" title="ویرایش">
                                                        <i class="fa fa-edit text-success font-20"></i>
                                                      </a>
                                                        &nbsp;
                                                     @endif

                                                   @if(Auth::guard('admin')->user()->can('provinces.cities.parts.areas.delete'))
                                                    <a href="#remove{{ $area->id }}" data-toggle="modal" class="btn  btn-icon"  title="حذف">
                                                        <i class="fa fa-trash text-danger font-20"></i>
                                                    </a>
                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $area->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف محله</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">  آیا مطمئن هستید که میخواهید محله {{ $area->name }}   را حذف نمایید؟ </h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.provinces.cities.parts.areas.delete',[$province,$city,$part,$area]) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit" title="حذف" class="btn btn-danger px-8">حذف</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endif

                                            </td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
