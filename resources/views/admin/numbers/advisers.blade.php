@extends('admin.master')

@section('script')
    <script type="text/javascript">

        // this will set a value in rate input based on star that has been clicked
        function changeRate(star){
            let stars = star.parentElement.children;
            let input = star.parentElement.nextElementSibling;
            let value = star.dataset.tooltip;
            $.map(stars, function(item){
                $(item).removeClass('text-warning-force');
            });
            for (let i = 0; i < value; i++) {
                $(stars[i]).addClass('text-warning-force');
            }
            input.value = value;
        }

        // change star color when hover
        function changeStarColor(star) {
            let stars = star.parentElement.children;
            let value = star.dataset.tooltip;
            for (let i = 0; i < value; i++) {
                $(stars[i]).addClass('text-warning');
            }
        }

        // reset star color when mouse leave
        function removeStarColor(star) {
            let stars = star.parentElement.children;
            $.map(stars, function(item){
                $(item).removeClass('text-warning');
            });
        }

    </script>
@endsection

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
                            {{ Breadcrumbs::render('numbers.advisers',$number) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-phone page-icon"></i>
                              مشاوران {{ $number->firstname." ".$number->lastname." (".$number->mobile.")"}}
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
                            <div class="table-responsive" style="overflow-x: initial !important;">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><b class="IRANYekanRegular">ردیف</b></th>
                                            <th><b class="IRANYekanRegular">سرویس </b></th>
                                            <th><b class="IRANYekanRegular">مدیر مشاوره</b></th>
                                            <th><b class="IRANYekanRegular">اپراتور</b></th>
                                            <th><b class="IRANYekanRegular">مشاور</b></th>
                                            <th><b class="IRANYekanRegular">ارنجمنت</b></th>
                                            <th><b class="IRANYekanRegular">زمان ارجاع به مشاور</b></th>
                                            <th><b class="IRANYekanRegular">نوع مشاوره</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($advisers as $index=>$adviser)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index}}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">{{ $adviser->service->name ?? "" }}</strong>
                                                @if($adviser->service->trashed())
                                                <span class="text-danger">(حذف شده)</span>
                                                @elseIf($adviser->service->status == App\Enums\Status::Deactive)
                                                <span class="text-danger">(غیرفعال)</span>
                                                @endIf
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $adviser->management->fullname ?? "" }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $adviser->operator->fullname ?? "" }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $adviser->adviser->fullname ?? "" }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $adviser->arrangement->fullname ?? "" }}</strong></td>
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
                                                    @if($adviser->status == App\Enums\NumberStatus::NoAction)
                                                        <span class="badge badge-secondary IR p-1">بلاتکلیف</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::Operator)
                                                        <span class="badge badge-warning IR p-1">اپراتور</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::NoAnswer)
                                                        <span class="badge badge-dark IR p-1">عدم پاسخگویی</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::NextNotice)
                                                        <span class="badge badge-light-danger IR p-1">اطلاع بعدی</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::WaitingForAdviser)
                                                        <span class="badge badge-info IR p-1">درخواست مشاور</span>
                                                    @elseif($adviser->status == App\Enums\NumberStatus::Adviser)
                                                        <span class="badge badge-info IR p-1">مشاور</span>
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
                                                                            <p>نام: {{ $number->firstname }}</p>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <p>نام خانوادگی: {{ $number->lastname }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <p>موبایل: {{ $number->mobile }}</p>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <p>معرف:
                                                                                @if($number->user != null)
                                                                                    {{ $number->user->firstname.' '.$number->user->lastname.' ('.$number->user->mobile.')' }}
                                                                                    @else
                                                                                    ندارد
                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>اطلاعات تکمیلی بیمار:<br> {{ $number->information }}</p>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>اپراتور تلفنی: {{ $number->operator->fullname ?? "" }}</p>
                                                                        </div>

                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>زمان ارجاع به اپراتور: {{ $number->operator_date_time() }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>توضیحات اپراتور:<br> {{ $number->operator_description }}</p>
                                                                        </div>
                                                                    </div>

                                                                   <div class="row">
                                                                        <div class="col-12">
                                                                            <p>مشاور تلفنی: {{ $adviser->adviser->fullname ?? "" }}</p>
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
                                                                            <p> جشنواره: {{ $adviser->festival->title ?? ''  }}</p>
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
                                                                                <span class="badge badge-info IR p-1">درخواست مشاور</span>
                                                                            @elseif($adviser->status == App\Enums\NumberStatus::Adviser)
                                                                                <span class="badge badge-info IR p-1">مشاور</span>
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
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>زمان پذیرش: {{ $number->accept_date_time() }}</p>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">بستن</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="adviser{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IR" id="newReviewLabel">تعیین مشاور</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <form method="post" action="{{ route('admin.numbers.advisers.set-adviser',[$number,$adviser]) }}" id="adviser-form-select{{ $adviser->id }}">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <div class="row">
                                                                                    <select name="adviser" class="form-control select2   IRANYekanRegular"   data-placeholder="... مشاور مورد نظر را انتخاب نمایید">
                                                                                        <option value="" >... در انتظار تعیین مشاور</option>
                                                                                        @foreach($adviser->service->advisers as $adv)
                                                                                            @if($adv->status == \App\Enums\Status::Active)
                                                                                            <option value="{{ $adv->id }}"  {{ $adv->id==$adviser->adviser_id?'selected':'' }}>{{ $adv->fullname  }}</option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>

                                                                <div class="row mt-2">
                                                                    <div class="col-12">
                                                                        <table class="w-100 IR">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>ردیف</th>
                                                                                        <th>مشاور</th>
                                                                                        <th>از</th>
                                                                                        <th>تا</th>
                                                                                        <th>جشنواره</th>
                                                                                        <th>توضیحات</th>
                                                                                        <th>تاریخ توضیحات</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @foreach($adviser->advisers as $index=>$history)
                                                                                <tr>
                                                                                    <td>
                                                                                        @if($history->until==null) <span class="text-danger">*</span> @endif
                                                                                        {{ ++$index }}
                                                                                    </td>
                                                                                    <td>{{ $history->admin->fullname }}</td>
                                                                                    <td>{{ $history->since() }}</td>
                                                                                    <td>{{ $history->until() }}</td>
                                                                                    <td>{{ $history->festival->title ?? '' }}</td>
                                                                                    <td>{{ $history->description }}</td>
                                                                                    <td>{{  $history->answeredAt() }}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                                </tbody>
                                                                        </table>

                                                                    </div>
                                                                </div>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="adviser-form-select{{ $adviser->id }}">ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
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
                                                                    <form method="post" action="{{ route('admin.numbers.advisers.update_adviser',[$number,$adviser]) }}" id="adviser-form-update{{ $adviser->id }}">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <div class="row">
                                                                            <label for="information-adviser" class="col-md-12 col-form-label text-md-left IRANYekanRegular">اطلاعات تکمیلی بیمار:</label>
                                                                            <textarea name="information" id="information-adviser"  placeholder="اطلاعات تکمیلی بیمار">{{ $number->information ?? "" }}</textarea>

                                                                            <label for="adviser-description" class="col-md-12 col-form-label text-md-left IRANYekanRegular">توضیحات مشاور:</label>
                                                                            <textarea name="adviser_description" id="adviser-description"  placeholder="توضیحات مشاور">{{ $adviser->adviser_description ?? "" }}</textarea>

                                                                            <label for="status-adviser" class="col-md-12 col-form-label text-md-left IRANYekanRegular">وضعیت:</label>
                                                                            <select name="status" class="form-control  IRANYekanRegular" id="status-adviser">
                                                                                <option value="{{ App\Enums\NumberStatus::NoAnswer }}"  {{  App\Enums\NumberStatus::NoAnswer==$adviser->status?'selected':'' }}>عدم پاسخگویی</option>
                                                                                <option value="{{ App\Enums\NumberStatus::Answer }}"  {{  App\Enums\NumberStatus::Answer==$adviser->status?'selected':'' }}>پاسخ داده</option>
                                                                                <option value="{{ App\Enums\NumberStatus::Adviser }}"  {{  App\Enums\NumberStatus::Adviser==$adviser->status?'selected':'' }}>مشاوره</option>
                                                                                <option value="{{ App\Enums\NumberStatus::Accept }}"  {{  App\Enums\NumberStatus::Accept==$adviser->status?'selected':'' }}>پذیرش</option>
                                                                                <option value="{{ App\Enums\NumberStatus::Cancel }}"  {{  App\Enums\NumberStatus::Cancel==$adviser->status?'selected':'' }}>لغو</option>
                                                                            </select>
                                                                        </div>
                                                                    </form>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="adviser-form-update{{ $adviser->id }}">ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="modal fade" id="sms{{ $number->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IR" id="newReviewLabel">ارسال پیامک</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body p-3">
                                                                    <form method="post" action="{{ route('admin.numbers.sms',$number) }}" id="sms-form{{ $number->id }}">
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
                                                                    <button type="submit" class="btn btn-primary mr-1" title="ارسال" form="sms-form{{ $number->id }}">ارسال</button>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف مشاوره</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">
                                                                        آیا مطمئن هستید که میخواهید این سرویس مشاوره را حذف کنید؟
                                                                    </h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.numbers.advisers.destroy',[$number,$adviser])  }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger px-8" title="حذف" >حذف</button>
                                                                    </form>
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
                                                                    <form action="{{ route('admin.numbers.advisers.cancel',[$number,$adviser])  }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="btn btn-danger px-8" title="لغو" >لغو</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="review{{ $adviser->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IR" id="newReviewLabel">نظرسنجی</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <form action="{{ route('admin.numbers.advisers.review',[$number,$adviser]) }}" method="POST" id="review-form">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        @if($adviser->review==null)
                                                                            <div class="row">

                                                                                <div class="col-12">
                                                                                    @foreach($reviewGroups as $reviewGroup)
                                                                                        <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                            <div class="col-3  text-dark small IR">{{ $reviewGroup->title  }}</div>
                                                                                            <div class="col-9  text-nowrap review-rating">
                                                                                                <i class="fa fa-star position-relative" data-tooltip="1" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                                <i class="fa fa-star position-relative" data-tooltip="2" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                                <i class="fa fa-star position-relative" data-tooltip="3" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                                <i class="fa fa-star position-relative" data-tooltip="4" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                                <i class="fa fa-star position-relative" data-tooltip="5" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                            </div>
                                                                                            <input type="hidden" name="{{ $reviewGroup->title  }}">
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>

                                                                                <div class="col-12">
                                                                                    <textarea name="content" id="content" style="height:180px;" placeholder="توضیحات..."></textarea>
                                                                                </div>

                                                                            </div>
                                                                        @else
                                                                            <div class="row">
                                                                                <div class="col-12">

                                                                                    @foreach(json_decode($adviser->review->reviews,true) as $key=>$value)
                                                                                        <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                            <div class="col-3  text-dark small IR">{{ str_replace('_',' ',$key) }}</div>
                                                                                            <div class="col-9 text-left  text-nowrap review-rating">
                                                                                                @for($i=0;$i<=$value;++$i)
                                                                                                    <i class="fa fa-star position-relative text-warning-force" data-tooltip="2"></i>
                                                                                                @endfor
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach

                                                                                </div>

                                                                            </div>

                                                                            <div class="col-12 mt-3">
                                                                                <p class="text-justify IR">{{ $adviser->review->content  }}</p>
                                                                            </div>
                                                                        @endif

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        @if($adviser->review==null && Auth::guard('admin')->user()->can('numbers.advisers.review.observe'))
                                                                            <button type="submit"  title="ثبت" class="btn btn-primary px-8">ثبت</button>&nbsp;
                                                                        @endif
                                                                        <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group">
                                                        <div class="input-group-append">

                                                            <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>

                                                            <div class="dropdown-menu">

                                                                <a href="#info{{ $adviser->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="اطلاعات">
                                                                    <i class="fa fa-info text-dark font-16"></i>
                                                                    <span class="p-1">اطلاعات</span>
                                                                </a>

                                                                @if(Auth::guard('admin')->user()->can('numbers.advisers.review.observe'))
                                                                <a href="#review{{ $adviser->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="نظرسنجی">
                                                                    <i class="fa fa-comment text-warning font-16"></i>
                                                                    <span class="p-1">نظرسنجی</span>
                                                                </a>
                                                                @endif

                                                                @if(Auth::guard('admin')->user()->can('numbers.advisers.destroy'))
                                                                <a href="#remove{{ $adviser->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="حذف">
                                                                    <i class="fa fa-trash text-danger"></i>
                                                                    <span class="p-1">حذف</span>
                                                                </a>
                                                                @endif

                                                                @if(Auth::guard('admin')->user()->can('numbers.advisers.cancel') &&
                                                                    $adviser->status != \App\Enums\NumberStatus::Cancel)
                                                                <a href="#cancel{{ $adviser->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="لغو">
                                                                    <i class="fa fa-window-close text-danger"></i>
                                                                    <span class="p-1">لغو</span>
                                                                </a>
                                                                @endif


                                                                @if(Auth::guard('admin')->user()->can('numbers.definition-adviser'))
                                                                <a href="#adviser{{ $adviser->id }}" class="dropdown-item IR cusrsor" data-toggle="modal"  title="تعیین مشاور">
                                                                    <i class="fa fa-user text-success"></i>
                                                                    <span class="p-1">تعیین مشاور</span>
                                                                </a>
                                                                @endif

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
