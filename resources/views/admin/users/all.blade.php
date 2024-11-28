@extends('admin.master')
@section('script')
    <script type="text/javascript">
        $("#since-birthday-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since-birthday-filter",
            textFormat: "yyyy/MM/dd",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: false,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });

        $("#since-marriage-date-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since-marriage-date-filter",
            textFormat: "yyyy/MM/dd",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: false,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });

        $("#until-marriage-date-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until-marriage-date-filter",
            textFormat: "yyyy/MM/dd",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: false,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });

        $("#until-birthday-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until-birthday-filter",
            textFormat: "yyyy/MM/dd",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: false,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });
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
                                {{ Breadcrumbs::render('users') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-users page-icon"></i>
                               کاربران عادی
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
                                     @if(Auth::guard('admin')->user()->can('users.create'))
                                    <div class="btn-group">
                                        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">افزودن کاربر جدید</b>
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
                                                <label for="name" class="control-label IRANYekanRegular">نام یا نام خانوادگی</label>
                                                <input type="text"  class="form-control input" id="name-filter" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ request('name') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="mobile-filter" class="control-label IRANYekanRegular">موبایل</label>
                                                <input type="text"  class="form-control input" id="mobile-filter" name="mobile" placeholder="موبایل را وارد کنید" value="{{ request('mobile') }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="email-filter" class="control-label IRANYekanRegular">آدرس ایمیل</label>
                                                <input type="text"  class="form-control input" id="email-filter" name="email" placeholder="آدرس ایمیل را وارد کنید...." value="{{ request('email') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="gender" class="control-label IRANYekanRegular">جنسیت</label>
                                                 <select name="gender[]" id="gender-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... جنسیت‌های مورد نظر را انتخاب نمایید">
                                                     <option value="{{ App\Enums\genderType::female }}" @if(request('female')!=null) {{ in_array(App\Enums\genderType::female,request('gender'))?'selected':'' }} @endif>زن</option>
                                                    <option value="{{ App\Enums\genderType::male }}" @if(request('gender')!=null) {{ in_array(App\Enums\genderType::male,request('gender'))?'selected':'' }} @endif>مرد</option>
                                                    <option value="{{ App\Enums\genderType::LGBTQ }}" @if(request('gender')!=null) {{ in_array(App\Enums\genderType::LGBTQ,request('gender'))?'selected':'' }} @endif>غیره...</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="name" class="control-label IRANYekanRegular">سطوح</label>
                                                 <select name="levels[]" id="levels-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سطوح مورد نظر را انتخاب نمایید">
                                                    @foreach($levels as $level)
                                                    <option value="{{ $level->id }}" @if(request('levels')!=null) {{ in_array($level->id,request('levels'))?'selected':'' }} @endif>{{ $level->title }}</option>
                                                    @endforeach
                                                 </select>
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="code-filter" class="control-label IRANYekanRegular">کد کاربر</label>
                                                <input type="text"  class="form-control input" id="code-filter" name="code" placeholder="کد کاربر را وارد کنید" value="{{ request('code') }}">
                                            </div>
                                       </div>

                                       <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="introduced-filter" class="control-label IRANYekanRegular">کد معرف</label>
                                                <input type="text"  class="form-control input" id="introduced-filter" name="introduced" placeholder="کد معرف را وارد کنید" value="{{ request('introduced') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="nationcode-filter" class="control-label IRANYekanRegular">کد ملی</label>
                                                <input type="text"  class="form-control input" id="nationcode-filter" name="nationcode" placeholder="کد ملی را وارد کنید" value="{{ request('nationcode') }}">
                                            </div>
                                       </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="jobs-filter" class="control-label IRANYekanRegular">مشاغل</label>
                                                <select name="jobs[]" id="jobs-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سطوح مورد نظر را انتخاب نمایید">
                                                    @foreach($jobs as $job)
                                                        <option value="{{ $job->id }}" @if(request('jobs')!=null) {{ in_array($job->id,request('jobs'))?'selected':'' }} @endif>{{ $job->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="provinces-filter" class="control-label IRANYekanRegular">استان ها</label>
                                                <select name="provinces[]" id="provinces-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سطوح مورد نظر را انتخاب نمایید">
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->id }}" @if(request('provinces')!=null) {{ in_array($province->id,request('provinces'))?'selected':'' }} @endif>{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="since-birthday-filter" class="control-label IRANYekanRegular">تاریخ تولد از</label>
                                                <input type="text"   class="form-control text-center" id="since-birthday-filter" name="since_birthday" value="{{ request('since_birthday') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="until-birthday-filter" class="control-label IRANYekanRegular">تاریخ تولد تا</label>
                                                <input type="text"   class="form-control text-center" id="until-birthday-filter" name="until_birthday" value="{{ request('until_birthday') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="since-marriage-date-filter" class="control-label IRANYekanRegular">تاریخ ازدواج از</label>
                                                <input type="text"   class="form-control text-center" id="since-marriage-date-filter" name="since_marriage_date" value="{{ request('since_marriage_date') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="until-marriage-dater-filter" class="control-label IRANYekanRegular">تاریخ ازدواج تا</label>
                                                <input type="text"   class="form-control text-center" id="until-marriage-date-filter" name="until_marriage_date" value="{{ request('until_marriage_date') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="cities-filter" class="control-label IRANYekanRegular">شهرها</label>
                                                <select name="cities[]" id="cities-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سطوح مورد نظر را انتخاب نمایید">
                                                    @foreach($cities as $city)
                                                        <option value="{{ $city->id }}" @if(request('cities')!=null) {{ in_array($city->id,request('cities'))?'selected':'' }} @endif>{{ $city->name }}</option>
                                                    @endforeach
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
                                                    document.getElementById("name-filter").value = "";
                                                    document.getElementById("mobile-filter").value = "";
                                                    document.getElementById("email-filter").value = "";
                                                    document.getElementById("code-filter").value = "";
                                                    document.getElementById("introduced-filter").value = "";
                                                    document.getElementById("nationcode-filter").value = "";
                                                    document.getElementById("since-birthday-filter").value = "";
                                                    document.getElementById("until-birthday-filter").value = "";
                                                    document.getElementById("since-marriage-date-filter").value = "";
                                                    document.getElementById("until-marriage-date-filter").value = "";
                                                    $("#gender-filter").val(null).trigger("change");
                                                    $("#levels-filter").val(null).trigger("change");
                                                    $("#jobs-filter").val(null).trigger("change");
                                                    $("#cities-filter").val(null).trigger("change");
                                                    $("#provinces-filter").val(null).trigger("change");

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
                                            <th><b class="IRANYekanRegular">نام</b></th>
                                            <th><b class="IRANYekanRegular">نام خانوادگی</b></th>
                                            <th><b class="IRANYekanRegular">شماره ملی</b></th>
                                            <th><b class="IRANYekanRegular">شماره موبایل</b></th>
                                            <th><b class="IRANYekanRegular">جنسیت</b></th>
                                            <th><b class="IRANYekanRegular">کد کاربر</b></th>
                                            <th><b class="IRANYekanRegular">کد معرف</b></th>
                                            <th><b class="IRANYekanRegular">سطح</b></th>
                                            <th><b class="IRANYekanRegular">امتیاز</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index=>$user)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $user->firstname }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $user->lastname }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $user->nationcode }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $user->mobile }}</strong></td>
                                            <td>
                                                @switch($user->gender)
                                                    @case(App\Enums\genderType::male)
                                                    <span class="badge badge-primary IR p-1">مرد</span>
                                                    @break
                                                    @case(App\Enums\genderType::female)
                                                    <span class="badge badge-success IR p-1">زن</span>
                                                    @break
                                                    @case(App\Enums\genderType::LGBTQ)
                                                    <span class="badge badge-danger IR p-1">LGBTQ</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td><a  href="{{ route('admin.users.create',['introduced' => $user->code]) }}" target="_blank" class="IRANYekanRegular">{{ $user->code }}</a></td>
                                            <td><strong class="IRANYekanRegular">{{ $user->introduced }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $user->level->title }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $user->point }}</strong></td>
                                            <td>
                                                @if($user->trashed())
                                                    @if(Auth::guard('admin')->user()->can('users.destroy'))
                                                    <a class="font18" href="#recycle{{ $user->id }}" data-toggle="modal" title="بازیابی">
                                                        <i class="fa fa-recycle text-danger"></i>
                                                    </a>

                                                    <!-- Recycle Modal -->
                                                    <div class="modal fade" id="recycle{{ $user->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">بازیابی ادمین</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواید این کاربر را بازیابی کنید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.users.recycle', $user) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" title="بازیابی" class="btn btn-info px-8">بازیابی</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @else

                                                    <a href="#info{{ $user->id }}" data-toggle="modal" class="font18 m-1" title="نمایش جزئیات">
                                                        <i class="fa fa-info text-warning"></i>
                                                    </a>

                                                    <!-- Info Modal -->
                                                    <div class="modal fade" id="info{{ $user->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">جزئیات کاربر</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="accordion mb-3" id="accordionExample">
                                                                        <div class="card-header pointer" id="heading1" data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1" style=" text-align: initial;">
                                                                            <h5 class="my-0">
                                                                                <a class="text-primary IR" data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                                                     مشخصات شخصی
                                                                                </a>
                                                                          </h5>
                                                                        </div>

                                                                        <div id="collapse1" class="collapse mycollapse" aria-labelledby="heading1}" data-parent="#accordionExample">
                                                                            <div class="card-body mycollapse" >
                                                                                <div class="row mt-2">
                                                                                    <div class="col-12">
                                                                                        <div class="row">
                                                                                            <div class="col-6">
                                                                                                <P class="IR">نام: {{ $user->firstname  }}</p>
                                                                                            </div>
                                                                                            <div class="col-6">
                                                                                                <P class="IR"> نام خانوادگی: {{ $user->lastname  }}</p>
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-6">
                                                                                                <P class="IR"> موبایل: {{ $user->mobile }}</p>
                                                                                            </div>

                                                                                            <div class="col-6">
                                                                                                <P class="IR"> ایمیل: {{ $user->info->email ?? '' }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-6">
                                                                                                <P class="IR"> کد کاربر: {{ $user->code }}</p>
                                                                                            </div>

                                                                                            <div class="col-6">
                                                                                                <P class="IR"> کد معرف: {{ $user->introduced }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-6">
                                                                                                <P class="IR"> سطح کاربر: {{ $user->level->title }}</p>
                                                                                            </div>

                                                                                            <div class="col-6">
                                                                                                <P class="IR"> امتیاز: {{ $user->point }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-6">
                                                                                                @switch($user->gender)
                                                                                                    @case(App\Enums\genderType::male)
                                                                                                        جنسیت: <span class="badge badge-primary IR p-1">مرد</span>
                                                                                                        @break
                                                                                                    @case(App\Enums\genderType::female)
                                                                                                        جنسیت: <span class="badge badge-success IR p-1">زن</span>
                                                                                                        @break
                                                                                                    @case(App\Enums\genderType::LGBTQ)
                                                                                                        جنسیت: <span class="badge badge-danger IR p-1">LGBTQcc</span>
                                                                                                        @break
                                                                                                @endswitch
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                        <div class="card-header pointer" id="heading2" data-toggle="collapse" href="#collapse2" aria-expanded="true" aria-controls="collapse2" style="text-align: initial;">
                                                                            <h5 class="my-0">
                                                                                <a class="text-primary IR" data-toggle="collapse" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                                                                  محل سکونت
                                                                                </a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
                                                                            <div class="card-body mycollapse">
                                                                                <div class="row">
                                                                                    <div class="col-6">
                                                                                        <P class="IR">استان: {{ $user->address->province->name??'' }}</p>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <P class="IR">شهر: {{ $user->address->city->name??'' }}</p>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <P class="IR">ناحیه: {{ $user->address->part->name??'' }}</p>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <P class="IR"> کد پستی: {{ $user->address->postalcode??''  }}</p>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <P class="IR">آدرس: {{ $user->address->address??''  }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="card-header pointer" id="heading3" data-toggle="collapse" href="#collapse3" aria-expanded="true" aria-controls="collapse2" style="text-align: initial;">
                                                                            <h5 class="my-0">
                                                                                <a class="text-primary IR" data-toggle="collapse" href="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                                                                  مشخصات بانکی
                                                                                </a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                                                                            <div class="card-body mycollapse">
                                                                                <div class="row">
                                                                                    <div class="col-12">
                                                                                        <P class="IR">نام بانک: {{ $user->bank->name??'' }}</p>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <P class="IR">شماره کارت: {{ $user->bank->cart??'' }}</p>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <P class="IR">شماره حساب: {{ $user->bank->account??'' }}</p>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <P class="IR">شماره شبا: {{ $user->bank->shaba??'' }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="card-header pointer" id="heading4" data-toggle="collapse" href="#collapse4" aria-expanded="true" aria-controls="collapse2" style="text-align: initial;">
                                                                            <h5 class="my-0">
                                                                                <a class="text-primary IR" data-toggle="collapse" href="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                                                                  سایر مشخصات
                                                                                </a>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionExample">
                                                                            <div class="card-body mycollapse">
                                                                                <div class="row">
                                                                                    <div class="col-12">
                                                                                        <P class="IR">شغل: {{ $user->info->job->title??'' }}</p>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <P class="IR">ایمیل: {{ $user->info->email??'' }}</p>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <P class="IR">تاریخ تولد: {{ $user->birthDate() }}</p>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <P class="IR"> وضعیت تاهل: {{ $user->married() }}</p>
                                                                                    </div>

                                                                                    @if($user->marriageDate() != null)
                                                                                    <div class="col-12">
                                                                                        <P class="IR">  تاریخ ازدواج: {{ $user->marriageDate() }}</p>
                                                                                    </div>
                                                                                    @endif

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                 </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if(Auth::guard('admin')->user()->can('users.edit'))
                                                    <a class="font18 m-1" href="{{ route('admin.users.edit', $user) }}" title="ویرایش">
                                                        <i class="fa fa-edit text-success"></i>
                                                    </a>
                                                    @endif

                                                    @if(Auth::guard('admin')->user()->can('users.destroy'))
                                                    <a href="#remove{{ $user->id }}" data-toggle="modal" class="font18 m-1" title="حذف">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </a>

                                                     <!-- Remove Modal -->
                                                    <div class="modal fade" id="remove{{ $user->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف ادمین</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که میخواهید {{ $user->name }} را حذف نمایید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.users.destroy', $user) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" title="حذف" class="btn btn-danger px-8">حذف</button>
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
                                {{ $users->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
