@extends('admin.master')

@section('script')
    <script type="text/javascript">
        $("#since-operator-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since-operator-filter",
            textFormat: "yyyy/MM/dd HH:mm",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
                console.log(param1);
            }
        });

        $("#until-operator-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until-operator-filter",
            textFormat: "yyyy/MM/dd HH:mm",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: true,
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
                                    {{ Breadcrumbs::render('numbers') }}
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <i class="fas fa-phone page-icon"></i>
                                لیست شماره های مشاوره
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
                                    <div class="form-group col-2  text-left">
                                        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </div>
                                    <div class="form-group col-10 text-right">

                                        @if(Auth::guard('admin')->user()->can('numbers.history.operators'))
                                            <div class="btn-group" >
                                                <a href="{{ route('admin.numbers.history.operators') }}" class="btn btn-sm btn-warning" title="تاریخچه اپراتورها">
                                                    <i class="fas fa-history plusiconfont"></i>
                                                    <b class="IRANYekanRegular">تاریخچه اپراتورها</b>
                                                </a>
                                            </div>
                                        @endif

                                        @if(Auth::guard('admin')->user()->can('numbers.history.advisers'))
                                            <div class="btn-group" >
                                                <a href="{{ route('admin.numbers.history.advisers') }}" class="btn btn-sm btn-info" title="تاریخچه مشاورها">
                                                    <i class="fas fa-history plusiconfont"></i>
                                                    <b class="IRANYekanRegular">تاریخچه مشاورها</b>
                                                </a>
                                            </div>
                                        @endif

                                        @if(Auth::guard('admin')->user()->can('numbers.create'))
                                            <div class="btn-group" >
                                                <a href="{{ route('admin.numbers.create') }}" class="btn btn-sm btn-primary" title="افزودن شماره جدید">
                                                    <i class="fa fa-plus plusiconfont"></i>
                                                    <b class="IRANYekanRegular">افزودن شماره جدید</b>
                                                </a>
                                            </div>
                                        @endif

                                        @if(Auth::guard('admin')->user()->can('numbers.import'))
                                            <div class="btn-group" >
                                                <a href="{{ route('admin.numbers.csv') }}" class="btn btn-sm btn-danger" title="CSV درون ریزی فایل">
                                                    <i class="fas fa-file-import plusiconfont"></i>
                                                    <b class="IRANYekanRegular">CSV درون ریزی فایل</b>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if(Auth::guard('admin')->user()->can('numbers.definition-operator'))
                                    <form method="post" action="{{ route('admin.numbers.referall') }}" id="refer-all">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row" style="margin-bottom: 20px;">
                                            <div class="form-group justify-content-center col-12 col-md-2">
                                                <select name="operator" id="operator-refer" class="form-control text-left IRANYekanRegular">
                                                    <option value="">اپراتور تلفنی را انتخاب کنید...</option>
                                                    @foreach($operators as $operator)
                                                        <option value="{{ $operator->id }}">{{ $operator->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group justify-content-center col-12 col-md-2">
                                                <select name="festival" id="festival-refer" class="form-control text-left IRANYekanRegular">
                                                    <option value="">جشنواره مورد نظر را انتخاب کنید...</option>
                                                    @foreach($festivals as $festival)
                                                        <option value="{{ $festival->id }}">{{ $festival->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group text-left" sttile="width:20px;">
                                                <button  class="btn btn-sm btn-warning">
                                                    <b class="IRANYekanRegular">ارجاع</b>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif

                                <div class="collapse" id="filter">
                                    <div class="card card-body filter">
                                        <form id="filter-form">

                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="firstname-filter" class="control-label IRANYekanRegular">نام</label>
                                                    <input type="text"  class="form-control input" id="firstname-filter" name="firstname" placeholder="نام را وارد کنید" value="{{ request('firstname') }}">
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="lastname-filter" class="control-label IRANYekanRegular">نام و نام خانوادگی</label>
                                                    <input type="text"  class="form-control input" id="lastname-filter" name="lastname" placeholder="نام خانوادگی را وارد کنید" value="{{ request('lastname') }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="mobile-filter" class="control-label IRANYekanRegular">موبایل</label>
                                                    <input type="text"  class="form-control input text-right" id="mobile-filter" name="mobile" placeholder="موبایل را وارد کنید" value="{{ request('mobile') }}">
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="user-filter" class="control-label IRANYekanRegular">نام یا موبایل معرف</label>
                                                    <input type="text"  class="form-control input text-right" id="user-filter" name="user" placeholder="نام یا موبایل معرف را وارد کنید" value="{{ request('user') }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                @if(Auth::guard('admin')->user()->can('numbers.definition-operator'))
                                                    <div class="form-group justify-content-center col-6">
                                                        <label for="operators-filter" class="control-label IRANYekanRegular">اپراتورها</label>
                                                        <select name="operators[]" id="operators-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...اپراتور های مورد نظر را انتخاب نمایید">
                                                            @foreach($operators as $operator)
                                                                <option value="{{ $operator->id }}" @if(request('operators')!=null) {{ in_array($operator->id,request('operators'))?'selected':'' }} @endif>{{ $operator->fullname }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                @if(Auth::guard('admin')->user()->can('numbers.definition-adviser'))
                                                    <div class="form-group justify-content-center col-6">
                                                        <label for="advisers-filter" class="control-label IRANYekanRegular">مشاوران</label>
                                                        <select name="advisers[]" id="advisers-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...مشاورهای مورد نظر را انتخاب نمایید">
                                                            @foreach($advisers as $adviser)
                                                                <option value="{{ $adviser->id }}" @if(request('advisers')!=null) {{ in_array($adviser->id,request('advisers'))?'selected':'' }} @endif>{{ $adviser->fullname }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endIf
                                            </div>

                                            <div class="row">
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
                                                    <label for="status" class="control-label IRANYekanRegular">مرجع پذیرش</label>
                                                    <select name="type[]" id="type-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... وضعیت های مورد نظر را انتخاب نمایید">
                                                        <option value="{{ App\Enums\NumberType::his }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::his,request('type'))?'selected':'' }} @endif>HIS</option>
                                                        <option value="{{ App\Enums\NumberType::instagram }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::instagram,request('type'))?'selected':'' }} @endif>اینستاگرام</option>
                                                        <option value="{{ App\Enums\NumberType::telegram }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::telegram,request('type'))?'selected':'' }} @endif>تلگرام</option>
                                                        <option value="{{ App\Enums\NumberType::sms }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::sms,request('type'))?'selected':'' }} @endif>پیامک</option>
                                                        <option value="{{ App\Enums\NumberType::lahijan }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::lahijan,request('type'))?'selected':'' }} @endif>شعبه لاهیجان</option>
                                                        <option value="{{ App\Enums\NumberType::tehran }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::tehran,request('type'))?'selected':'' }} @endif>شعبه تهران</option>
                                                        <option value="{{ App\Enums\NumberType::hozoori }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::hozoori,request('type'))?'selected':'' }} @endif>حضوری</option>
                                                        <option value="{{ App\Enums\NumberType::call }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::call,request('type'))?'selected':'' }} @endif>تماس  های وررودی</option>
                                                        <option value="{{ App\Enums\NumberType::football }}" @if(request('type')!=null) {{ in_array(App\Enums\NumberType::football,request('type'))?'selected':'' }} @endif>آکادمی فوتبال صبوری</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="since-round" class="control-label IRANYekanRegular">تاریخ ارجاع به اپراتور از:</label>
                                                    <input type="text"   class="form-control text-center" id="since-operator-filter" name="since_operator" value="{{ request('since_operator') }}" readonly>
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="since-round" class="control-label IRANYekanRegular">تاریخ ارجاع به اپراتور تا</label>
                                                    <input type="text"   class="form-control text-center" id="until-operator-filter" name="until_operator" value="{{ request('until_operator') }}" readonly>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-6">
                                                    <label for="advisers-filter" class="control-label IRANYekanRegular">جشنواره ها</label>
                                                    <select name="festivals[]" id="festivals-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...مشاورهای مورد نظر را انتخاب نمایید">
                                                        @foreach($festivals as $festival)
                                                            <option value="{{ $festival->id }}" @if(request('festivals')!=null) {{ in_array($festival->id,request('festivals'))?'selected':'' }} @endif>{{ $festival->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="mobile-filter" class="control-label IRANYekanRegular">اطلاعات تکمیلی بیمار</label>
                                                    <input type="text"  class="form-control input" id="information-filter" name="information" placeholder="اطلاعات تکمیلی بیمار را وارد کنید" value="{{ request('information') }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-6">
                                                    <div class="form-check pr-5 mr-2">
                                                        <input class="form-check-input cursor-pointer" type="checkbox" value="on" name="exel" id="exel" {{ request('exel')=='on'?'checked':'' }}>
                                                        <label class="form-check-label IRANYekanRegular" style="margin-right: 19px !important;" for="exel">
                                                            خروجی اکسل
                                                        </label>
                                                    </div>
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
                                                        document.getElementById("user-filter").value = "";
                                                        document.getElementById("information-filter").value = "";
                                                        $("#status-filter").val(null).trigger("change");
                                                        $("#operators-filter").val(null).trigger("change");
                                                        $("#advisers-filter").val(null).trigger("change");
                                                        $("#festivals-filter").val(null).trigger("change");
                                                        $("#type-filter").val(null).trigger("change");
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
                                            <th>
                                                <b class="IRANYekanRegular">ردیف
                                                    <input type="checkbox" name="selectall" id="selectall" class="pointer" title="انتخاب همه">
                                                </b>
                                            </th>
                                            <th><b class="IRANYekanRegular">نام </b></th>
                                            <th><b class="IRANYekanRegular"> نام خانوادگی</b></th>
                                            <th><b class="IRANYekanRegular">شماره موبایل</b></th>
                                            <th><b class="IRANYekanRegular">باشگاه مشتریان</b></th>
                                            <th><b class="IRANYekanRegular">معرف</b></th>
                                            <th><b class="IRANYekanRegular">مرجع پذیرش</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($numbers as $index=>$number)
                                            <tr>
                                                <td>
                                                    @if(Auth::guard('admin')->user()->can('numbers.definition-operator') &&
                                                        ($number->status == App\Enums\NumberStatus::NoAction || $number->status == App\Enums\NumberStatus::Operator
                                                        || $number->status == App\Enums\NumberStatus::NoAnswer || $number->status == App\Enums\NumberStatus::Answer))
                                                        <input type="checkbox" name="numbers[]" class="pointer selectall" form="refer-all" value="{{ $number->id }}">
                                                    @endif
                                                    <strong class="IRANYekanRegular">{{ ++$index}}</strong>
                                                </td>
                                                <td><strong class="IRANYekanRegular">{{ $number->firstname }}</strong></td>
                                                <td><strong class="IRANYekanRegular">{{ $number->lastname }}</strong></td>
                                                <td><a class="IRANYekanRegular" href="{{ route('admin.receptions.index',['mobile'=>$number->mobile]) }}" target="_blank">{{ $number->mobile }}</a></td>
                                                <td>
                                                    @if($number->club())
                                                        @php $info = $number->completeInfo()  @endphp
                                                        <a  href="{{ route('admin.numbers.info.show',$number)  }}" class="dropdown-item IR  cursor-pointer"  title="{{ $info['title'] }}" target="_blank">
                                                            <i class="fa fa-info  cursor-pointer  {{ $info['color'] }}"></i>
                                                        </a>
                                                    @elseif(Auth::guard('admin')->user()->can('numbers.add2club'))
                                                        <a  href="#register{{ $number->id }}" class="dropdown-item IR cursor-pointer" data-toggle="modal" title="افزودن به باشگاه مشتریان">
                                                            <i class="fa fa-plus cursor-pointer"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong class="IRANYekanRegular">
                                                        @if($number->user != null)
                                                            {{ $number->user->firstname.' '.$number->user->lastname.' ('.$number->user->mobile.')' }}
                                                        @endif
                                                    </strong>
                                                </td>

                                                <td>
                                                    <strong class="IRANYekanRegular">
                                                        {{ $number->getType() }}
                                                    </strong>
                                                </td>

                                                <td>
                                                    <strong class="IRANYekanRegular">
                                                        @if($number->status == App\Enums\NumberStatus::NoAction)
                                                            <span class="badge badge-secondary IR p-1">بلاتکلیف</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::Operator)
                                                            <span class="badge badge-warning IR p-1">اپراتور</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::NoAnswer)
                                                            <span class="badge badge-dark IR p-1">عدم پاسخگویی</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::NextNotice)
                                                            <span class="badge badge-light-danger IR p-1">اطلاع بعدی</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::WaitingForAdviser)
                                                            <span class="badge badge-info IR p-1">درخواست مشاور</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::Adviser)
                                                            <span class="badge badge-info IR p-1">مشاور</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::Accept)
                                                            <span class="badge badge-success IR p-1">پذیرش</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::Cancel)
                                                            <span class="badge badge-danger IR p-1">لغو</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::WaitnigForDocuments)
                                                            <span class="badge badge-warning IR p-1">در انتظار ارسال مدارک</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::RecivedDocuments)
                                                            <span class="badge badge-warning IR p-1">دریافت مدارک</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::Reservicd)
                                                            <span class="badge badge-success IR p-1">رزرو شده</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::Confirm)
                                                            <span class="badge badge-primary IR p-1">تایید شده</span>
                                                        @elseif($number->status == App\Enums\NumberStatus::Done)
                                                            <span class="badge badge-primary IR p-1">انجام شده</span>
                                                        @endif
                                                    </strong>
                                                </td>

                                                <td>
                                                    <div class="modal fade" id="register{{ $number->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IR" id="newReviewLabel">ثبت کاربر</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body p-3">
                                                                    <form method="post" action="{{ route('admin.numbers.register',$number) }}" id="register-form{{ $number->id }}">
                                                                        @csrf
                                                                        <div class="row">
                                                                            <div class="col-6">
                                                                                <label for="nationcode{{ $number->id }}" class="col-md-12 col-form-label text-md-left IRANYekanRegular" max="100">شماره ملی:</label>
                                                                                <input type="text" class="form-control w-100 ltr" name="nationcode" minlenght="10" required maxlength="10" id="nationcode{{ $number->id }}"  placeholder="شماره ملی" value="{{ old('nationcode') }}">
                                                                            </div>

                                                                            <div class="col-6">
                                                                                <label for="gender{{ $number->id }}" class="col-md-12 col-form-label text-md-left IRANYekanRegular">جنسیت:</label>
                                                                                <select name="gender" class="form-control  IRANYekanRegular" id="gender{{ $number->id }}">
                                                                                    <option value="{{ App\Enums\genderType::male }}"  {{  App\Enums\genderType::male==old('stattus')?'selected':'' }}>مرد</option>
                                                                                    <option value="{{ App\Enums\genderType::female }}"  {{  App\Enums\genderType::female==old('status')?'selected':'' }}>زن</option>
                                                                                    <option value="{{ App\Enums\genderType::LGBTQ }}"  {{  App\Enums\genderType::LGBTQ==old('status')?'selected':'' }}>غیره ..</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </form>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary mr-1" title="ارسال" form="register-form{{ $number->id }}">ارسال</button>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="info{{ $number->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
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
                                                                            <p> اطلاعات تکمیلی بیمار:<br> {{ $number->information }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>مدیر مشاور: {{ $number->management->fullname ?? "" }}</p>
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
                                                                            <p>جشنواره: {{ $number->festival->title ?? 'ندارد' }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <p> وضعیت:</p>
                                                                            @if($number->status == App\Enums\NumberStatus::NoAction)
                                                                                <span class="badge badge-secondary IR p-1">بلاتکلیف</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::Operator)
                                                                                <span class="badge badge-warning IR p-1">اپراتور</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::NoAnswer)
                                                                                <span class="badge badge-dark IR p-1">عدم پاسخگویی</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::NextNotice)
                                                                                <span class="badge badge-light-danger IR p-1">اطلاع بعدی</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::WaitingForAdviser)
                                                                                <span class="badge badge-info IR p-1">درخواست مشاور</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::Adviser)
                                                                                <span class="badge badge-info IR p-1">مشاور</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::Accept)
                                                                                <span class="badge badge-success IR p-1">پذیرش</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::Cancel)
                                                                                <span class="badge badge-danger IR p-1">لغو</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::WaitnigForDocuments)
                                                                                <span class="badge badge-warning IR p-1">در انتظار ارسال مدارک</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::RecivedDocuments)
                                                                                <span class="badge badge-warning IR p-1">دریافت مدارک</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::Reservicd)
                                                                                <span class="badge badge-success IR p-1">رزرو شده</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::Confirm)
                                                                                <span class="badge badge-primary IR p-1">تایید شده</span>
                                                                            @elseif($number->status == App\Enums\NumberStatus::Done)
                                                                                <span class="badge badge-primary IR p-1">انجام شده</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>زمان پذیرش: {{ $number->accept_date_time() }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>توضیحات اپراتور:</p>
                                                                            <p>{{ $number->operator_description  }}</p>
                                                                            <table class="w-100 IR">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>ردیف</th>
                                                                                    <th>اپراتور</th>
                                                                                    <th>از</th>
                                                                                    <th>تا</th>
                                                                                    <th>جشنواره</th>
                                                                                    <th>توضیحات</th>
                                                                                    <th>تاریخ توضیحات</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @foreach($number['operators'] as $index=>$operator)
                                                                                    <tr>
                                                                                        <td>
                                                                                            @if($operator->until==null) <span class="text-danger">*</span> @endif
                                                                                            {{ ++$index }}
                                                                                        </td>
                                                                                        <td>{{ $operator['admin']['fullname'] }}</td>
                                                                                        <td>{{ $operator->since() }}</td>
                                                                                        <td>{{ $operator->until() }}</td>
                                                                                        <td>{{ $operator->festival->title ?? '' }}</td>
                                                                                        <td>{{ $operator->description }}</td>
                                                                                        <td>{{  $operator->answeredAt() }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">بستن</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="operator{{ $number->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IR" id="newReviewLabel">تعیین اپراتور</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <form method="post" action="{{ route('admin.numbers.operator',$number) }}" id="operator-form-select{{ $number->id }}">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <div class="row">
                                                                                    <select name="operator" class="form-control select2   IRANYekanRegular"   data-placeholder="... اپراتور مورد نظر را انتخاب نمایید">
                                                                                        <option value="" >...ارجاع به</option>
                                                                                        @foreach($operators as $operator)
                                                                                            @if($operator->status == \App\Enums\Status::Active)
                                                                                                <option value="{{ $operator->id }}"  {{ $operator->id==$number->operator_id?'selected':'' }}>{{ $operator->fullname  }}</option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <label for="suggestion{{ $number->id }}" class="col-md-12 col-form-label text-md-left IRANYekanRegular">سرویس های پیشنهادی</label>
                                                                                    <select name="suggestion[]" id="suggestion{{ $number->id }}" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...سرویس های پیشنهادی را انتخاب نمایید">
                                                                                        @foreach($servicesDetails as $service)
                                                                                            <option value="{{ $service->id }}" @if(!empty($number->suggestions)) {{ in_array($service->id,$number->suggestions->pluck('id')->toArray())?'selected':'' }} @endif>{{ $service->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <label for="festival{{ $number->id }}" class="col-md-12 col-form-label text-md-left IRANYekanRegular">جشنواره</label>
                                                                                    <select name="festival"  class="form-control  IRANYekanRegular text-center"  data-placeholder="...  جشنواره را انتخاب نمایید">
                                                                                        <option value="">بدون جشنواره</option>
                                                                                        @foreach($festivals as $festival)
                                                                                            <option value="{{ $festival->id }}"  {{ $festival->id==$number->festival_id?'selected':'' }} >{{ $festival->title }}</option>
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
                                                                                    <th>اپراتور</th>
                                                                                    <th>از</th>
                                                                                    <th>تا</th>
                                                                                    <th>طرح</th>
                                                                                    <th>توضیحات</th>
                                                                                    <th>تاریخ توضیحات</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @foreach($number['operators'] as $index=>$operator)
                                                                                    <tr>
                                                                                        <td>
                                                                                            @if($operator->until==null) <span class="text-danger">*</span> @endif
                                                                                            {{ ++$index }}
                                                                                        </td>
                                                                                        <td>{{ $operator['admin']['fullname'] }}</td>
                                                                                        <td>{{ $operator->since() }}</td>
                                                                                        <td>{{ $operator->until() }}</td>
                                                                                        <td>{{ $operator->festival->title ?? '' }}</td>
                                                                                        <td>{{ $operator->description }}</td>
                                                                                        <td>{{  $operator->answeredAt() }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="operator-form-select{{ $number->id }}">ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="modal fade" id="operator-form{{ $number->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">`
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IR" id="newReviewLabel">اپراتور تلفنی</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body p-3">
                                                                    <form method="post" action="{{ route('admin.numbers.update_oprator',$number) }}" id="operator-update-form{{ $number->id }}">
                                                                        @csrf
                                                                        @method('PATCH')

                                                                        <label for="status-op" class="col-md-12 col-form-label text-md-left IRANYekanRegular">وضعیت:</label>
                                                                        <div class="row">
                                                                              <input type="radio" id="NoAnswer{{ $number->id }}" name="status" value="{{ App\Enums\NumberStatus::NoAnswer }}"
                                                                                     onchange="clearservice({{ $number->id }})"   @if(App\Enums\NumberStatus::NoAnswer==$number->status) checked @endIf>
                                                                              <label for="NoAnswer{{ $number->id }}" class="IR">عدم پاسخگویی</label>
                                                                        </div>

                                                                        <div class="row">
                                                                              <input type="radio" id="Answer{{ $number->id }}" name="status" value="{{ App\Enums\NumberStatus::Cancel }}"
                                                                                     onchange="clearservice({{ $number->id }})"  @if(App\Enums\NumberStatus::Cancel==$number->status) checked @endIf>
                                                                              <label for="Answer{{ $number->id }}" class="IR">عدم تمایل</label>
                                                                        </div>

                                                                        <div class="row">
                                                                              <input type="radio" id="Answer{{ $number->id }}" name="status" value="{{ App\Enums\NumberStatus::NextNotice }}"
                                                                                     onchange="clearservice({{ $number->id }})"  @if(App\Enums\NumberStatus::NextNotice==$number->status) checked @endIf>
                                                                              <label for="Answer{{ $number->id }}" class="IR">اطلاع بعدی</label>
                                                                        </div>

                                                                        <div class="row">
                                                                              <input type="radio" id="WaitingForAdviser{{ $number->id }}" name="status" value="{{ App\Enums\NumberStatus::WaitingForAdviser }}">
                                                                              <label for="WaitingForAdviser{{ $number->id }}" class="IR">درخواست مشاور</label>
                                                                        </div>

                                                                        <div class="row">
                                                                            <label for="information-op" class="col-md-12 col-form-label text-md-left IRANYekanRegular"> اطلاعات تکمیلی بیمار:</label>
                                                                            <textarea name="information" id="information-op"  placeholder="اطلاعات تکمیلی بیمار">{{ $number->information ?? "" }}</textarea>

                                                                            <label for="operator-description" class="col-md-12 col-form-label text-md-left IRANYekanRegular">توضیحات اپراتور تلفنی:</label>
                                                                            <textarea name="operator_description" id="operator-description"  placeholder="توضیحات اپراتور تلفنی"></textarea>


                                                                            @if($number->suggestions == null)
                                                                                <label  class="col-md-12 col-form-label text-md-left IRANYekanRegular">سرویس های پیشنهادی</label>
                                                                                <div class="alert alert-danger text-left w-100">
                                                                                    <ul>
                                                                                        @foreach($number->suggestions as $suggestion)
                                                                                            <li class="IR">{{ $suggestion->name }}</li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            @endif

                                                                            <label for="services{{ $number->id }}" class="col-md-12 col-form-label text-md-left IRANYekanRegular">سرویس ها</label>
                                                                            <select name="services[]" id="services{{ $number->id }}" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple"
                                                                                    multiple data-placeholder="... اپراتور های مورد نظر را انتخاب نمایید" onchange="changeservice({{ $number->id }})">
                                                                                @foreach($servicesDetails as $service)
                                                                                    <option value="{{ $service->id }}" @if(old('services')!=null) {{ in_array($service->id,old('services'))?'selected':'' }} @endif>{{ $service->name }}</option>
                                                                                @endforeach
                                                                            </select>

                                                                        </div>

                                                                    </form>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="operator-update-form{{ $number->id }}">ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="remove{{ $number->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xs">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-3">
                                                                    <h5 class="modal-title IRANYekanRegular" id="newReviewLabel">حذف شماره</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5 class="IRANYekanRegular">آیا مطمئن هستید که می‌خواهید شماره {{ $number->mobile }} را حذف نمایید؟</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form action="{{ route('admin.numbers.destroy', $number) }}"  method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" title="حذف" class="btn btn-danger px-8">حذف</button>
                                                                    </form>
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

                                                    <div class="input-group">
                                                        <div class="input-group-append">

                                                            <i class=" ti-align-justify" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>

                                                            <div class="dropdown-menu">
                                                                <a href="#info{{ $number->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="اطلاعات">
                                                                    <i class="fa fa-info text-dark font-16"></i>
                                                                    <span class="p-1">اطلاعات</span>
                                                                </a>

                                                                @if(Auth::guard('admin')->user()->can('numbers.definition-operator'))
                                                                    <a href="#operator{{ $number->id }}" class="dropdown-item IR cusrsor" data-toggle="modal" title="تعیین اپراتور">
                                                                        <i class="fa fa-user text-warning"></i>
                                                                        <span class="p-1"> تعیین اپراتور</span>
                                                                    </a>
                                                                @endif

                                                                @if(Auth::guard('admin')->user()->can('numbers.operator-phone') &&
                                                                ($number->status == App\Enums\NumberStatus::Operator ||
                                                                $number->status == App\Enums\NumberStatus::NoAnswer ||
                                                                $number->status == App\Enums\NumberStatus::NextNotice ||
                                                                $number->status == App\Enums\NumberStatus::Cancel ||
                                                                $number->status == App\Enums\NumberStatus::Answer))
                                                                    <a href="#operator-form{{ $number->id }}" class="dropdown-item IR cusrsor" data-toggle="modal" title="اپراتور تلفنی">
                                                                        <i class="fa fa-user text-warning"></i>
                                                                        <span class="p-1">اپراتور تلفنی</span>
                                                                    </a>
                                                                @endif

                                                                @if(Auth::guard('admin')->user()->can('numbers.definition-adviser'))
                                                                    <a href="{{ route('admin.numbers.advisers.index',$number) }}"  class="dropdown-item IR cusrsor" title="تعیین مشاور">
                                                                        <i class="fa fa-user text-success"></i>
                                                                        <span class="p-1">مشاوره</span>
                                                                    </a>
                                                                @endif


                                                                @if(Auth::guard('admin')->user()->can('numbers.edit') &&
                                                                 ($number->status == App\Enums\NumberStatus::NoAction || $number->status == App\Enums\NumberStatus::Operator || $number->status == App\Enums\NumberStatus::Cancel))
                                                                    <a  href="{{ route('admin.numbers.edit',$number) }}"  class="dropdown-item IR cusrsor" title="ویرایش">
                                                                        <i class="fa fa-edit text-primary"></i>
                                                                        <span class="p-1">ویرایش</span>
                                                                    </a>
                                                                @endif

                                                                @if(Auth::guard('admin')->user()->can('numbers.delete'))
                                                                    <a href="#remove{{ $number->id }}" data-toggle="modal"   class="dropdown-item IR cusrsor" data-toggle="modal" title="حذف">
                                                                        <i class="fa fa-trash text-danger"></i>
                                                                        <span class="p-1">حدف</span>
                                                                    </a>
                                                                @endif

                                                                @if(Auth::guard('admin')->user()->can('numbers.sms'))
                                                                    <a  href="#sms{{ $number->id }}" class="dropdown-item IR cusrsor" data-toggle="modal" title="ارسال پیامک">
                                                                        <i class="fa fa-sms text-dark cusrsor"></i>
                                                                        <span class="p-1">ارسال پیامک</span>
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
                                    {{ $numbers->render() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const checkbox = document.getElementById('selectall')
            checkbox.addEventListener('change', (event) => {
                if (event.currentTarget.checked)
                {
                    $(".selectall").attr("checked", "true");

                }
                else
                {
                    $(".selectall").removeAttr("checked", "true");

                }})


            function changeservice(id)
            {
                var service = "services"+id;

                if ($('#'+service).val() != "")
                {
                    document.getElementById("WaitingForAdviser"+id).checked = true;
                }
                else
                {
                    document.getElementById("WaitingForAdviser"+id).checked = false;
                }
            }

            function clearservice(id)
            {
                var service = "services"+id;
                $("#"+service).val(null).trigger("change");
            }


        </script>
@endsection
