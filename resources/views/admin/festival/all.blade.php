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
                             {{ Breadcrumbs::render('festivals.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="ti-wand page-icon"></i>
                              جشنواره ها
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
                                        <div class="btn-group" >
                                            <a href="{{ route('admin.festivals.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-plus plusiconfont"></i>
                                                <b class="IRANYekanRegular">ایجاد جشنواره جدید</b>
                                            </a>
                                        </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">عنوان</b></th>
                                        <th><b class="IRANYekanRegular">زمان پایان</b></th>
                                        <th><b class="IRANYekanRegular">نمایش در وبسایت</b></th>
                                        <th><b class="IRANYekanRegular">تصویر شاخص</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($festivals as $index=>$festival)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $festival->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $festival->end() }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">{{ $festival->display?'بلی':'خیر' }}</strong>

                                                @if(!$festival->display)
                                                <a href="#show{{ $festival->id }}" data-toggle="modal" class="btn btn-icon" title="نمایش در وبسایت">
                                                    <i class="fa fa-eye text-success font-20"></i>
                                                </a>

                                                <!-- Show Modal -->
                                                <div class="modal fade" id="show{{ $festival->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">نمایش در وب سایت</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">شما فقط یک جشنواره را میتوانید در وبسایت نمایش دهید.<br>آیا مطمئن هستید که میخواید جشنواره {{ $festival->title }} در وبسایت نمایش داده شود؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.festivals.display', $festival) }}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <button type="submit" class="btn btn-success px-8" title="نمایش" >نمایش</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @endif
                                            </td>
                                            <td>
                                                @if($festival->thumbnail != null)
                                                    <img src="{{  $festival->thumbnail->getImagePath('thumbnail') }}" width="40" height="40">
                                                @endIf
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($festival->status == App\Enums\Status::Active)
                                                        <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($festival->status == App\Enums\Status::Deactive)
                                                        <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>

{{--                                                @if(Auth::guard('admin')->user()->can('branchs.edit'))--}}
                                                <a class="btn  btn-icon" href="{{ route('admin.festivals.edit', $festival) }}" title="ویرایش">
                                                    <i class="fa fa-edit text-success font-20"></i>
                                                </a>
{{--                                                @endif--}}


{{--                                                @if(Auth::guard('admin')->user()->can('branchs.delete'))--}}

                                                    <a href="#remove{{ $festival->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                        <i class="fa fa-trash text-danger font-20"></i>
                                                    </a>

                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $festival->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف نظر</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید جشنواره {{ $festival->title }} را حذف کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.festivals.destroy', $festival) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger px-8" title="حذف" >حذف</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $festivals->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
