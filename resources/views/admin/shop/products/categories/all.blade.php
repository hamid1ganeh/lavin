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
                            {{ Breadcrumbs::render('products.categories') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-layer-group page-icon"></i>
                            دسته بندی محصولات
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
                                <div class="col-6">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                                <div class="col-6 text-right">
                                    @if(Auth::guard('admin')->user()->can('shop.products.categories.create'))
                                    <div class="btn-group" >
                                        <a href="{{ route('admin.shop.products.categories.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد دسته بندی جدید</b>
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
                                                <label for="name" class="control-label IRANYekanRegular">عنوان دسته بندی</label>
                                                <input type="text"  class="form-control input" id="name-filter" name="name" placeholder="عنوان را وارد کنید" value="{{ request('name') }}">
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
                                                    document.getElementById("name-filter").value = "";
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
                                            <th><b class="IRANYekanRegular">نام دسته</b></th>
                                            <th><b class="IRANYekanRegular">تصویر شاخص</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($categories as $index=>$category)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $category->name }}</strong></td>
                                            <td>
                                                <img src="{{ $category->getThumbnail('thumbnail') }}" width="50" height="50" class="rounded-circle my-2 px-2" title="{{ $category->thumbnail->title ?? '' }}" alt="{{ $category->thumbnail->alt ?? '' }}">
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($category->status == App\Enums\Status::Active)
                                                    <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($category->status == App\Enums\Status::Deactive)
                                                    <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>
                                             @if(Auth::guard('admin')->user()->can('shop.products.categories.sub.index'))
                                              <a class="font18 mx-2" href="{{ route('admin.shop.products.categories.sub.index', $category) }}" title="زیر دسته ها">
                                                <i class="fas fa-layer-group text-warning"></i>
                                              </a>
                                              @endif

                                              @if(Auth::guard('admin')->user()->can('shop.products.categories.edit'))
                                              <a class="font18 mx-2" href="{{ route('admin.shop.products.categories.edit', $category) }}" title="ویرایش">
                                                <i class="fa fa-edit text-info"></i>
                                              </a>
                                              @endif

                                              @if(Auth::guard('admin')->user()->can('shop.products.categories.delete'))
                                              <a class="font18 mx-2" href="#remove{{ $category->id }}" data-toggle="modal"  title="حذف">
                                                 <i class="fa fa-trash text-danger"></i>
                                               </a>

                                               <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $category->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف نظر</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید دسته بندی {{ $category->name }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.shop.products.categories.delete', $category) }}"  method="POST" class="d-inline">
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
                                {{ $categories->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
