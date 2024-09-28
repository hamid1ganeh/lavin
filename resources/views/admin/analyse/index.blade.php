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
{{--                            {{ Breadcrumbs::render('galleries') }}--}}
                        </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fa fa-spinner page-icon"></i>
                            سرویس های آنالیز
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                        @if ($errors->any())
                            <div class="row">
                                <div class="col-12 alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li class="IR">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                           @if(Auth::guard('admin')->user()->can('analysis.store'))
                            <div class="row">
                                <div class="col-12">
                                    <div class="btn-group mb-3" >
                                        <a  href="{{ route('admin.analysis.create')  }}"  class="btn btn-sm btn-primary">
                                            <i class="fa fa-spinner  plusiconfont"></i>
                                            <b class="IRANYekanRegular">افزودن آنالیز جدید</b>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">عنوان</b></th>
                                        <th><b class="IRANYekanRegular">حداکثر قیمت</b></th>
                                        <th><b class="IRANYekanRegular">حداقل قیمت</b></th>
                                        <th><b class="IRANYekanRegular">تصویر شاخص</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($analysis as $index=>$analise)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $analise->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $analise->min_price ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $analise->max_price ?? '' }}</strong></td>
                                            <td>
                                                @if($analise->thumbnail != null)
                                                    <img src="{{  $analise->thumbnail->getImagePath('thumbnail') }}" width="40" height="40">
                                                @endIf
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($analise->status == App\Enums\Status::Active)
                                                        <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($analise->status == App\Enums\Status::Deactive)
                                                        <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                                <td>
                                                    @if($analise->trashed())
                                                        @if(Auth::guard('admin')->user()->can('analysis.delete'))
                                                            <a class="font18 m-1" href="#recycle{{ $analise->id }}" data-toggle="modal" title="بازیابی">
                                                                <i class="fa fa-recycle text-danger"></i>
                                                            </a>

                                                            <!-- Recycle Modal -->
                                                            <div class="modal fade" id="recycle{{ $analise->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xs">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header py-3">
                                                                            <h5 class="modal-title IR" id="newReviewLabel">بازیابی سرویس آنالیز</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <h5 class="IRANYekanRegular">آیا مطمئن هستید که می‌خواهید سرویس آنالیز  {{ $analise->title }} را بازیابی نمایید؟</h5>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <form action="{{ route('admin.analysis.recycle', $analise) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('patch')
                                                                                <button type="submit"  title="بازیابی" class="btn btn-info px-8">بازیابی</button>
                                                                            </form>
                                                                            <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else

                                                            <a class="font18 m-1" href="{{ route('admin.analysis.detail', $analise) }}" title="جزئیات">
                                                                <i class="fa fa-eye text-success"></i>
                                                            </a>

                                                        @if(Auth::guard('admin')->user()->can('analysis.edit'))
                                                            <a class="font18 m-1" href="{{ route('admin.analysis.edit', $analise) }}" title="ویرایش">
                                                                <i class="fa fa-edit text-info"></i>
                                                            </a>
                                                        @endif

                                                        @if(Auth::guard('admin')->user()->can('analysis.delete'))
                                                            <a href="#remove{{ $analise->id }}" data-toggle="modal" class="font18 m-1" title="حذف">
                                                                <i class="fa fa-trash text-danger"></i>
                                                            </a>

                                                            <!-- Remove Modal -->
                                                            <div class="modal fade" id="remove{{ $analise->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xs">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header py-3">
                                                                            <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف سرویس آنالیز</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید سرویس آنالیز {{ $analise->title }} را حذف کنید؟</h5>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <form action="{{ route('admin.analysis.destroy', $analise) }}"  method="POST" class="d-inline">
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

                            </div>`

                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div>
</div>
@endsection

