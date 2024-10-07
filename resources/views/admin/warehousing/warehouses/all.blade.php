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
                                {{ Breadcrumbs::render('warehousing.warehouses.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                          <i class="mdi mdi-city page-icon"></i>
                          انبارها
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
                                    @if(Auth::guard('admin')->user()->can('warehousing.warehouses.create'))
                                    <div class="btn-group" >
                                            <a href="{{ route('admin.warehousing.warehouses.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد انبار جدید</b>
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
                                        <th><b class="IRANYekanRegular">نام</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                        <th><b class="IRANYekanRegular">مسئولین</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($warehouses as $index=>$warehouse)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $warehouse->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $warehouse->description }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @foreach($warehouse->admins as $index=>$admin)
                                                        @if($index>0),@endif
                                                        {{ $admin->fullname }}
                                                    @endforeach
                                                </strong>
                                            </td>

                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($warehouse->status == App\Enums\Status::Active)
                                                        <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($warehouse->status == App\Enums\Status::Deactive)
                                                        <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>
                                                <!-- Recycle Modal -->
                                                <div class="modal fade" id="recycle{{ $warehouse->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">بازیابی دسته بندی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواید این انبار را بازیابی کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.warehousing.warehouses.recycle', $warehouse) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <button type="submit" title="بازیابی" class="btn btn-info px-8">بازیابی</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $warehouse->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف انبار</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید انبار  {{ $warehouse->name }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.warehousing.warehouses.destroy', $warehouse) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger px-8" title="حذف" >حذف</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                        <div class="dropdown-menu">
                                                            @if($warehouse->trashed())
                                                                @if(Auth::guard('admin')->user()->can('warehousing.warehouses.recycle'))
                                                                    <a class="dropdown-item IR cusrsor" href="#recycle{{ $warehouse->id }}" data-toggle="modal" title="بازیابی">
                                                                        <i class="fa fa-recycle text-danger"></i>
                                                                        بازیابی
                                                                    </a>
                                                                @endif
                                                            @else
                                                                @if(Auth::guard('admin')->user()->can('warehousing.warehouses.stocks'))
                                                                    <a class="dropdown-item IR cusrsor" href="{{ route('admin.warehousing.warehouses.stocks', $warehouse) }}" title="موجودی">
                                                                        <i class="fa fa-cube text-warning font-20"></i>
                                                                        موجودی
                                                                    </a>
                                                                @endif
                                                                @if(Auth::guard('admin')->user()->can('warehousing.warehouses.edit'))
                                                                    <a class="dropdown-item IR cusrsor" href="{{ route('admin.warehousing.warehouses.edit', $warehouse) }}" title="ویرایش">
                                                                        <i class="fa fa-edit text-success font-20"></i>
                                                                        ویرایش
                                                                    </a>
                                                                @endif
                                                                @if(Auth::guard('admin')->user()->can('warehousing.warehouses.destroy'))
                                                                    <a href="#remove{{ $warehouse->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="حذف">
                                                                        <i class="fa fa-trash text-danger font-20"></i>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
