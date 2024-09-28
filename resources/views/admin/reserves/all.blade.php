@extends('admin.master')


    @section('script')
        <script type="text/javascript">
            $("#since-reserve-filter").MdPersianDateTimePicker({
                targetDateSelector: "#showDate_class",
                targetTextSelector: "#since-reserve-filter",
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

            $("#until-reserve-filter").MdPersianDateTimePicker({
                targetDateSelector: "#showDate_class",
                targetTextSelector: "#until-reserve-filter",
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


            $("#since-round-filter").MdPersianDateTimePicker({
                targetDateSelector: "#showDate_class",
                targetTextSelector: "#since-round-filter",
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

            $("#until-round-filter").MdPersianDateTimePicker({
                targetDateSelector: "#showDate_class",
                targetTextSelector: "#until-round-filter",
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
                            {{ Breadcrumbs::render('reserves') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="wi wi-time-10 page-icon"></i>
                             رزروها
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

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                                <div class="col-12 col-md-8 text-right">

                                    @if(Auth::guard('admin')->user()->can('reserves.upgrades'))
                                    <div class="btn-group" >
                                        <a href="{{ route('admin.reserves.upgrades') }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-level-up-alt plusiconfont"></i>
                                            <b class="IRANYekanRegular">گزارش ارتقاء</b>
                                        </a>
                                    </div>
                                    @endif

                                    @if(Auth::guard('admin')->user()->can('reserves.create'))
                                    <div class="btn-group" >
                                        <a href="{{ route('admin.reserves.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus plusiconfont"></i>
                                            <b class="IRANYekanRegular">ایجاد رزرو جدید</b>
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
                                                <label for="name" class="control-label IRANYekanRegular">سرویسها</label>
                                                 <select name="services[]" id="service-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سرویس‌های مورد نظر را انتخاب نمایید">
                                                    @foreach($serviceDetails as $service)
                                                    <option value="{{ $service->id }}" @if(request('services')!=null) {{ in_array($service->id,request('services'))?'selected':'' }} @endif>{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </diV>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="name" class="control-label IRANYekanRegular">پزشکان</label>
                                                 <select name="doctors[]" id="doctors-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... پزشکان مورد نظر را انتخاب نمایید">
                                                    @foreach($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}" @if(request('doctors')!=null) {{ in_array($doctor->id,request('doctors'))?'selected':'' }} @endif>{{ $doctor->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="status-filter" class="control-label IRANYekanRegular">وضعیت</label>
                                                 <select name="status[]" id="status-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... وضعیت مورد نظر را انتخاب نمایید">
                                                    <option value="{{ App\Enums\ReserveStatus::waiting }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::waiting,request('status'))?'selected':'' }} @endif>در انتظار رزرو</option>
                                                     <option value="{{ App\Enums\ReserveStatus::wittingForAdviser }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::wittingForAdviser,request('status'))?'selected':'' }} @endif>در انتظار مشاوره</option>
                                                     <option value="{{ App\Enums\ReserveStatus::Adviser }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::Adviser,request('status'))?'selected':'' }} @endif>مشاوره شده</option>
                                                     <option value="{{ App\Enums\ReserveStatus::confirm }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::confirm,request('status'))?'selected':'' }} @endif>تایید</option>
                                                    <option value="{{ App\Enums\ReserveStatus::cancel }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::cancel,request('status'))?'selected':'' }} @endif>لغو</option>
                                                    <option value="{{ App\Enums\ReserveStatus::done }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::done,request('status'))?'selected':'' }} @endif>انجام شده</option>
                                                     <option value="{{ App\Enums\ReserveStatus::paid }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::paid,request('status'))?'selected':'' }} @endif>پراخت شده</option>
                                                 </select>
                                            </div>

                                        </diV>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since-reserve" class="control-label IRANYekanRegular">زمان رزرو از تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="since-reserve-filter" name="since_reserve" value="{{ request('since-reserve') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since-reserve" class="control-label IRANYekanRegular">زمان رزرو تا تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="until-reserve-filter" name="until-reserve" value="{{ request('until-reserve') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since-round" class="control-label IRANYekanRegular">زمان نوبت دهی از تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="since-round-filter" name="since-round" value="{{ request('since-round') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since-round" class="control-label IRANYekanRegular">زمان نوبت دهی تا تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="until-round-filter" name="until-round" value="{{ request('until-round') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="status-filter" class="control-label IRANYekanRegular">نوع رزرو</label>
                                                <select name="type[]" id="type-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... نوع رزرو را انتخاب نمایید">
                                                    <option value="{{ App\Enums\ReserveType::adivser }}" @if(request('type')!=null) {{ in_array(App\Enums\ReserveType::adivser,request('type'))?'selected':'' }} @endif>مشاوره</option>
                                                    <option value="{{ App\Enums\ReserveType::inPerson }}" @if(request('type')!=null) {{ in_array(App\Enums\ReserveType::inPerson,request('type'))?'selected':'' }} @endif>حضوری</option>
                                                    <option value="{{ App\Enums\ReserveType::online }}" @if(request('type')!=null) {{ in_array(App\Enums\ReserveType::online,request('type'))?'selected':'' }} @endif>آنلاین</option>
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="name" class="control-label IRANYekanRegular">شعبه ها</label>
                                                <select name="branches[]" id="branches-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... شعبه های مورد نظر را انتخاب نمایید">
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}" @if(request('branches')!=null) {{ in_array($branch->id,request('branches'))?'selected':'' }} @endif>{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="receptions" class="control-label IRANYekanRegular">مسئول پذیرش</label>
                                                <select name="receptions[]" id="receptions-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... شعبه های مورد نظر را انتخاب نمایید">
                                                    @foreach($receptions as $reception)
                                                        <option value="{{ $reception->id }}" @if(request('receptions')!=null) {{ in_array($reception->id,request('receptions'))?'selected':'' }} @endif>{{ $reception->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="code-filter" class="control-label IRANYekanRegular">کد مراجعه</label>
                                                <input type="text"  class="form-control input" id="code-filter" name="code" placeholder="کد مراجعه را وارد کنید" value="{{ request('code') }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <div class="form-check mt-4 pr-5 mr-2">
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
                                                    document.getElementById("user-filter").value = "";
                                                    document.getElementById("since-reserve-filter").value = "";
                                                    document.getElementById("until-reserve-filter").value = "";
                                                    document.getElementById("since-round-filter").value = "";
                                                    document.getElementById("until-round-filter").value = "";
                                                    document.getElementById("code-filter").value = "";
                                                    $("#doctors-filter").val(null).trigger("change");
                                                    $("#status-filter").val(null).trigger("change");
                                                    $("#service-filter").val(null).trigger("change");
                                                    $("#type-filter").val(null).trigger("change");
                                                    $("#branches-filter").val(null).trigger("change");
                                                    $("#receptions-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">کد مراجعه</b></th>
                                            <th><b class="IRANYekanRegular">شعبه</b></th>
                                            <th><b class="IRANYekanRegular">سرویس</b></th>
                                            <th><b class="IRANYekanRegular">پزشک</b></th>
                                            <th><b class="IRANYekanRegular">رزور</b></th>
                                            <th><b class="IRANYekanRegular">نوبت</b></th>
                                            <th><b class="IRANYekanRegular">نوع رزرو</b></th>
                                            <th><b class="IRANYekanRegular">ارتقاء</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th style="width:200px;"><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reserves as $index=>$reserve)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">
                                                    @if($reserve->user)
                                                    {{ $reserve->user->firstname.' '.$reserve->user->lastname.' ('.$reserve->user->mobile.')' }}
                                                    @endif
                                                </strong>
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $reserve->reception->code ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $reserve->branch->name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $reserve->detail_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $reserve->doctor->fullname ?? "" }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $reserve->reserve_time() }}
                                                </strong>
                                            </td>
                                            <td>
                                                @if($reserve->time!=null)
                                                <strong class="IRANYekanRegular">
                                                    {{ $reserve->round_time() }}
                                                </strong>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">{{ $reserve->getType()  }}</strong>
                                            </td>
                                            <td>
                                                @if($reserve->upgradesCount())
                                                <a class="dropdown-item IR cusrsor" href="{{ route('admin.reserves.upgrade.index', $reserve) }}" title="ارتقاء" target="_blank">
                                                    <i class="fas fa-level-up-alt text-success"></i>
                                                    <span class="p-1">نمایش</span>
                                                </a>
                                                @else
                                               <strong class="IRANYekanRegular">ندارد</strong>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @switch($reserve->status)
                                                        @case(App\Enums\ReserveStatus::waiting)
                                                        <span class="badge badge-warning IR p-1">درانتظار رزرو</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::confirm)
                                                        <span class="badge badge-success IR p-1">رزرو</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::accept)
                                                            <span class="badge badge-success IR p-1">پذیرش</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::cancel)
                                                        <span class="badge badge-danger IR p-1">لغو</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::secratry)
                                                        <span class="badge badge-info IR p-1">ارجاع به منشی</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::done)
                                                        <span class="badge badge-primary IR p-1">انجام شده</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::paid)
                                                        <span class="badge badge-primary IR p-1">پرداخت شده</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::wittingForAdviser)
                                                        <span class="badge badge-warning IR p-1">در انتظار مشاور</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::Adviser)
                                                        <span class="badge badge-warning IR p-1">مشاوره شده</span>
                                                        @break
                                                    @endswitch
                                                </strong>
                                            </td>

                                            <td>

                                                <div class="modal fade" id="info{{ $reserve->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
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
                                                                            <p>نام:
                                                                                @if($reserve->user)
                                                                                    {{ $reserve->user->firstname }}
                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <p>نام خانوادگی:
                                                                                @if($reserve->user)
                                                                                    {{ $reserve->user->lastname }}
                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <p>موبایل:
                                                                                @if($reserve->user)
                                                                                    {{ $reserve->user->mobile }}
                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                     <div class="row">
                                                                         <div class="col-12">
                                                                             <p>پذیرش توسط:
                                                                              {{ $reserve->reception->fullname ?? '' }}
                                                                             </p>
                                                                         </div>
                                                                     </div>

                                                                     <div class="row">
                                                                         <div class="col-12">
                                                                             <p>توضیحات پذیرش:
                                                                                 {{ $reserve->reception_desc }}
                                                                             </p>
                                                                         </div>
                                                                     </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>زمان رزرو:<br> {{ $reserve->reserve_time() }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>زمان نوبت:<br>{{ $reserve->round_time() }}</p>
                                                                        </div>
                                                                    </div>


                                                                     <div class="row">
                                                                         <div class="col-12">
                                                                             <p> زمان اجرا:<br>{{ $reserve->done_time() }}</p>
                                                                         </div>
                                                                     </div>

                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            وضعیت:
                                                                            @switch($reserve->status)
                                                                                @case(App\Enums\ReserveStatus::waiting)
                                                                                    <span class="badge badge-warning IR p-1">درانتظار رزرو</span>
                                                                                    @break
                                                                                @case(App\Enums\ReserveStatus::confirm)
                                                                                    <span class="badge badge-success IR p-1">رزرو</span>
                                                                                    @break
                                                                                @case(App\Enums\ReserveStatus::accept)
                                                                                    <span class="badge badge-success IR p-1">پذیرش</span>
                                                                                    @break
                                                                                @case(App\Enums\ReserveStatus::cancel)
                                                                                    <span class="badge badge-danger IR p-1">لغو</span>
                                                                                    @break
                                                                                @case(App\Enums\ReserveStatus::secratry)
                                                                                    <span class="badge badge-info IR p-1">ارجاع به منشی</span>
                                                                                    @break
                                                                                @case(App\Enums\ReserveStatus::done)
                                                                                    <span class="badge badge-primary IR p-1">انجام شده</span>
                                                                                    @break
                                                                                @case(App\Enums\ReserveStatus::paid)
                                                                                    <span class="badge badge-primary IR p-1">پرداخت شده</span>
                                                                                    @break
                                                                                @case(App\Enums\ReserveStatus::wittingForAdviser)
                                                                                    <span class="badge badge-warning IR p-1">در انتظار مشاور</span>
                                                                                    @break
                                                                                @case(App\Enums\ReserveStatus::Adviser)
                                                                                    <span class="badge badge-warning IR p-1">مشاوره شده</span>
                                                                                    @break
                                                                            @endswitch
                                                                        </div>
                                                                    </div>

                                                                     <div class="row mt-2">
                                                                         <div class="col-12">
                                                                             <p>مدت زمان اجرا: <br>{{ $reserve->duration() }}</p>
                                                                         </div>
                                                                     </div>


                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p>توضیحات اپراتور:</p>
                                                                            <p>{{ $reserve->adviser->number->operator_description ?? ""  }}</p>
                                                                            <table class="w-100 IR">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>ردیف</th>
                                                                                    <th>اپراتور</th>
                                                                                    <th>از</th>
                                                                                    <th>تا</th>
                                                                                    <th>توضیحات</th>
                                                                                    <th>تاریخ توضیحات</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @foreach($reserve->adviser->number->operators ?? [] as $index=>$operator)
                                                                                    <tr>
                                                                                        <td>
                                                                                            @if($operator->until==null) <span class="text-danger">*</span> @endif
                                                                                            {{ ++$index }}
                                                                                        </td>
                                                                                        <td>{{ $operator['admin']['fullname'] }}</td>
                                                                                        <td>{{ $operator->since() }}</td>
                                                                                        <td>{{ $operator->until() }}</td>
                                                                                        <td>{{ $operator->description }}</td>
                                                                                        <td>
                                                                                            @if(!is_null($operator->description))
                                                                                                {{  $operator->updatedAt() }}
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                     <div class="row mt-2">
                                                                         <div class="col-12">
                                                                             <p>توضیحات مشاور:</p>
                                                                             <p>{{ $reserve->adviser->adviser_description ?? ""  }}</p>
                                                                             <table class="w-100 IR">
                                                                                 <thead>
                                                                                 <tr>
                                                                                     <th>ردیف</th>
                                                                                     <th>مشاور</th>
                                                                                     <th>از</th>
                                                                                     <th>تا</th>
                                                                                     <th>توضیحات</th>
                                                                                     <th>تاریخ توضیحات</th>
                                                                                 </tr>
                                                                                 </thead>
                                                                                 <tbody>
                                                                                 @foreach($reserve->adviser->advisers ?? [] as $index=>$history)
                                                                                     <tr>
                                                                                         <td>
                                                                                             @if($history->until==null) <span class="text-danger">*</span> @endif
                                                                                             {{ ++$index }}
                                                                                         </td>
                                                                                         <td>{{ $history->admin->fullname }}</td>
                                                                                         <td>{{ $history->since() }}</td>
                                                                                         <td>{{ $history->until() }}</td>
                                                                                         <td>{{ $history->description }}</td>
                                                                                         <td>
                                                                                             @if(!is_null($history->description))
                                                                                                 {{  $history->updatedAt() }}
                                                                                             @endif
                                                                                         </td>
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

                                                <div class="modal fade" id="review{{ $reserve->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">نظرسنجی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                @if(isset($reserve->review->reviews))
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @foreach(json_decode($reserve->review->reviews,true) as $key=>$value)
                                                                            <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                <div class="col-3  text-dark small">{{ $key }}</div>
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
                                                                    <p class="text-justify IR">{{ $reserve->review->content  }}</p>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="secratry{{ $reserve->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">تعیین منشی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">

                                                                <form method="post" action="{{ route('admin.reserves.secratry',$reserve) }}" id="referForm{{$reserve->id}}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                        <select name="secratry" class="form-control select2   IRANYekanRegular"   data-placeholder="... منشی مورد نظر را انتخاب نمایید">
                                                                            <option value="" >...ارجاع به</option>
                                                                            @foreach($secretaries as $secretry)
                                                                                <option value="{{ $secretry->id }}"  {{ $secretry->id==$reserve->secratry_id?'selected':'' }}>{{ $secretry->fullname  }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </form>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="referForm{{$reserve->id}}">ثبت</button>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="asistant{{ $reserve->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">تعیین وضعیت</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">

                                                                <form method="post" action="{{ route('admin.reserves.done',$reserve) }}" id="doneForm{{$reserve->id}}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                        <select name="asistant" class="form-control select2   IRANYekanRegular"   data-placeholder="...انجام دهنده کار را مشخص نمایید">
                                                                            <option value="" >...اجرا توسط</option>
                                                                            <optgroup label="پزشک">
                                                                                <option value="{{ $reserve->doctor_id }}" {{ $reserve->doctor_id==$reserve->asistant_id?'selected':'' }}>{{ $reserve->doctor->fullname ?? "" }}</option>
                                                                            </optgroup>

                                                                            <optgroup label="دستیار اول پزشک">
                                                                                @foreach($asistants as $asistant)
                                                                                    <option value="{{ $asistant->id }}"  {{ $asistant->id==$reserve->asistant_id?'selected':'' }}>{{ $asistant->fullname  }}</option>
                                                                                @endforeach
                                                                            </optgroup>
                                                                        </select>
                                                                    </div>
                                                                </form>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="doneForm{{$reserve->id}}">ثبت</button>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="poll{{ $reserve->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">نظرسنجی اختصاصی</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <form action="{{ route('admin.reserves.poll', $reserve) }}" method="POST" id="review-form">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    @if($reserve->poll==null)
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">زمان انتظار</div>
                                                                                    <div class="col-9  text-nowrap review-rating">
                                                                                        <i class="fa fa-star position-relative" data-tooltip="1" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="2" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="3" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="4" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="5" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="6" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="7" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="8" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="9" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="10" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                    </div>
                                                                                    <input type="hidden" name="duration">
                                                                                </div>

                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">کیفیت سرویس</div>
                                                                                    <div class="col-9  text-nowrap review-rating">
                                                                                        <i class="fa fa-star position-relative" data-tooltip="1" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="2" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="3" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="4" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="5" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="6" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="7" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="8" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="9" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="10" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                    </div>
                                                                                    <input type="hidden" name="serviceQuality">
                                                                                </div>

                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">رفتار پرسنل</div>
                                                                                    <div class="col-9  text-nowrap review-rating">
                                                                                        <i class="fa fa-star position-relative" data-tooltip="1" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="2" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="3" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="4" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="5" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="6" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="7" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="8" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="9" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="10" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                    </div>
                                                                                    <input type="hidden" name="staffBehavior">
                                                                                </div>

                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">رضایت از محصول</div>
                                                                                    <div class="col-9  text-nowrap review-rating">
                                                                                        <i class="fa fa-star position-relative" data-tooltip="1" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="2" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="3" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="4" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="5" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="6" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="7" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="8" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="9" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                        <i class="fa fa-star position-relative" data-tooltip="10" onclick="changeRate(this)" onmousemove="changeStarColor(this)" onmouseout="removeStarColor(this)"></i>
                                                                                    </div>
                                                                                    <input type="hidden" name="satisfactionWithProduct">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <textarea name="text" id="text" style="height:180px;"></textarea>
                                                                            </div>

                                                                        </div>
                                                                    @else
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">زمان انتظار</div>
                                                                                    <div class="col-9 text-left  text-nowrap review-rating">
                                                                                        @for($i=0;$i<=$reserve->poll->duration;++$i)
                                                                                            <i class="fa fa-star position-relative text-warning-force" data-tooltip="2"></i>
                                                                                        @endfor
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">کیفیت سرویس</div>
                                                                                    <div class="col-9 text-left  text-nowrap review-rating">
                                                                                        @for($i=0;$i<=$reserve->poll->serviceQuality;++$i)
                                                                                            <i class="fa fa-star position-relative text-warning-force" data-tooltip="2"></i>
                                                                                        @endfor
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">رفتار پرسنل</div>
                                                                                    <div class="col-9 text-left  text-nowrap review-rating">
                                                                                        @for($i=0;$i<=$reserve->poll->staffBehavior;++$i)
                                                                                            <i class="fa fa-star position-relative text-warning-force" data-tooltip="2"></i>
                                                                                        @endfor
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <div class="col-12 pt-2 pb-2 px-0 row mx-0 mt-0">
                                                                                    <div class="col-3  text-dark small IR">رضایت از محصول</div>
                                                                                    <div class="col-9 text-left  text-nowrap review-rating">
                                                                                        @for($i=0;$i<=$reserve->poll->satisfactionWithProduct;++$i)
                                                                                            <i class="fa fa-star position-relative text-warning-force" data-tooltip="2"></i>
                                                                                        @endfor
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-12 mt-3">
                                                                            <p class="text-justify IR">{{ $reserve->poll->text  }}</p>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                                <div class="modal-footer">
                                                                    @if($reserve->poll ==null)
                                                                        <button type="submit"  title="ثبت" class="btn btn-primary px-8">ثبت</button>
                                                                        &nbsp
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

                                                            <a href="#info{{ $reserve->id }}" data-toggle="modal" class="dropdown-item IR cusrsor" title="اطلاعات">
                                                                <i class="fa fa-info text-dark font-16"></i>
                                                                <span class="p-1">اطلاعات</span>
                                                            </a>

                                                             @if(Auth::guard('admin')->user()->can('reserves.determining'))
                                                              <a class="dropdown-item IR cusrsor" href="{{ route('admin.reserves.show', $reserve) }}" title="تعیین وضعیت رزرو">
                                                                <i class="fas fa-thumbs-up text-primary"></i>
                                                                <span class="p-1">تعیین وضعیت رزرو</span>
                                                              </a>
                                                              @endif

                                                              @if(Auth::guard('admin')->user()->can('reserves.review') &&
                                                                   $reserve->status == App\Enums\ReserveStatus::done)
                                                                <a class="dropdown-item IR cusrsor" href="#review{{ $reserve->id }}" data-toggle="modal" title="نظرسنجی">
                                                                    <i class="fa fa-comment text-danger cusrsor"></i>
                                                                    <span class="p-1">نظرسنجی عمومی</span>
                                                                </a>

                                                                <a class="dropdown-item IR cusrsor" href="#poll{{ $reserve->id }}" data-toggle="modal" title="نظرسنجی اختصاصی">
                                                                    <i class="fa fa-comment text-info cusrsor"></i>
                                                                    <span class="p-1">نظرسنجی اختصاصی</span>
                                                                </a>
                                                              @endif

                                                               @if(Auth::guard('admin')->user()->can('reserves.payment'))
                                                                <a href="{{ route('admin.reserves.payment',$reserve) }}" class="dropdown-item IR cusrsor" title="پرداخت" target="_blank">
                                                                    <i class="fas fa-dollar-sign text-primary cusrsor"></i>
                                                                    <span class="p-1">پرداخت</span>
                                                                </a>
                                                               @endif

                                                                @if(App\Enums\reserveStatus::done != $reserve->status && Auth::guard('admin')->user()->can('reserves.secratry') && $reserve->paid())
                                                                <a class="dropdown-item IR cusrsor" href="#secratry{{ $reserve->id }}" data-toggle="modal" title="تعیین منشی">
                                                                    <i class="fa fa-user text-dark cusrsor"></i>
                                                                    <span class="p-1">تعیین منشی</span>
                                                                </a>
                                                                @endif


                                                                @if(Auth::guard('admin')->user()->can('reserves.done') &&
                                                                (App\Enums\reserveStatus::done == $reserve->status || App\Enums\reserveStatus::secratry == $reserve->status ))
                                                                <a class="dropdown-item IR cusrsor" href="#asistant{{ $reserve->id }}" data-toggle="modal" title="تعیین وضعیت">
                                                                    <i class="fas fa-thumbs-up  text-primary  cusrsor"></i>
                                                                    <span class="p-1">تعیین وضعیت</span>
                                                                </a>
                                                                @endif

                                                                @if(Auth::guard('admin')->user()->can('reserves.upgrade.index') &&
                                                                    App\Enums\reserveStatus::done == $reserve->status &&
                                                                     !is_null($reserve->secratry_id))
                                                                    <a class="dropdown-item IR cusrsor" href="{{ route('admin.reserves.upgrade.index', $reserve) }}" title="ارتقاء" target="_blank">
                                                                        <i class="fas fa-level-up-alt text-success"></i>
                                                                        <span class="p-1">ارتقاء</span>
                                                                    </a>
                                                                @endif

                                                                @if(Auth::guard('admin')->user()->can('complications.show') &&
                                                                    App\Enums\reserveStatus::done == $reserve->status   )
                                                                    <a class="dropdown-item IR cusrsor" href="{{ route('admin.reserves.complications.show', $reserve) }}" title="عوارض" target="_blank">
                                                                        <i class="fa fa-exclamation text-warning"></i>
                                                                        <span class="p-1">عوارض</span>
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
                                {{ $reserves->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
