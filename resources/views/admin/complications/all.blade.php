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
                             {{ Breadcrumbs::render('complications.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fa fa-exclamation page-icon"></i>
                             لیست عوارض
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
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>

                                <div class="col-12 col-md-6 text-right">
                                     @if(Auth::guard('admin')->user()->can('complications.index'))
                                        <div class="btn-group" >
                                            <a href="{{ route('admin.complications.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-plus plusiconfont"></i>
                                                <b class="IRANYekanRegular">ایجاد عارضه جدید</b>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="collapse" id="filter">
                                <div class="card card-body filter">
                                    <form id="filter-form">
                                        <div class="row">

                                            <div class="form-group justify-content-center col-6">
                                                <label for="name" class="control-label IRANYekanRegular">عنوان عارضه</label>
                                                <input type="text"  class="form-control input" id="title-filter" name="title" placeholder="عنوان را وارد کنید" value="{{ request('title') }}">
                                            </div>

                                        </diV>

                                        <div class="form-group col-12 d-flex justify-content-center mt-3">

                                            <button type="submit" class="btn btn-info col-lg-2 offset-lg-4 cursor-pointer">
                                                <i class="fa fa-filter fa-sm"></i>
                                                <span class="pr-2">فیلتر</span>
                                            </button>

                                            <div class="col-lg-2">
                                                <a onclick="reset()" class="btn btn-light border border-secondary cursor-pointer">
                                                    <i class="fas fa-undo fa-sm"></i>
                                                    <span class="pr-2">پاک کردن</span>
                                                </a>
                                            </div>


                                            <script>
                                                function reset()
                                                {
                                                    document.getElementById("title-filter").value = "";
                                                }
                                            </script>

                                        </div>
                                    </form>
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

                                        @foreach($complications as $index=>$complication)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $complication->title }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($complication->status == App\Enums\Status::Active)
                                                    <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($complication->status == App\Enums\Status::Deactive)
                                                    <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>

                                              @if(Auth::guard('admin')->user()->can('complications.edit'))
                                              <a class="btn  btn-icon" href="{{ route('admin.complications.edit', $complication) }}" title="ویرایش">
                                                <i class="fa fa-edit text-primary font-20"></i>
                                              </a>
                                              @endif

                                              @if(Auth::guard('admin')->user()->can('complications.delete'))
                                              <a href="#remove{{ $complication->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                 <i class="fa fa-trash text-danger font-20"></i>
                                               </a>

                                               <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $complication->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف نظر</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید عارضه {{ $complication->title }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.complications.delete', $complication) }}"  method="POST" class="d-inline">
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
