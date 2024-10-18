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
                                {{ Breadcrumbs::render('warehousing.lasers.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-deaf page-icon"></i>
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
                                    @if(Auth::guard('admin')->user()->can('warehousing.lasers.create'))
                                    <div class="btn-group" >
                                            <a href="{{ route('admin.warehousing.lasers.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد دستگاه جدید</b>
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
                                        <th><b class="IRANYekanRegular">کد دستگاه</b></th>
                                        <th><b class="IRANYekanRegular">نام</b></th>
                                        <th><b class="IRANYekanRegular">برند</b></th>
                                        <th><b class="IRANYekanRegular">مدل</b></th>
                                        <th><b class="IRANYekanRegular">سال</b></th>
                                        <th><b class="IRANYekanRegular">تیوب</b></th>
                                        <th><b class="IRANYekanRegular">شات موجود</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lasers as $index=>$laser)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $laser->code }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $laser->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $laser->brand }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $laser->model }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $laser->year }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $laser->tube_id }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $laser->shot }}</strong></td>
                                            <td>
                                                @if($laser->trashed())
                                                @if(Auth::guard('admin')->user()->can('warehousing.lasers.recycle'))
                                                    <a href="#recycle{{ $laser->id }}" data-toggle="modal" class="btn btn-icon" title="بازیابی">
                                                        <i class="fa fa-recycle text-danger font-20"></i>
                                                    </a>
                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="recycle{{ $laser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">بازیابی دستگاه لیزر</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این دستگاه لیزر را بازیابی کنید؟ </h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.warehousing.lasers.recycle', $laser) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger px-8" title="بازیابی" >بازیابی</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @else
                                                @if(Auth::guard('admin')->user()->can('warehousing.lasers.edit'))
                                                    <a class="btn  btn-icon" href="{{ route('admin.warehousing.lasers.edit', $laser) }}" title="ویرایش">
                                                        <i class="fa fa-edit text-success font-20"></i>
                                                    </a>
                                                @endif
                                               @if(Auth::guard('admin')->user()->can('warehousing.lasers.destroy'))
                                                <a href="#remove{{ $laser->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                    <i class="fa fa-trash text-danger font-20"></i>
                                                </a>
                                                <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $laser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف دستگاه لیزر</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید  دستگاه لیزر  {{ $laser->name }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.warehousing.lasers.destroy', $laser) }}"  method="POST" class="d-inline">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
