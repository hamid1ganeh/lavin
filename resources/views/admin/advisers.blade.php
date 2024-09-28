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

                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-phone page-icon"></i>
                             لیست مشاوره
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

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

                            <div class="row" style="margin-bottom: 20px;">
                                <div class="col-2">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>

                                <div class="form-group col-10 text-right">
                                    @if(Auth::guard('admin')->user()->can('numbers.create'))
                                        <div class="btn-group" >
                                            <a href="{{ route('admin.numbers.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-plus plusiconfont"></i>
                                                <b class="IRANYekanRegular">افزودن شماره جدید</b>
                                            </a>
                                        </div>
                                    @endif

                                    @if(Auth::guard('admin')->user()->can('numbers.import'))
                                        <div class="btn-group" >
                                            <a href="{{ route('admin.numbers.csv') }}" class="btn btn-sm btn-danger">
                                                <i class="fa fa-file-import plusiconfont"></i>
                                                <b class="IRANYekanRegular">CSV درون ریزی فایل</b>
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
                                                <label for="firstname" class="control-label IRANYekanRegular">نام</label>
                                                <input type="text"  class="form-control input" id="firstname-filter" name="firstname" placeholder="نام  را وارد کنید" value="{{ request('firstname') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="lastname" class="control-label IRANYekanRegular">نام خانوادگی</label>
                                                <input type="text"  class="form-control input" id="lastname-filter" name="lastname" placeholder="نام خانوادگی  را وارد کنید" value="{{ request('lastname') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="mobile-filter" class="control-label IRANYekanRegular">موبایل</label>
                                                <input type="text"  class="form-control input" id="mobile-filter" name="mobile" placeholder="موبایل را وارد کنید" value="{{ request('mobile') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="status" class="control-label IRANYekanRegular">وضعیت</label>
                                                <select name="status[]" id="status-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... وضعیت های مورد نظر را انتخاب نمایید">
                                                    <option value="{{ App\Enums\NumberStatus::NoAction }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::NoAction,request('status'))?'selected':'' }} @endif>بلاتکلیف</option>
                                                    <option value="{{ App\Enums\NumberStatus::Operator }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::Operator,request('status'))?'selected':'' }} @endif>اپراتور</option>
                                                    <option value="{{ App\Enums\NumberStatus::NoAnswer }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::NoAnswer,request('status'))?'selected':'' }} @endif>عدم پاسخگویی</option>
                                                    <option value="{{ App\Enums\NumberStatus::NextNotice }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::NextNotice,request('status'))?'selected':'' }} @endif>اطلاع بعدی</option>
                                                    <option value="{{ App\Enums\NumberStatus::WaitingForAdviser }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::WaitingForAdviser,request('status'))?'selected':'' }} @endif>درخواست مشاور</option>
                                                    <option value="{{ App\Enums\NumberStatus::Adviser }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::Adviser,request('status'))?'selected':'' }} @endif>مشاور</option>
                                                    <option value="{{ App\Enums\NumberStatus::Accept }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::Accept,request('status'))?'selected':'' }} @endif>پذیرش</option>
                                                    <option value="{{ App\Enums\NumberStatus::Cancel }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::Cancel,request('status'))?'selected':'' }} @endif>لغو</option>
                                                    <option value="{{ App\Enums\NumberStatus::WaitnigForDocuments }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::WaitnigForDocuments,request('status'))?'selected':'' }} @endif>در انتظار ارسال مدارک</option>
                                                    <option value="{{ App\Enums\NumberStatus::RecivedDocuments }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::RecivedDocuments,request('status'))?'selected':'' }} @endif>دریافت مدارک</option>
                                                    <option value="{{ App\Enums\NumberStatus::Reservicd }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::Reservicd,request('status'))?'selected':'' }} @endif>رزرو شده</option>
                                                    <option value="{{ App\Enums\NumberStatus::Confirm }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::Confirm,request('status'))?'selected':'' }} @endif>تایید شده</option>
                                                    <option value="{{ App\Enums\NumberStatus::Done }}" @if(request('status')!=null) {{ in_array(App\Enums\NumberStatus::Done,request('status'))?'selected':'' }} @endif>انجام شده</option>
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="type" class="control-label IRANYekanRegular">نوع مشاوره</label>
                                                <select name="type[]" id="type-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... وضعیت های مورد نظر را انتخاب نمایید">
                                                    <option value="0" @if(request('type')!=null) {{ in_array("0",request('type'))?'selected':'' }} @endif>تلفنی</option>
                                                    <option value="1" @if(request('type')!=null) {{ in_array("1",request('type'))?'selected':'' }} @endif>حضوری</option>
                                                </select>
                                            </div>
                                        </div>


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
                                                    document.getElementById("firstname-filter").value = "";
                                                    document.getElementById("lastname-filter").value = "";
                                                    document.getElementById("mobile-filter").value = "";
                                                    $("#status-filter").val(null).trigger("change");
                                                    $("#type-filterr").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">نام و نام خانوادگی </b></th>
                                            <th><b class="IRANYekanRegular">شماره موبایل</b></th>
                                            <th><b class="IRANYekanRegular">سرویس </b></th>
                                            <th><b class="IRANYekanRegular">زمان ارجاع به مشاور</b></th>
                                            <th><b class="IRANYekanRegular">نوع مشاوره</b></th>
                                            <th><b class="IRANYekanRegular"></b>وضعیت</th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($advisers as $index=>$adviser)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index}}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">{{ $adviser->number->firstname." ".$adviser->number->lastname }}</strong>
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">{{ $adviser->number->mobile }}</strong>
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">{{ $adviser->service->name ?? "" }}</strong>
                                                @if($adviser->service->trashed())
                                                <span class="text-danger">(حذف شده)</span>
                                                @elseIf($adviser->service->status == App\Enums\Status::Deactive)
                                                <span class="text-danger">(غیرفعال)</span>
                                                @endIf
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $adviser->adviser_date_time() }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($adviser->in_person)
                                                        <span class="badge badge-warning IR p-1">حضوری</span>
                                                    @else
                                                        <span class="badge badge-success IR p-1">تلفنی</span>
                                                    @endif
                                                </strong>
                                            </td>
                                             <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($adviser->status == App\Enums\NumberStatus::NoAnswer)
                                                    <span class="badge badge-dark IR p-1">عدم پاسخگویی</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::NextNotice)
                                                    <span class="badge badge-light-danger IR p-1">اطلاع بعدی</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::WaitingForAdviser)
                                                    <span class="badge badge-info IR p-1">تعیین مشاوره</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::Adviser)
                                                    <span class="badge badge-info IR p-1">مشاوره</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::Accept)
                                                    <span class="badge badge-success IR p-1">پذیرش</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::Cancel)
                                                    <span class="badge badge-danger IR p-1">لغو</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::WaitnigForDocuments)
                                                    <span class="badge badge-warning IR p-1">در انتظار ارسال مدارک</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::RecivedDocuments)
                                                     <span class="badge badge-warning IR p-1">دریافت مدارک</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::Reservicd)
                                                     <span class="badge badge-success IR p-1">رزرو شده</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::Confirm)
                                                        <span class="badge badge-primary IR p-1">تایید شده</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::Done)
                                                        <span class="badge badge-primary IR p-1">انجام شده</span>
                                                    @endif
                                                </strong>
                                            </td>

                                            <td>
                                                @if(!$adviser->service->trashed() && $adviser->service->status == App\Enums\Status::Active)
                                                <div class="modal fade" id="info{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">اطلاعات</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body IR text-left">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <p>نام: {{ $adviser->number->firstname }}</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p>نام خانوادگی: {{ $adviser->number->lastname }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <p>موبایل: {{ $adviser->number->mobile }}</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p>معرف:
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>مشاور: {{ $adviser->adviser->fullname ?? "" }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>ارنجمنت: {{ $adviser->arrangement->fullname ?? "" }}</p>
                                                                    </div>

                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>اپراتور تلفنی: {{ $adviser->number->operator->fullname ?? "" }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>زمان ارجاع به اپراتور: {{ $adviser->number->operator_date_time() }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>توضیحات اپراتور:<br> {{ $adviser->number->operator_description }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>مشاور تلفنی: {{ $adviser->adviser->fullname ?? "" }}</p>
                                                                    </div>

                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>مدیر مشاوره: {{ $adviser->management->fullname ?? "" }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>زمان ارجاع به مشاور: {{ $adviser->adviser_date_time() }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>توضیحات مشاور:<br> {{ $adviser->adviser_description }}</p>
                                                                    </div>
                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>جشنواره:<br> {{ $adviser->festival->title ?? ''   }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <p> وضعیت:</p>
                                                                        @if($adviser->status == App\Enums\NumberStatus::NoAction)
                                                                        <span class="badge badge-secondary IR p-1">بلاتکلیف</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::Operator)
                                                                        <span class="badge badge-warning IR p-1">اپراتور</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::NoAnswer)
                                                                        <span class="badge badge-dark IR p-1">عدم پاسخگویی</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::NextNotice)
                                                                        <span class="badge badge-light-danger IR p-1">اطلاع بعدی</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::WaitingForAdviser)
                                                                        <span class="badge badge-info IR p-1">تعیین مشاور</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::Adviser)
                                                                        <span class="badge badge-info IR p-1">مشاور</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::Accept)
                                                                        <span class="badge badge-primary IR p-1">پذیرش</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::Cancel)
                                                                        <span class="badge badge-danger IR p-1">لغو</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::Confirm)
                                                                        <span class="badge badge-success IR p-1">تایید شده</span>
                                                                        @elseif($adviser->status == App\Enums\NumberStatus::Done)
                                                                         <span class="badge badge-success IR p-1">انجام شده</span>
                                                                        @endif
                                                                    </div>
                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>زمان پذیرش: {{ $adviser->number->accept_date_time() }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p>توضیحات پذیرش: {{ $adviser->reserve->reception_desc ?? '' }}</p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">بستن</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="adviser-form{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">مشاوره</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body p-3">
                                                                <form method="post" action="{{ route('admin.advisers.update_adviser',$adviser) }}" id="adviser-form-update{{ $adviser->id }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                         <label for="information-adviser" class="col-md-12 col-form-label text-md-left IRANYekanRegular">اطلاعات تکمیلی بیمار:</label>
                                                                        <textarea name="information" id="information-adviser"  placeholder="سایر اطلاعات مشتری">{{ $adviser->number->information ?? "" }}</textarea>

                                                                        <label for="adviser-description" class="col-md-12 col-form-label text-md-left IRANYekanRegular">توضیحات مشاور:</label>
                                                                        <textarea name="adviser_description" id="adviser-description"  placeholder="توضیحات مشاور">{{ $adviser->adviser_description ?? "" }}</textarea>
                                                                    </div>

                                                                    <label for="status-op" class="col-md-12 col-form-label text-md-left IRANYekanRegular">وضعیت:</label>
                                                                    <div class="row">
                                                                          <input type="radio" id="NoAnswer{{ $adviser->id }}" name="status" value="{{ App\Enums\NumberStatus::NoAnswer }}"
                                                                        onchange="clearservice({{ $adviser->id }})"   @if(App\Enums\NumberStatus::NoAnswer==$adviser->status) checked @endIf>
                                                                          <label for="NoAnswer{{ $adviser->id }}" class="IR">عدم پاسخگویی</label>
                                                                    </div>

                                                                    <div class="row">
                                                                          <input type="radio" id="Answer{{ $adviser->id }}" name="status" value="{{ App\Enums\NumberStatus::NextNotice }}"
                                                                        onchange="clearservice({{ $adviser->id }})"  @if(App\Enums\NumberStatus::NextNotice==$adviser->status) checked @endIf>
                                                                          <label for="Answer{{ $adviser->id }}" class="IR"> اطلاع بعدی</label>
                                                                    </div>

                                                                    <div class="row">
                                                                          <input type="radio" id="Accept{{ $adviser->id }}" name="status" value="{{ App\Enums\NumberStatus::Cancel }}" @if(App\Enums\NumberStatus::Cancel==$adviser->status) checked @endIf>
                                                                          <label for="Accept{{ $adviser->id }}" class="IR">عدم تمایل</label>
                                                                    </div>


                                                                    @if(is_null($adviser->service->documents->first()))
                                                                    <div class="row">
                                                                          <input type="radio" id="Accept{{ $adviser->id }}" name="status" value="{{ App\Enums\NumberStatus::Reservicd }}" @if(App\Enums\NumberStatus::Accept==$adviser->status) checked @endIf>
                                                                          <label for="Accept{{ $adviser->id }}" class="IR">رزور</label>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <label for="branch" class="control-label IRANYekanRegular">شعبه</label>
                                                                        <select name="branch" id="branch" class="form-control select2   IRANYekanRegular"   data-placeholder="... شعبه  مربوطه را انتخاب نمایید">
                                                                            @foreach($adviser->service->branches as $branch)
                                                                                <option value="{{ $branch->id }}"  {{ $branch->id==old('branch')?'selected':'' }}>{{ $branch->name  }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="row mt-2">
                                                                        <label for="doctor" class="control-label IRANYekanRegular">پزشک</label>
                                                                        <select name="doctor" class="form-control select2   IRANYekanRegular"   data-placeholder="... پزشک  مربوطه را انتخاب نمایید">
                                                                            @foreach($adviser->service->doctors as $doctor)
                                                                                <option value="{{ $doctor->id }}"  {{ $doctor->id==old('doctor')?'selected':'' }}>{{ $doctor->fullname  }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    @else
                                                                    <div class="row">
                                                                      <input type="radio" id="Accept{{ $adviser->id }}" name="status" value="{{ App\Enums\NumberStatus::Accept }}" @if(App\Enums\NumberStatus::Accept==$adviser->status) checked @endIf>
                                                                      <label for="Accept{{ $adviser->id }}" class="IR">دریافت مدارک</label>
                                                                     </div>
                                                                    @endif


                                                                </form>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="adviser-form-update{{ $adviser->id }}">ثبت</button>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="document{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IRANYekanRegular" id="newReviewLabel"> مدارک مورد نیاز سرویس {{ $adviser->service->name ?? "" }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body" style="padding:2rem !important;">
                                                                @forelse($adviser->service->documents as $document)
                                                                <ul>
                                                                <li class="IRANYekanRegular" style="text-align: initial">{{ $document->title }}</li>
                                                                </ul>
                                                                @empty
                                                                <h5 class="IRANYekanRegular text-danger">برای این سرویس مدارک مورد نیاز تعریف نشده است</h5>
                                                                @endforelse

                                                                @if(!$adviser->service->documents->isEmpty())
                                                                <form action="{{ route('admin.advisers.send_documents', $adviser) }}"  method="POST" class="d-inline" id="send-document{{ $adviser->id }}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        @if($adviser->status == App\Enums\NumberStatus::WaitnigForDocuments)
                                                                        <div class="col-12">
                                                                            <p class="IRANYekanRegular text-danger" style="text-align: initial">پیامک ارسال مدارک قبلا برای کاربر ارسال شده است.</p>
                                                                        </div>
                                                                        @endif
                                                                        <div class="col-12">
                                                                            <p class="IRANYekanRegular text-danger" style="text-align: initial">آیا مطمئن هستید که مدارک فوق به شماره موبایل {{ $adviser->number->mobile }} متعلق به {{ $adviser->number->firstname." ".$adviser->number->lastname }} پیامک شود؟</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12 col-md-6">
                                                                            <label for="name" class="col-md-12 col-form-label text-md-left IRANYekanRegular"> پلتفرم:</label>
                                                                            <input   class="form-control" placeholder="عنوان پلتفرم" name="platform" required>
                                                                        </div>
                                                                        <div class="col-12 col-md-6">
                                                                            <label for="name" class="col-md-12 col-form-label text-md-left IRANYekanRegular"> شناسه:</label>
                                                                            <input   class="form-control" placeholder="شناسه پلتفرم" name="id" ltr required dir="ltr">
                                                                        </div>
                                                                    </div>
                                                               </form>
                                                                @endif

                                                                <form action="{{ route('admin.advisers.recive_documents', $adviser) }}"  method="POST" class="d-inline" id="recive-document{{ $adviser->id }}">
                                                                    @csrf
                                                                    @method('patch')
                                                                </form>

                                                            </div>
                                                            <div class="modal-footer">
                                                                 @if(!$adviser->service->documents->isEmpty())
                                                                 <button type="submit" title="ارسال پیامک" class="btn btn-primary mr-1" form="send-document{{ $adviser->id }}">ارسال پیامک</button>
                                                                 @endif
                                                                @if($adviser->status == App\Enums\NumberStatus::WaitnigForDocuments ||
                                                                    $adviser->status == App\Enums\NumberStatus::Accept ||
                                                                    $adviser->status == App\Enums\NumberStatus::Adviser)
                                                                <button type="submit" title="دریافت مدارک" class="btn btn-warning mr-1" form="recive-document{{ $adviser->id }}">دریافت مدارک</button>
                                                                @endif
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="sms{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">ارسال پیامک</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body p-3">
                                                                <form method="post" action="{{ route('admin.numbers.sms',$adviser->number) }}" id="sms-form{{ $adviser->id }}">
                                                                    @csrf
                                                                    <div class="row">
                                                                         <label for="information-adviser" class="col-md-12 col-form-label text-md-left IRANYekanRegular">متن پیامک:</label>
                                                                        <textarea name="text" id="text"  placeholder="متن پیامک" max="144" require>{{ old('text') }}</textarea>

                                                                        <label for="link" class="col-md-12 col-form-label text-md-left IRANYekanRegular" max="100">لینک:</label>
                                                                        <input type="text" class="w-100 ltr form-control" name="link" id="link"  placeholder="لینک" value="{{ old('link') }}">
                                                                    </div>
                                                                </form>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary mr-1" title="ارسال" form="sms-form{{ $adviser->id }}">ارسال</button>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="reserve{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">رزرو سرویس</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body p-3">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <form method="post" action="{{ route('admin.advisers.reserve',$adviser) }}" id="reserve-form{{ $adviser->id }}">
                                                                            @csrf
                                                                            <div class="row mt-2">
                                                                                <label for="branch" class="control-label IRANYekanRegular">شعبه</label>
                                                                                <select name="branch" id="branch" class="form-control select2   IRANYekanRegular"   data-placeholder="... شعبه  مربوطه را انتخاب نمایید">
                                                                                    @foreach($adviser->service->branches as $branch)
                                                                                        <option value="{{ $branch->id }}"  {{ $branch->id==old('branch')?'selected':'' }}>{{ $branch->name  }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="row mt-2">
                                                                                <label for="doctor" class="control-label IRANYekanRegular">پزشک</label>
                                                                                <select name="doctor" class="form-control select2   IRANYekanRegular"   data-placeholder="... پزشک  مربوطه را انتخاب نمایید">
                                                                                    @foreach($adviser->service->doctors as $doctor)
                                                                                        <option value="{{ $doctor->id }}"  {{ $doctor->id==old('doctor')?'selected':'' }}>{{ $doctor->fullname  }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary mr-1" title="رزرو سرویس" form="reserve-form{{ $adviser->id }}">رزرو</button>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                    <!-- Cancel Modal -->
                                                    <div class="modal fade" id="cancel{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">لغو مشاوره</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">
                                                                        آیا مطمئن هستید که میخواهید این سرویس مشاوره را لغو کنید؟
                                                                    </h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.numbers.advisers.cancel',[$adviser->number,$adviser])  }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="btn btn-danger px-8" title="لغو" >لغو</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <div class="input-group">
                                                    <div class="input-group-append">

                                                        <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>

                                                        <div class="dropdown-menu">

                                                            @if(Auth::guard('admin')->user()->can('reserves.create') &&
                                                                  $adviser->status == App\Enums\NumberStatus::RecivedDocuments)
                                                            <a href="#reserve{{ $adviser->id }}" data-toggle="modal"  class="dropdown-item IR cusrsor" data-toggle="modal" title="رزرو سرویس">
                                                                <i class="fab fa-first-order-alt text-primary"></i>
                                                                <span class="p-1">رزرو سرویس</span>
                                                            </a>
                                                            @endif

                                                            <a href="#info{{ $adviser->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="اطلاعات">
                                                                <i class="fa fa-info text-dark font-16"></i>
                                                                <span class="p-1">اطلاعات</span>
                                                            </a>


                                                            @if(Auth::guard('admin')->user()->can('numbers.adviser-phone') &&
                                                                ($adviser->status == App\Enums\NumberStatus::Adviser ||
                                                                $adviser->status == App\Enums\NumberStatus::NoAnswer ||
                                                                $adviser->status == App\Enums\NumberStatus::NextNotice))
                                                            <a  href="#adviser-form{{ $adviser->id }}" class="dropdown-item IR cusrsor" data-toggle="modal" title="مشاور تلفنی">
                                                                <i class="fa fa-user text-success"></i>
                                                                <span class="p-1">مشاوره تلفنی</span>
                                                            </a>
                                                            @endif

                                                            @if(Auth::guard('admin')->user()->can('numbers.advisers.cancel') &&
                                                                 $adviser->status != \App\Enums\NumberStatus::Cancel)
                                                                <a href="#cancel{{ $adviser->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="لغو">
                                                                    <i class="fa fa-window-close text-danger"></i>
                                                                    <span class="p-1">لغو</span>
                                                                </a>
                                                            @endif


                                                            @if(Auth::guard('admin')->user()->can('numbers.sms'))
                                                            @if($adviser->status == App\Enums\NumberStatus::Accept ||
                                                             $adviser->status == App\Enums\NumberStatus::WaitnigForDocuments ||
                                                             $adviser->status == App\Enums\NumberStatus::RecivedDocuments ||
                                                             $adviser->status == App\Enums\NumberStatus::Adviser)
                                                            <a href="#document{{ $adviser->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" data-toggle="modal" title="ارسال مدارک مورد نیاز">
                                                                <i class="fa fa-sms text-dark"></i>
                                                                <span class="p-1">ارسال مدارک</span>
                                                            </a>
                                                            @endif
                                                            <a  href="#sms{{ $adviser->id }}" class="dropdown-item IR cusrsor" data-toggle="modal" title="ارسال پیامک">
                                                                <i class="fa fa-sms text-dark cusrsor"></i>
                                                                <span class="p-1">ارسال پیامک</span>
                                                            </a>
                                                            @endif


{{--                                                            @if(Auth::guard('admin')->user()->can('reserves.create'))--}}
{{--                                                            @if($adviser->status == App\Enums\NumberStatus::RecivedDocuments)--}}
{{--                                                            <a href="#reserve{{ $adviser->id }}" data-toggle="modal"  class="dropdown-item IR cusrsor" data-toggle="modal" title="رزرو سرویس">--}}
{{--                                                                <i class="fab fa-first-order-alt text-primary"></i>--}}
{{--                                                                <span class="p-1">رزرو سرویس</span>--}}
{{--                                                            </a>--}}
{{--                                                            @endif--}}
{{--                                                            @endif--}}

                                                        </div>
                                                    </div>
                                                </div>

                                                @endif
                                            </td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $advisers->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
