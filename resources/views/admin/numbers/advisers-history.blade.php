@extends('admin.master')

@section('script')
    <script type="text/javascript">
        $("#since-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since-filter",
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

        $("#until-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until-filter",
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

        $("#since_response-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since_response-filter",
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

        $("#until_response-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until_response-filter",
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
                            {{ Breadcrumbs::render('numbers.history.advisers') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-history page-icon"></i>
                            تاریخچه مشاورها
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
                            </div>

                            <div class="collapse" id="filter">
                                <div class="card card-body filter">
                                    <form id="filter-form">

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since_refer-filter" class="control-label IRANYekanRegular">تاریخ ارجاع از</label>
                                                <input type="text"   class="form-control text-center" id="since_refer-filter" name="since_refer" value="{{ request('since_refer') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="until_refer-filter" class="control-label IRANYekanRegular">تاریخ ارجاع تا</label>
                                                <input type="text"   class="form-control text-center" id="until_refer-filter" name="until_refer" value="{{ request('until_refer') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since_response-filter" class="control-label IRANYekanRegular">تاریخ پاسخگویی از</label>
                                                <input type="text"   class="form-control text-center" id="since_response-filter" name="since_response" value="{{ request('since_response') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="until_response-filter" class="control-label IRANYekanRegular">تاریخ پاسخگویی تا</label>
                                                <input type="text"   class="form-control text-center" id="until_response-filter" name="until_response" value="{{ request('until_response') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="advisers-filter" class="control-label IRANYekanRegular">مشاوران</label>
                                                <select name="advisers[]" id="advisers-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...اپراتور های مورد نظر را انتخاب نمایید">
                                                    @foreach($advisers as $adviser)
                                                        <option value="{{ $adviser->id }}" @if(request('advisers')!=null) {{ in_array($adviser->id,request('advisers'))?'selected':'' }} @endif>{{ $adviser->fullname }}</option>
                                                    @endforeach
                                                </select>
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
                                                    document.getElementById("since_refer-filter").value = "";
                                                    document.getElementById("until_refer-filter").value = "";
                                                    document.getElementById("since_response-filter").value = "";
                                                    document.getElementById("until_response-filter").value = "";
                                                    $("#operators-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">مشاور</b></th>
                                            <th><b class="IRANYekanRegular">توضیحات مشاور</b></th>
                                            <th><b class="IRANYekanRegular">نام نام خانوادگی</b></th>
                                            <th><b class="IRANYekanRegular">موبایل</b></th>
                                            <th><b class="IRANYekanRegular">از</b></th>
                                            <th><b class="IRANYekanRegular">تا</b></th>
                                            <th><b class="IRANYekanRegular">تاریخ توضیحات</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($histories as $index=>$history)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index}}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">{{ $history->admin->fullname ?? "" }}</strong>
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $history->description ?? "" }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $history->adviser->number->firstname ?? ""}}
                                                    {{ " ".$history->adviser->number->lastname }}
                                                </strong>
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $history->adviser->number->mobile ?? "" }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->since() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->until() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $history->answeredAt() }}</strong>
                                            </td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $histories->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
