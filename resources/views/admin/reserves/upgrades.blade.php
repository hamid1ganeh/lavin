@extends('admin.master')

@section('script')
    <script type="text/javascript">
        $("#since-done-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since-done-filter",
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

        $("#until-done-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until-done-filter",
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
                                {{ Breadcrumbs::render('reserves.upgrades') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-level-up-alt page-icon"></i>
                            ارتقاء
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


                                <div class="col-12 col-md-4">
                                    <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                                        <i class="fas fa-filter"></i>
                                    </button>
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
                                                    <label for="secretaries-filte" class="control-label IRANYekanRegular">منشی ها</label>
                                                    <select name="secretaries[]" id="secretaries-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... منشی های مورد نظر را انتخاب نمایید">
                                                        @foreach($secretaries as $secretary)
                                                            <option value="{{ $secretary->id }}" @if(request('secretaries')!=null) {{ in_array($secretary->id,request('secretaries'))?'selected':'' }} @endif>{{ $secretary->fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="status-filter" class="control-label IRANYekanRegular">وضعیت</label>
                                                    <select name="status[]" id="status-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... وضعیت مورد نظر را انتخاب نمایید">
                                                        <option value="{{ App\Enums\ReserveStatus::waiting }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::waiting,request('status'))?'selected':'' }} @endif>درانتظار</option>
                                                        <option value="{{ App\Enums\ReserveStatus::cancel }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::cancel,request('status'))?'selected':'' }} @endif>لغو</option>
                                                        <option value="{{ App\Enums\ReserveStatus::done }}" @if(request('status')!=null) {{ in_array(App\Enums\ReserveStatus::done,request('status'))?'selected':'' }} @endif>انجام شده</option>
                                                    </select>
                                                </div>
                                            </diV>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="asistants1-filter" class="control-label IRANYekanRegular">دستیارهای اول</label>
                                                    <select name="asistants1[]" id="asistants1-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  دستیارهای اول مورد نظر را انتخاب نمایید">
                                                        @foreach($asistants as $asistant)
                                                            <option value="{{ $asistant->id }}" @if(request('asistants1')!=null) {{ in_array($asistant->id,request('asistants1'))?'selected':'' }} @endif>{{ $asistant->fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="asistants2-filter" class="control-label IRANYekanRegular">دستیارهای دوم</label>
                                                    <select name="asistants2[]" id="asistants2-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  دستیارهای دوم مورد نظر را انتخاب نمایید">
                                                        @foreach($asistants as $asistant)
                                                            <option value="{{ $asistant->id }}" @if(request('asistants2')!=null) {{ in_array($asistant->id,request('asistants2'))?'selected':'' }} @endif>{{ $asistant->fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </diV>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="since-done" class="control-label IRANYekanRegular">زمان انجام از </label>
                                                    <input type="text"   class="form-control text-center" id="since-done-filter" name="since_done" value="{{ request('since-done') }}" readonly>
                                                </div>

                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="until-done" class="control-label IRANYekanRegular">زمان انجام تا </label>
                                                    <input type="text"   class="form-control text-center" id="until-done-filter" name="until-done" value="{{ request('until-done') }}" readonly>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="name" class="control-label IRANYekanRegular">شعبه ها</label>
                                                    <select name="branches[]" id="branches-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... شعبه های مورد نظر را انتخاب نمایید">
                                                        @foreach($branches as $branch)
                                                            <option value="{{ $branch->id }}" @if(request('branches')!=null) {{ in_array($branch->id,request('branches'))?'selected':'' }} @endif>{{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="receptions" class="control-label IRANYekanRegular">مسئول پذیرش</label>
                                                    <select name="receptions[]" id="receptions-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... شعبه های مورد نظر را انتخاب نمایید">
                                                        @foreach($receptions as $reception)
                                                            <option value="{{ $reception->id }}" @if(request('receptions')!=null) {{ in_array($reception->id,request('receptions'))?'selected':'' }} @endif>{{ $reception->fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="form-group justify-content-center col-12 col-md-6">
                                                    <label for="code-filter" class="control-label IRANYekanRegular">کد مراجعه</label>
                                                    <input type="text"  class="form-control input" id="code-filter" name="code" placeholder="کد مراجعه را وارد کنید" value="{{ request('code') }}">
                                                </div>

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
                                                        document.getElementById("since-done-filter").value = "";
                                                        document.getElementById("until-done-filter").value = "";
                                                        document.getElementById("code-filter").value = "";
                                                        $("#status-filter").val(null).trigger("change");
                                                        $("#service-filter").val(null).trigger("change");
                                                        $("#secretaries-filter").val(null).trigger("change");
                                                        $("#asistants1-filter").val(null).trigger("change");
                                                        $("#asistants2-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">نام نام خانوادگی</b></th>
                                            <th><b class="IRANYekanRegular">شماره موبایل</b></th>
                                            <th><b class="IRANYekanRegular">سرویس</b></th>
                                            <th><b class="IRANYekanRegular">قیمت</b></th>
                                            <th><b class="IRANYekanRegular">دستیار اول</b></th>
                                            <th><b class="IRANYekanRegular">دستیار دوم</b></th>
                                            <th><b class="IRANYekanRegular">توضیحات</b></th>
                                            <th><b class="IRANYekanRegular">زمان انجام</b></th>
                                            <th><b class="IRANYekanRegular">مدت زمان(ساعت)</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upgrades as $index=>$upgrade)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->reserve->user->getFullName()  }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->reserve->user->mobile}}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->detail_name }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ number_format($upgrade->price) }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->asistant1->fullname }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->asistant2->fullname ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->desc ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->done_time()  }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $upgrade->duration()  }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @switch($upgrade->status)
                                                        @case(App\Enums\ReserveStatus::waiting)
                                                        <span class="badge badge-warning IR p-1">درانتظار</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::done)
                                                        <span class="badge badge-primary IR p-1">انجام شده</span>
                                                        @break
                                                        @case(App\Enums\ReserveStatus::cancel)
                                                             <span class="badge badge-danger IR p-1">لغو شده</span>
                                                            @break
                                                    @endswitch
                                                </strong>
                                            </td>


                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $upgrades->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
