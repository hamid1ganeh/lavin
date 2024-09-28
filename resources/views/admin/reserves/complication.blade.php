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
                                {{ Breadcrumbs::render('reserves.complications',$reserve) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fa fa-exclamation page-icon"></i>
                            عوراض سرویس
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card p-3">
                          <div class="row">
                                <div class="col-12 col-md-4">
                                    <p class="IR">تاریخ ثبت: {{ $complication->register_at() }}</p>
                                    <p class="IR"> نام و نام خانوادگی: {{ $complication->reserve->user->getFullName() }}</p>
                                    <p class="IR">شماره تماس: {{ $complication->reserve->user->mobile }}</p>
                                    <p class="IR">سرویس مربوط: {{ $complication->reserve->service->name }}</p>
                                </div>
                                <div class="col-12 col-md-8">
                                    <p class="IR">توضیحات کاربر:</p>
                                    <p class="IR justify-content-center">{{ $complication->description }}</p>
                                 </div>
                         </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="IR">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class=" text-right">
                                @if(Auth::guard('admin')->user()->can('complications.item.create'))
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-primary" href="#add-complication" data-toggle="modal" title="ایچاد عارضه جدید">
                                        <i class="fa fa-plus plusiconfont"></i>
                                        <b class="IRANYekanRegular">ایجاد عارضه جدید</b>
                                    </a>
                                </div>
                                @endif
                            </div>

                            <div class="modal fade" id="add-complication" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xs">
                                    <div class="modal-content">
                                        <div class="modal-header py-3">
                                            <h5 class="modal-title IR" id="newReviewLabel">ایجاد عارضه جدید</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <form method="post" action="{{ route('admin.reserves.complications.create',$reserve  ) }}" id="registerForm">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group justify-content-center col-12">
                                                        <label for="complications" class="control-label IRANYekanRegular">عوارض</label>
                                                        <select name="complications[]" id="complications-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... عوارض وارد شده را انتخاب کنید">
                                                            @foreach($complicationList as $cp)
                                                                <option value="{{ $cp->id }}" @if(!is_null(old('complications'))) {{ in_array($cp->id,old('complications'))?'selected':'' }} @endif>{{ $cp->title }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('complications') }} </span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group justify-content-center col-12">
                                                        <label for="content" class="control-label IRANYekanRegular">توصیه پزشک</label>
                                                        <textarea class="form-control" row="100" class="form-control" name="prescription" id="prescription" placeholder=" نسخه را وارد کنید...">{{ old('prescription') ?? $complication->prescription }}</textarea>
                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('prescription') }} </span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group justify-content-center col-12">
                                                        <label for="explain" class="control-label IRANYekanRegular">توضیحات</label>
                                                        <textarea class="form-control" row="100" class="form-control" name="explain" id="explain" placeholder=" توضیحات را وارد کنید...">{{ old('explain') ?? $complication->explain }}</textarea>
                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('explain') }} </span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group justify-content-center col-12">
                                                        <label for="complications" class="control-label IRANYekanRegular">وضعیت</label>
                                                        <select name="status" id="status-filter" class="form-control  IRANYekanRegular" >
                                                            <option value="{{ App\Enums\ComplicationStatus::pending }}"   {{  App\Enums\ComplicationStatus::pending == $complication->status ?'selected':'' }} >در انتظار</option>
                                                            <option value="{{ App\Enums\ComplicationStatus::following }}"   {{  App\Enums\ComplicationStatus::following == $complication->status ?'selected':'' }} >درحال پیگیری</option>
                                                            <option value="{{ App\Enums\ComplicationStatus::followed }}"   {{  App\Enums\ComplicationStatus::followed == $complication->status ?'selected':'' }} >پیگیری شده</option>
                                                            <option value="{{ App\Enums\ComplicationStatus::cancel }}"   {{  App\Enums\ComplicationStatus::cancel == $complication->status ?'selected':'' }} >رد شده</option>
                                                            <option value="{{ App\Enums\ComplicationStatus::treatment }}"   {{  App\Enums\ComplicationStatus::treatment == $complication->status ?'selected':'' }} >درمان عارضه</option>
                                                        </select>
                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="registerForm">ثبت</button>
                                            <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                        </div>

                                    </div>
                                </div>
                            </div>



                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><b class="IRANYekanRegular">ردیف</b></th>
                                        <th><b class="IRANYekanRegular">عوارض</b></th>
                                        <th><b class="IRANYekanRegular">نسخه</b></th>
                                        <th><b class="IRANYekanRegular">درمان تزریقی</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th style="width:200px;"><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($complications as $index=>$complication)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td>
                                                @foreach($complication->complications as $cp)
                                                    <span class="badge badge-light-purple IR p-1 m-1">{{ $cp->title }}</span>
                                                @endforeach
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $complication->prescription ?? '' }}</strong></td>
                                            <td>
                                                <a href="{{ route('admin.reserves.index',['complications[]'=>$complication->pluck('id')->toArray()]) }}" target="_blank">
                                                @foreach($complication->reserves as $reserve)
                                                <span class="badge badge-light-info IR p-1 m-1">{{ $reserve->service_name	 }}</span>
                                                @endforeach
                                                </a>
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $complication->explain ?? '' }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @switch($complication->status)
                                                        @case(App\Enums\ComplicationStatus::pending)
                                                            <span class="badge badge-warning IR p-1">درانتظار</span>
                                                            @break
                                                        @case(App\Enums\ComplicationStatus::following)
                                                            <span class="badge badge-info IR p-1">درحال پیگیری</span>
                                                            @break
                                                        @case(App\Enums\ComplicationStatus::followed)
                                                            <span class="badge badge-success IR p-1">پیگیری شده</span>
                                                            @break
                                                        @case(App\Enums\ComplicationStatus::treatment)
                                                            <span class="badge badge-primary IR p-1">درمان عارضه</span>
                                                            @break
                                                        @case(App\Enums\ComplicationStatus::cancel)
                                                            <span class="badge badge-danger IR p-1">رد شده</span>
                                                            @break
                                                    @endswitch
                                                </strong>
                                            </td>

                                            <td>
                                                <div class="modal fade" id="edit{{ $complication->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">ویرایش</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body text-left">
                                                                <form method="post" action="{{ route('admin.reserves.complications.update',[$reserve,$complication]  ) }}" id="updateForm{{$complication->id}}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                        <div class="form-group justify-content-center col-12">
                                                                            <label for="complications" class="control-label IRANYekanRegular">عوارض</label>
                                                                            <select name="complications[]" id="complications{{ $complication->id }}" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... عوارض وارد شده را انتخاب کنید">
                                                                                @foreach($complicationList as $cp)
                                                                                    <option value="{{ $cp->id }}" @if(!is_null($complication->complications)) {{ in_array($cp->id,$complication->complications->pluck('id')->toArray())?'selected':'' }} @endif>{{ $cp->title }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('complications') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group justify-content-center col-12">
                                                                            <label for="content" class="control-label IRANYekanRegular">توصیه پزشک</label>
                                                                            <textarea class="form-control" row="100" class="form-control" name="prescription" id="prescription{{ $complication->id }}" placeholder=" نسخه را وارد کنید...">{{ old('prescription') ?? $complication->prescription }}</textarea>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('prescription') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group justify-content-center col-12">
                                                                            <label for="explain" class="control-label IRANYekanRegular">توضیحات</label>
                                                                            <textarea class="form-control" row="100" class="form-control" name="explain" id="explain{{ $complication->id }}" placeholder=" توضیحات را وارد کنید...">{{ old('explain') ?? $complication->explain }}</textarea>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('explain') }} </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group justify-content-center col-12">
                                                                            <label for="complications" class="control-label IRANYekanRegular">وضعیت</label>
                                                                            <select name="status" id="status{{ $complication->id }}" class="form-control  IRANYekanRegular" >
                                                                                <option value="{{ App\Enums\ComplicationStatus::pending }}"   {{  App\Enums\ComplicationStatus::pending == $complication->status ?'selected':'' }} >در انتظار</option>
                                                                                <option value="{{ App\Enums\ComplicationStatus::following }}"   {{  App\Enums\ComplicationStatus::following == $complication->status ?'selected':'' }} >درحال پیگیری</option>
                                                                                <option value="{{ App\Enums\ComplicationStatus::followed }}"   {{  App\Enums\ComplicationStatus::followed == $complication->status ?'selected':'' }} >پیگیری شده</option>
                                                                                <option value="{{ App\Enums\ComplicationStatus::cancel }}"   {{  App\Enums\ComplicationStatus::cancel == $complication->status ?'selected':'' }} >رد شده</option>
                                                                                <option value="{{ App\Enums\ComplicationStatus::treatment }}"   {{  App\Enums\ComplicationStatus::treatment == $complication->status ?'selected':'' }} >درمان عارضه</option>
                                                                            </select>
                                                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>
                                                                        </div>
                                                                    </div>

                                                                </form>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success mr-1" title="بروزرسانی" form="updateForm{{$complication->id}}">بروزرسانی</button>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="remove{{ $complication->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">حذف</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body text-center">
                                                                <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید این عارضه را حذف نمایید؟</h5>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <form method="post" action="{{ route('admin.reserves.complications.delete',[$reserve,$complication]  ) }}" >
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit" class="btn btn-danger mr-1" title="حذف">حذف</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="reserve{{ $complication->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">رزرو</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body text-left">
                                                                <form method="post" action="{{ route('admin.reserves.complications.reserve',[$reserve,$complication]) }}" id="reserveForm{{ $complication->id }}">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="form-group justify-content-center col-12">
                                                                        <label for="services{{ $complication->id }}" class="control-label IRANYekanRegular">سرویس ها</label>
                                                                        <select name="services[]" id="services{{ $complication->id }}" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سرویس های مورد نظر را انتخاب کنید">
                                                                            @foreach($serviceDetails as $service)
                                                                                <option value="{{ $service->id }}" @if(!is_null(old('services'))) {{ in_array($service->id,$reserveDetails->pluck('id')->toArray())?'selected':'' }} @endif>{{ $service->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('services') }} </span>
                                                                    </div>
                                                                </div>

                                                                </form>
                                                            </div>

                                                            <div class="modal-footer">
                                                               <button type="submit" class="btn btn-primary mr-1" title="حذف" form="reserveForm{{ $complication->id }}">رزرو</button>
                                                                &nbsp;
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                        <div class="dropdown-menu">

                                                            @if(Auth::guard('admin')->user()->can('complications.item.edit'))
                                                             <a class="dropdown-item IR cusrsor" href="#edit{{ $complication->id }}" data-toggle="modal" title="ویرایش">
                                                                <i class="fa fa-edit text-success cusrsor"></i>
                                                                <span class="p-1">ویرایش</span>
                                                            </a>
                                                            @endif

                                                            @if(Auth::guard('admin')->user()->can('complications.item.delete'))
                                                            <a class="dropdown-item IR cusrsor" href="#remove{{ $complication->id }}" data-toggle="modal" title="حذف">
                                                                <i class="fa fa-trash text-danger cusrsor"></i>
                                                                <span class="p-1">حذف</span>
                                                            </a>
                                                            @endif

                                                           @if(Auth::guard('admin')->user()->can('complications.item.reserve'))
                                                            <a class="dropdown-item IR cusrsor" href="#reserve{{ $complication->id }}" data-toggle="modal" title="رزرو">
                                                                <i class="wi wi-time-10 text-info cusrsor"></i>
                                                                <span class="p-1">رزرو</span>
                                                            </a>
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
{{--                            <form action="{{ route('admin.reserves.complications.register',[$reserve,$complication]) }}" method="post">--}}
{{--                                @csrf--}}
{{--                                <div class="row">--}}
{{--                                    <div class="form-group justify-content-center col-12">--}}
{{--                                        <label for="complications" class="control-label IRANYekanRegular">عوارض</label>--}}
{{--                                        <select name="complications[]" id="complications-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... عوارض وارد شده را انتخاب کنید">--}}
{{--                                            @foreach($complications as $cp)--}}
{{--                                                <option value="{{ $cp->id }}" @if(count($complication->complications)) {{ in_array($cp->id,$complication->complications->pluck('id')->toArray())?'selected':'' }} @endif>{{ $cp->title }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('complications') }} </span>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group justify-content-center col-12">--}}
{{--                                        <label for="content" class="control-label IRANYekanRegular">توصیه پزشک</label>--}}
{{--                                        <textarea class="form-control" row="100" class="form-control" name="prescription" id="prescription" placeholder=" نسخه را وارد کنید...">{{ old('prescription') ?? $complication->prescription }}</textarea>--}}
{{--                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('prescription') }} </span>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group justify-content-center col-12">--}}
{{--                                        <label for="explain" class="control-label IRANYekanRegular">توضیحات</label>--}}
{{--                                        <textarea class="form-control" row="100" class="form-control" name="explain" id="explain" placeholder=" توضیحات را وارد کنید...">{{ old('explain') ?? $complication->explain }}</textarea>--}}
{{--                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('explain') }} </span>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group justify-content-center col-12">--}}
{{--                                        <label for="complications" class="control-label IRANYekanRegular">وضعیت</label>--}}
{{--                                        <select name="status" id="status-filter" class="form-control  IRANYekanRegular" >--}}
{{--                                            <option value="{{ App\Enums\ComplicationStatus::pending }}"   {{  App\Enums\ComplicationStatus::pending == $complication->status ?'selected':'' }} >در انتظار</option>--}}
{{--                                            <option value="{{ App\Enums\ComplicationStatus::following }}"   {{  App\Enums\ComplicationStatus::following == $complication->status ?'selected':'' }} >درحال پیگیری</option>--}}
{{--                                            <option value="{{ App\Enums\ComplicationStatus::followed }}"   {{  App\Enums\ComplicationStatus::followed == $complication->status ?'selected':'' }} >پیگیری شده</option>--}}
{{--                                            <option value="{{ App\Enums\ComplicationStatus::cancel }}"   {{  App\Enums\ComplicationStatus::cancel == $complication->status ?'selected':'' }} >رد شده</option>--}}
{{--                                            <option value="{{ App\Enums\ComplicationStatus::treatment }}"   {{  App\Enums\ComplicationStatus::treatment == $complication->status ?'selected':'' }} >درمان عارضه</option>--}}
{{--                                        </select>--}}
{{--                                        <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>--}}
{{--                                    </div>--}}

{{--                                    <div class="row mt-2 p-2">--}}
{{--                                        <div class="col-sm-12">--}}
{{--                                            <button type="submit" class="btn btn-primary">ثبت</button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                            </form>--}}
                     </div>
                  </div>
                </div>
            </div>

        </div>
    </div>


@endsection
