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
                                {{ Breadcrumbs::render('sms.history') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fas fa-history page-icon"></i>
                             تاریخچه پیامک ها
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
                                <div class="form-group col-6  text-left">
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
                                                <label for="mobile-filter" class="control-label IRANYekanRegular">موبایل</label>
                                                <input type="text"  class="form-control input text-right" id="mobile-filter" name="mobile" placeholder="موبایل را وارد کنید" value="{{ request('mobile') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="content-filter" class="control-label IRANYekanRegular">متن</label>
                                                <input type="text"  class="form-control input text-right" id="content-filter" name="content" placeholder="متن پیامک را وارد کنید" value="{{ request('content') }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="since" class="col-form-label IRANYekanRegular">از تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="since" name="since"  readonly value="{{ request('since') }}">
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="until" class="col-form-label IRANYekanRegular">تا تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="until" name="until"  readonly value="{{ request('until') }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="advisers-filter" class="control-label IRANYekanRegular">فرستندگان</label>
                                                 <select name="admins[]" id="admins-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...فرستندگان مورد نظر را انتخاب نمایید">
                                                    @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}" @if(request('admins')!=null) {{ in_array($admin->id,request('admins'))?'selected':'' }} @endif>{{ $admin->fullname }}</option>
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
                                                    document.getElementById("mobile-filter").value = "";
                                                    document.getElementById("content-filter").value = "";
                                                    document.getElementById("since").value = "";
                                                    document.getElementById("until").value = "";
                                                    $("#admins-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">شماره موبایل </b></th>
                                            <th><b class="IRANYekanRegular">متن</b></th>
                                            <th><b class="IRANYekanRegular">فرستنده</b></th>
                                            <th><b class="IRANYekanRegular">زمان ارسال</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($messages as $index=>$sms)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index}}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $sms->mobile }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $sms->content }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $sms->sender->fullname }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $sms->send_date_time() }}</strong></td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {!! $messages->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script type="text/javascript">
        $("#since").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since",
            textFormat: "yyyy/MM/dd HH:mm:ss",
            isGregorian: false,
            modalMode: false,
            englishNumber: false,
            enableTimePicker: false,
            selectedDateToShow: new Date(),
            calendarViewOnChange: function(param1){
            console.log(param1);
            }
        });

        $("#until").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until",
            textFormat: "yyyy/MM/dd HH:mm:ss",
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
