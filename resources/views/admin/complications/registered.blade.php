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
                             {{ Breadcrumbs::render('complications.index') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fa fa-exclamation page-icon"></i>
                             گزارش عوارض ثبت شده
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
                                                <label for="name-filter" class="control-label IRANYekanRegular">نام یا نام خانوادگی</label>
                                                <input type="text"  class="form-control input" id="name-filter" name="name" placeholder="نام یا نام خانوادگی" value="{{ request('name') }}">
                                            </div>
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="mobile-filte" class="control-label IRANYekanRegular">شماره موبایل</label>
                                                <input type="text"  class="form-control input text-right" id="mobile-filter" name="mobile" placeholder="شماره موبایل" value="{{ request('mobile') }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="complications-filter" class="control-label IRANYekanRegular">عوارض</label>
                                                <select name="complications[]" id="complications-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  وضعیت های مورد نظر را انتخاب نمایید">
                                                   @foreach($complicationsList as $cp)
                                                    <option value="{{ $cp->id }}"  @if(request('complications')!=null)  {{  in_array($cp->id,request('complications')) ?'selected':'' }} @endif>{{ $cp->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group justify-content-center col-6">
                                                <label for="status" class="control-label IRANYekanRegular">وضعیت</label>
                                                <select name="status[]" id="status-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... وضعیت های مورد نظر را انتخاب نمایید">
                                                    <option value="{{ App\Enums\ComplicationStatus::pending }}"  @if(request('status')!=null)  {{  in_array(App\Enums\ComplicationStatus::pending,request('status')) ?'selected':'' }} @endif>در انتظار</option>
                                                    <option value="{{ App\Enums\ComplicationStatus::following }}"  @if(request('status')!=null) {{  in_array(App\Enums\ComplicationStatus::following,request('status')) ?'selected':'' }} } @endif>درحال پیگیری</option>
                                                    <option value="{{ App\Enums\ComplicationStatus::followed }}"  @if(request('status')!=null) {{  in_array(App\Enums\ComplicationStatus::followed,request('status')) ?'selected':'' }}  }@endif>پیگیری شده</option>
                                                    <option value="{{ App\Enums\ComplicationStatus::treatment }}"  @if(request('status')!=null) {{  in_array(App\Enums\ComplicationStatus::treatment,request('status')) ?'selected':'' }}  }@endif>درمان عارضه</option>
                                                </select>
                                            </div>

                                        </diV>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="services-filte" class="control-label IRANYekanRegular">سرویس ها</label>
                                                <select name="services[]" id="services-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="...  سرویس های مورد نظر را انتخاب نمایید">
                                                    @foreach($serviceDetails as $service)
                                                        <option value="{{ $service->id }}"  @if(request('services')!=null)  {{  in_array($service->id,request('services')) ?'selected':'' }} @endif>{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group justify-content-center col-12 col-md-6">
                                                <label for="name" class="control-label IRANYekanRegular">نسخه</label>
                                                <input type="text"  class="form-control input" id="prescription-filter" name="prescription" placeholder="نسخه را وارد کنید" value="{{ request('prescription') }}">
                                            </div>
                                        </diV>

                                        <div class="row">
                                            <div class="form-group justify-content-center col-6">
                                                <label for="since" class="control-label IRANYekanRegular">از تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="since-filter" name="since" value="{{ request('since') }}" readonly>
                                            </div>

                                            <div class="form-group justify-content-center col-6">
                                                <label for="since" class="control-label IRANYekanRegular">تا تاریخ</label>
                                                <input type="text"   class="form-control text-center" id="until-filter" name="until" value="{{ request('until') }}" readonly>
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
                                                    document.getElementById("name-filter").value = "";
                                                    document.getElementById("mobile-filter").value = "";
                                                    document.getElementById("prescription-filter").value = "";
                                                    document.getElementById("until-filter").value = "";
                                                    document.getElementById("since-filter").value = "";
                                                    $("#status-filter").val(null).trigger("change");
                                                    $("#complications-filter").val(null).trigger("change");
                                                    $("#services-filter").val(null).trigger("change");
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
                                            <th><b class="IRANYekanRegular">عوارض</b></th>
                                            <th><b class="IRANYekanRegular">نسخه</b></th>
                                            <th><b class="IRANYekanRegular">تاریخ ثبت</b></th>
                                            <th><b class="IRANYekanRegular">وضعیت</b></th>
                                            <th><b class="IRANYekanRegular">اقدامات</b></th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($complicationItems as $index=>$complication)
                                        <tr>
                                            <td><strong class="IRANYekanRegular">{{ ++$index }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $complication->reserve->user->getFullName() }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $complication->reserve->user->mobile }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $complication->reserve->detail_name }}</strong></td>
                                            <td>
                                                @foreach($complication->complications as $cp)
                                                    <span class="badge badge-light-purple IR p-1">{{ $cp->title }}</span>
                                                @endforeach
                                            </td>
                                            <td><strong class="IRANYekanRegular">{{ $complication->prescription }}</strong></td>
                                            <td><strong class="IRANYekanRegular">{{ $complication->register_at() }}</strong></td>
                                            <td>
                                                <strong class="IRANYekanRegular">
                                                    @if($complication->status == App\Enums\ComplicationStatus::pending)
                                                        <span class="badge badge-dark IR p-1">درانتظار</span>
                                                    @elseif($complication->status == App\Enums\ComplicationStatus::following)
                                                        <span class="badge badge-warning IR p-1">درحال پیگیری</span>
                                                    @elseif($complication->status == App\Enums\ComplicationStatus::followed)
                                                        <span class="badge badge-success IR p-1">پیگیری شده</span>
                                                    @elseif($complication->status == App\Enums\ComplicationStatus::cancel)
                                                        <span class="badge badge-danger IR p-1">رد شده</span>
                                                    @elseif($complication->status == App\Enums\ComplicationStatus::treatment)
                                                        <span class="badge badge-primary IR p-1">درمان عارضه</span>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>
                                                @if(Auth::guard('admin')->user()->can('complications.show') &&
                                                    App\Enums\ReserveStatus::done == $complication->reserve->status)
                                                <a class="btn  btn-icon" href="{{ route('admin.reserves.complications.show', $complication->reserve) }}" title="نمایش" target="_blank">
                                                    <i class="fas fa-eye text-success font-22"></i>
                                                </a>
                                                @endif

                                            </td>
                                         </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $complicationItems->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
