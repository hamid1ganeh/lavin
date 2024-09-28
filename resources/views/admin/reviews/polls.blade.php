@extends('admin.master')

@section('script')
    <script type="text/javascript">
        $("#since-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since-filter",
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

        $("#until-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until-filter",
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
                            {{ Breadcrumbs::render('polls') }}
                        </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fa fa-comments page-icon"></i>
                            نظرسنجی اختصاصی رزروها
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
                                <div class="form-group col-12  text-left">
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
                                                <label for="user-filter" class="control-label IRANYekanRegular">کاربر</label>
                                                <input type="text"  class="form-control input" id="user-filter" name="user" placeholder="نام یا نام خانوادگی یا شماره موبایل را وارد کنید" value="{{ request('user') }}">
                                            </div>


                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="admin-filter" class="control-label IRANYekanRegular">ثبت کننده نظر</label>
                                                <select name="admins[]" id="admin-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  ثبت کنندگان  نظر را انتخاب نمایید">
                                                    <option value="0" @if(request('admins')!=null) {{ in_array("0",request('admins'))?'selected':'' }} @endif>کاربر</option>
                                                    @foreach($admins as $admin)
                                                        <option value="{{ $admin->id }}" @if(request('admins')!=null) {{ in_array($admin->id,request('admins'))?'selected':'' }} @endif>{{ $admin->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since" class="control-label IRANYekanRegular">از تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="since-filter" name="since" value="{{ request('since') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="until" class="control-label IRANYekanRegular">تا تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="until-filter" name="until" value="{{ request('until') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="admin-filter" class="control-label IRANYekanRegular">سرویس ها</label>
                                                <select name="services[]" id="service-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  سرویس های مورد نظرسنجی  را انتخاب نمایید">
                                                    @foreach($servicesDetails as $service)
                                                        <option value="{{ $service->id }}" @if(request('services')!=null) {{ in_array($service->id,request('services'))?'selected':'' }} @endif>{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group justify-content-center  col-6">
                                                <div class="form-check mt-4 pr-5 mr-2">
                                                    <input class="form-check-input cursor-pointer" type="checkbox" value="on" name="exel" id="exel" {{ request('exel')=='on'?'checked':'' }}>
                                                    <label class="form-check-label IRANYekanRegular" style="margin-right: 19px !important;" for="exel">
                                                        خروجی اکسل
                                                    </label>
                                                </div>
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
                                                    document.getElementById("user-filter").value = "";
                                                    document.getElementById("since-filter").value = "";
                                                    document.getElementById("until-filter").value = "";
                                                    $("#admin-filter").val(null).trigger("change");
                                                    $("#service-filter").val(null).trigger("change");
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
                                        <th><b class="IRANYekanRegular">ثبت کننده نظر</b></th>
                                        <th><b class="IRANYekanRegular">سررویس ارائه شده</b></th>
                                        <th><b class="IRANYekanRegular">زمان انتظار</b></th>
                                        <th><b class="IRANYekanRegular">کیفیت سرویس</b></th>
                                        <th><b class="IRANYekanRegular">رفتار پرسنل</b></th>
                                        <th><b class="IRANYekanRegular">رضایت از محصول</b></th>
                                        <th><b class="IRANYekanRegular">زمان درج</b></th>
                                        <th><b class="IRANYekanRegular">توضیحات</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($polls as $index=>$poll)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $poll->user->firstname??' '}}
                                                    {{ $poll->user->lastname??' '}}
                                                    {{ '('.$poll->user->mobile.')'??' '}}
                                                </strong>
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $poll->admin->fullname??' کاربر'}}
                                                </strong>
                                            </td>

                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $poll->reserve->service->name??''  }}
                                                </strong>
                                            </td>

                                            <td>
                                                <strong class="IRANYekanRegular">
                                                  {{ $poll->duration  }}
                                                </strong>
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $poll->serviceQuality  }}
                                                </strong>
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $poll->staffBehavior  }}
                                                </strong>
                                            </td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $poll->satisfactionWithProduct  }}
                                                </strong>
                                            </td>

                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $poll->created_at()  }}
                                                </strong>
                                            </td>

                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    {{ $poll->text  }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $polls->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
