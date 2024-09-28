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
                            {{ Breadcrumbs::render('ask.analysis') }}
                        </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fa fa-spinner page-icon"></i>
                             درخواست آنالیز
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                        @if ($errors->any())
                            <div class="row">
                                <div class="col-12 alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li class="IR">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                            <div class="row">
                                <div class="col-12">
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
                                                <label for="name" class="control-label IRANYekanRegular">سرویس های آنالیز</label>
                                                <select name="services[]" id="service-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سرویس‌های آنالیز مورد نظر را انتخاب نمایید">
                                                    @foreach($analiseServices as $service)
                                                        <option value="{{ $service->id }}" @if(request('services')!=null) {{ in_array($service->id,request('services'))?'selected':'' }} @endif>{{ $service->title }}</option>
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
                                                    <option value="{{ App\Enums\AnaliseStatus::pending }}" @if(request('status')!=null) {{ in_array(App\Enums\AnaliseStatus::pending,request('status'))?'selected':'' }} @endif>درانتظار</option>
                                                    <option value="{{ App\Enums\AnaliseStatus::doctor }}" @if(request('status')!=null) {{ in_array(App\Enums\AnaliseStatus::doctor,request('status'))?'selected':'' }} @endif>ارجاع به پزشک</option>
                                                    <option value="{{ App\Enums\AnaliseStatus::response }}" @if(request('status')!=null) {{ in_array(App\Enums\AnaliseStatus::response,request('status'))?'selected':'' }} @endif>پاسخ پزشک</option>
                                                    <option value="{{ App\Enums\AnaliseStatus::accept }}" @if(request('status')!=null) {{ in_array(App\Enums\AnaliseStatus::accept,request('status'))?'selected':'' }} @endif>تایید شده</option>
                                                    <option value="{{ App\Enums\AnaliseStatus::reject }}" @if(request('status')!=null) {{ in_array(App\Enums\AnaliseStatus::reject,request('status'))?'selected':'' }} @endif>رد شده</option>
                                                </select>
                                            </div>

                                        </diV>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="since-filter" class="control-label IRANYekanRegular">زمان درخواست از تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="since-filter" name="since" value="{{ request('since') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="until-filter" class="control-label IRANYekanRegular">زمان درخواست تا تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="until-filter" name="until" value="{{ request('until') }}" readonly>
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
                                                    document.getElementById("since-filter").value = "";
                                                    document.getElementById("until-filter").value = "";
                                                    $("#doctors-filter").val(null).trigger("change");
                                                    $("#status-filter").val(null).trigger("change");
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
                                        <th><b class="IRANYekanRegular">نام و نام خانوادگی</b></th>
                                        <th><b class="IRANYekanRegular">شماره تماس</b></th>
                                        <th><b class="IRANYekanRegular">عنوان سرویس آنالیز</b></th>
                                        <th><b class="IRANYekanRegular">پزشک آنالیز</b></th>
                                        <th><b class="IRANYekanRegular">مبلغ مورد نظر(تومان)</b></th>
                                        <th><b class="IRANYekanRegular">زمان درخواست آنالیز</b></th>
                                        <th><b class="IRANYekanRegular">وضعیت</b></th>
                                        <th><b class="IRANYekanRegular">اقدامات</b></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($asks as $index=>$ask)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->user->getFullName() ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->user->mobile ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->analyse->title ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->doctor->fullname ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->price ?? '' }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $ask->ask_date_time() ?? '' }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($ask->status == App\Enums\AnaliseStatus::pending)
                                                        <span class="badge badge-warning IR p-1">در انتظار</span>
                                                    @elseif($ask->status == App\Enums\AnaliseStatus::doctor)
                                                        <span class="badge badge-info IR p-1">ارجاع به پزشک</span>
                                                    @elseif($ask->status == App\Enums\AnaliseStatus::response)
                                                        <span class="badge badge-success IR p-1">پاسخ پزشک</span>
                                                    @elseif($ask->status == App\Enums\AnaliseStatus::reject)
                                                        <span class="badge badge-danger IR p-1">رد شده</span>
                                                    @elseif($ask->status == App\Enums\AnaliseStatus::accept)
                                                        <span class="badge badge-primary IR p-1">تایید شده</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>
                                                @if(Auth::guard('admin')->user()->can('analysis.ask.show'))
                                                <a class="font18 m-1" href="{{ route('admin.ask.analysis.show',$ask) }}" title="پاسخ پزشک">
                                                    <i class="fa fa-eye text-success"></i>
                                                </a>
                                                @endif

                                               @if(Auth::guard('admin')->user()->can('analysis.ask.doctor'))
                                                <a class="font18 m-1" href="#doctor{{ $ask->id }}" data-toggle="modal" title="ارجاع به پزشک">
                                                    <i class="fas fa-user-md text-info"></i>
                                                </a>

                                                <div class="modal fade" id="doctor{{ $ask->id }}" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xs">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-3">
                                                                <h5 class="modal-title IR" id="newReviewLabel">ارجاع به پزشک</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">

                                                                <form method="post" action="{{ route('admin.ask.analysis.doctor',$ask) }}" id="referForm{{$ask->id}}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="row">
                                                                        <select name="doctor" class="form-control select2   IRANYekanRegular"   data-placeholder="... پزشک مورد نظر را انتخاب نمایید">
                                                                            <option value="" >...ارجاع به</option>
                                                                            @foreach($doctors as $doctor)
                                                                                <option value="{{ $doctor->id }}"  {{ $doctor->id==$ask->doctor_id?'selected':'' }}>{{ $doctor->fullname  }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </form>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary mr-1" title="ثبت" form="referForm{{$ask->id}}">ثبت</button>
                                                                <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $asks->render() }}
                            </div>

                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div>
</div>
@endsection

