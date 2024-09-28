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
                                {{ Breadcrumbs::render('stories.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-image page-icon"></i>
                            استوری ها
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
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>

                                <div class="col-12 text-right">

                                    <div class="btn-group">
                                        <a href="{{ route('admin.highlights.index') }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-image plusiconfont"></i>
                                            <b class="IRANYekanRegular">هایلایتها</b>
                                        </a>
                                    </div>
                                    @if(Auth::guard('admin')->user()->can('highlights.story.create'))
                                    <div class="btn-group" >
                                        <a href="{{ route('admin.stories.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد استوری جدید</b>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="collapse" id="filter">
                                <div class="card card-body filter">
                                    <form id="filter-form">
                                        <div class="row">
                                            <div class="form-group justify-content-center  col-6">
                                                <label for="status-filter" class="control-label IRANYekanRegular">هایلایت ها</label>
                                                <select name="highlights[]" id="highlights-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  هایلایتها را انتخاب نمایید">
                                                    <option value="0" @if(request('highlights')!=null) {{ in_array("0",request('highlights'))?'selected':'' }} @endif>استوری های روزانه</option>
                                                    @foreach($highlights as $highlight)
                                                        <option value="{{ $highlight->id }}" @if(request('highlights')!=null) {{ in_array($highlight->id,request('highlights'))?'selected':'' }} @endif>{{ $highlight->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group justify-content-center col-6">
                                                <label for="name" class="control-label IRANYekanRegular">وضعیت</label>
                                                <select name="status[]" id="status-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  وضعیتها را انتخاب نمایید">
                                                    <option value="{{ App\Enums\Status::Active }}" @if(request('status')!=null) {{ in_array(App\Enums\Status::Active,request('status'))?'selected':'' }} @endif>فعال</option>
                                                    <option value="{{ App\Enums\Status::Deactive }}" @if(request('status')!=null) {{ in_array(App\Enums\Status::Deactive,request('status'))?'selected':'' }} @endif>غیرفعال</option>
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
                                        <th><b class="IRANYekanRegular">عنوان</b></th>
                                        <th><b class="IRANYekanRegular">تصویر</b></th>
                                        <th><b class="IRANYekanRegular">ویدئو</b></th>
                                        <th><b class="IRANYekanRegular">هایلایت</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($stories as $index=>$story)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $story->title }}</strong></td>
                                            <td>
                                                @if(! is_null($story->image))
                                                <a href="{{  $story->image->getImagePath()  }}" class="IRANYekanRegular" target="_blank">
                                                    <img src="{{ $story->image->getImagePath('thumbnail') }}" width="50">
                                                </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if(! is_null($story->link))
                                                <a href="{{  $story->link  }}" class="IRANYekanRegular" target="_blank">
                                                     نمایش
                                                </a>
                                                @else
                                                    <strong class="IRANYekanRegular">ندارد</strong>
                                                @endif
                                            </td>
                                            <td>
                                                @if(is_null($story->highlight_id))
                                                    <strong class="IRANYekanRegular">استوری های روزانه</strong>
                                                @else
                                                    <strong class="IRANYekanRegular">{{ $story->highlight->title  }}</strong>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($story->status == App\Enums\Status::Active)
                                                        <span class="badge badge-primary IR p-1">فعال</span>
                                                    @elseif($story->status == App\Enums\Status::Deactive)
                                                        <span class="badge badge-danger IR p-1">غیرفعال</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>


                                                @if(Auth::guard('admin')->user()->can('highlights.story.edit'))
                                                <a class="btn  btn-icon" href="{{ route('admin.stories.edit',$story) }}" title="ویرایش">
                                                    <i class="fa fa-edit text-success font-20"></i>
                                                </a>
                                                @endif

                                                @if(Auth::guard('admin')->user()->can('highlights.story.delete'))
                                                <a href="#remove{{ $story->id }}" data-toggle="modal" class="btn btn-icon" title="حذف">
                                                    <i class="fa fa-trash text-danger font-20"></i>
                                                </a>
                                                <!-- Remove Modal -->
                                                <div class="modal fade" id="remove{{ $story->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف نظر</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید استوری {{ $story->title }} را حذف کنید؟</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('admin.stories.delete',$story) }}"  method="POST" class="d-inline">
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
