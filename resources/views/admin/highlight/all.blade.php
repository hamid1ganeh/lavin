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
                             {{ Breadcrumbs::render('highlights.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-image page-icon"></i>
                              هایلایت ها
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
                                    @if(Auth::guard('admin')->user()->can('highlights.create'))
                                        <div class="btn-group" >
                                            <a href="{{ route('admin.highlights.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-plus plusiconfont"></i>
                                                <b class="IRANYekanRegular">ایجاد هایلایت جدید</b>
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
                                        <th><b class="IRANYekanRegular">تصویر شاخص</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($hilights as $index=>$hilight)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $hilight->title }}</strong></td>
                                            <td>
                                                @if($hilight->thumbnail != null)
                                                    <img src="{{  $hilight->thumbnail->getImagePath('thumbnail') }}" width="40" height="40">
                                                @endIf
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($hilight->status == App\Enums\Status::Active)
                                                        <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($hilight->status == App\Enums\Status::Deactive)
                                                        <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>

                                                @if(Auth::guard('admin')->user()->can('highlights.edit'))
                                                <a class="btn  btn-icon" href="{{ route('admin.highlights.edit', $hilight) }}" title="ویرایش">
                                                    <i class="fa fa-edit text-success font-20"></i>
                                                </a>
                                                @endif

                                                @if(Auth::guard('admin')->user()->can('highlights.edit'))
                                                    <a href="#remove{{ $hilight->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                        <i class="fa fa-trash text-danger font-20"></i>
                                                    </a>
                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $hilight->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف نظر</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید هایلایت {{ $hilight->name }} را حذف کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.highlights.delete', $hilight) }}"  method="POST" class="d-inline">
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
