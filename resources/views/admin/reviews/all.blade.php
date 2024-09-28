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
                            {{ Breadcrumbs::render('reviews') }}
                        </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fa fa-comments page-icon"></i>
                              بازخوردها
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
                                <div class="form-group col-2  text-left">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                                <div class="form-group col-10 text-right">
                                    @if(Auth::guard('admin')->user()->can('reviews.polls'))
                                    <div class="btn-group" >
                                        <a href="{{ route('admin.reviews.polls') }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-comments plusiconfont"></i>
                                            <b class="IRANYekanRegular">نظرسنجی اختصاصی رزروها</b>
                                        </a>
                                    </div>
                                   @endif
                                </div>

                            </div>

                            <div class="collapse" id="filter">
                                <div class="card card-body filter">
                                    <form id="filter-form">
                                        <div class="row">

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="user-filter" class="control-label IRANYekanRegular">کاربر</label>
                                                <input type="text"  class="form-control input" id="user-filter" name="user" placeholder="نام یا نام خانوادگی یا شماره موبایل را وارد کنید" value="{{ request('user') }}">
                                            </div>


                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="categories-filter" class="control-label IRANYekanRegular">دسته بندی</label>
                                                <select name="categories[]" id="categories-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... دسته بندی مورد نظر را انتخاب نمایید">
                                                    <option value="{{ App\Enums\ReviewGroupType::Service }}" @if(request('status')!=null) {{ in_array(App\Enums\ReviewGroupType::Service,request('status'))?'selected':'' }} @endif>سرویس</option>
                                                    <option value="{{ App\Enums\ReviewGroupType::Shop }}" @if(request('status')!=null) {{ in_array(App\Enums\ReviewGroupType::Shop,request('status'))?'selected':'' }} @endif>محصول</option>
                                                    <option value="{{ App\Enums\ReviewGroupType::Adviser }}" @if(request('status')!=null) {{ in_array(App\Enums\ReviewGroupType::Adviser,request('status'))?'selected':'' }} @endif>مشاوره تلفنی</option>
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="admin-filter" class="control-label IRANYekanRegular">ثبت کننده نظر</label>
                                                <select name="admins[]" id="admin-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  ثبت کنندگان  نظر را انتخاب نمایید">
                                                    <option value="0" @if(request('admins')!=null) {{ in_array("0",request('admins'))?'selected':'' }} @endif>کاربر</option>
                                                    @foreach($admins as $admin)
                                                        <option value="{{ $admin->id }}" @if(request('admins')!=null) {{ in_array($admin->id,request('admins'))?'selected':'' }} @endif>{{ $admin->fullname }}</option>
                                                    @endforeach
                                                </select>
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
                                                    document.getElementById("user-filter").value = "";
                                                    $("#admin-filter").val(null).trigger("change");
                                                    $("#categories-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">کاربر</b></th>
                                            <th><b class="IRANYekanRegular">ثبت کننده نظر</b></th>
                                            <th><b class="IRANYekanRegular">دسته بندی</b></th>
                                            <th><b class="IRANYekanRegular">سرویس یا محصول</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($reviews as $index=>$review)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $review->user->firstname??' '}}
                                                    {{ $review->user->lastname??' '}}
                                                </strong>
                                            </td>

                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $review->admin->fullname??' کاربر'}}
                                                </strong>
                                            </td>

                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($review->reviewable_type ==  'App\Models\Product')
                                                    <span class="badge badge-primary IR p-1">محصول</span>
                                                    @elseif($review->reviewable_type == 'App\Models\ServiceReserve')
                                                    <span class="badge badge-success IR p-1">سرویس</span>
                                                    @elseif($review->reviewable_type == 'App\Models\Adviser')
                                                        <span class="badge badge-warning IR p-1">مشاوره تلفنی</span>
                                                    @endif
                                                </strong>
                                            </td>

                                            <td>
                                                @if($review->reviewable_type ==  'App\Models\Product')
                                                    <strong class="IRANYekanRegular">{{ $review->reviewable->name ?? "" }}</strong>
                                                @elseif($review->reviewable_type == 'App\Models\ServiceReserve')
                                                     <strong class="IRANYekanRegular">{{ $review->reviewable->service_name ?? "" }}</strong>
                                                @elseif($review->reviewable_type == 'App\Models\Adviser')
                                                    <strong class="IRANYekanRegular">{{ $review->reviewable->service->name ?? "" }}</strong>
                                                @endif
                                            <td>
                                                <a class="btn  btn-icon font-20" href="#review{{ $review->id }}" data-toggle="modal" title="نظرسنجی">
                                                    <i class="fa fa-comment text-danger cusrsor"></i>
                                                </a>

                                                <div class="modal fade" id="review{{ $review->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">نظرسنجی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">

                                                                    <div class="row">
                                                                        <div class="col-12">

                                                                                @foreach(json_decode($review->reviews,true) as $key=>$value)
                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">{{ str_replace('_',' ',$key) }}</div>
                                                                                    <div class="col-9 text-left  text-nowrap review-rating">
                                                                                        @for($i=0;$i<$value;++$i)
                                                                                        <i class="fa fa-star position-relative text-warning-force" data-tooltip="2"></i>
                                                                                        @endfor
                                                                                    </div>
                                                                                </div>
                                                                                @endforeach

                                                                        </div>

                                                                    </div>

                                                                    <div class="col-12 mt-3">
                                                                        <p class="text-justify IR">{{ $review->content  }}</p>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">بستن</button>
                                                                </div>


                                                            </div>
                                                    </div>
                                                </div>

                                            </td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $reviews->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
