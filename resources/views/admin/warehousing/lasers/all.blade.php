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
{{--                                {{ Breadcrumbs::render('warehousing.goods.index') }}--}}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-shopping-cart page-icon"></i>
                            لیست دستگاههای لیزر
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
                                    @if(Auth::guard('admin')->user()->can('warehousing.goods.create'))
                                    <div class="btn-group" >
                                            <a href="{{ route('admin.warehousing.goods.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد کالا جدید</b>
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
                                        <th><b class="IRANYekanRegular">عنوان</b></th>
                                        <th><b class="IRANYekanRegular">برند</b></th>
                                        <th><b class="IRANYekanRegular">کد</b></th>
                                        <th><b class="IRANYekanRegular">دسته اصلی</b></th>
                                        <th><b class="IRANYekanRegular">دسته فرعی</b></th>
                                        <th><b class="IRANYekanRegular">واحد مصرفی</b></th>
                                        <th><b class="IRANYekanRegular">حجم واحد در هر عدد</b></th>
                                        <th><b class="IRANYekanRegular">موجودی تعداد</b></th>
                                        <th><b class="IRANYekanRegular">موجودی واحد</b></th>
                                        <th><b class="IRANYekanRegular">قیمت واحد مصرفی</b></th>
                                        <th><b class="IRANYekanRegular">تاریخ انقضاء</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($goods as $index=>$good)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->brand }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->code }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->main_category->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->sub_category->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->unit }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->value_per_count }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->count_stock }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->unit_stock }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->price }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->expireDate() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $good->description }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($good->status == App\Enums\Status::Active)
                                                        <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($good->status == App\Enums\Status::Deactive)
                                                        <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>

                                               @if($good->trashed())

                                                    @if(Auth::guard('admin')->user()->can('warehousing.goods.recycle'))
                                                    <a class="font18" href="#recycle{{ $good->id }}" data-toggle="modal" title="بازیابی">
                                                        <i class="fa fa-recycle text-danger"></i>
                                                    </a>
                                                    <!-- Recycle Modal -->
                                                    <div class="modal fade" id="recycle{{ $good->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">بازیابی شغل</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواید این کالا را بازیابی کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.warehousing.goods.recycle', $good) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('patch')
                                                                        <button type="submit" title="بازیابی" class="btn btn-info px-8">بازیابی</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @else
                                                    @if(Auth::guard('admin')->user()->can('warehousing.goods.edit'))
                                                        <a class="btn  btn-icon" href="{{ route('admin.warehousing.goods.edit', $good) }}" title="ویرایش">
                                                            <i class="fa fa-edit text-success font-20"></i>
                                                        </a>
                                                    @endif

                                                    @if(Auth::guard('admin')->user()->can('warehousing.goods.destroy'))
                                                    <a href="#remove{{ $good->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                        <i class="fa fa-trash text-danger font-20"></i>
                                                    </a>

                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $good->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف شغل</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهدی  کالا  {{ $good->title }} را حذف کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.warehousing.goods.destroy', $good) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger px-8" title="حذف" >حذف</button>
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

                                {!! $goods->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
