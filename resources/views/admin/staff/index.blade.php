@extends('admin.master')


@section('script')

    <script type="text/javascript">
        $("#since-filter").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#since-filter",
            textFormat: "yyyy/MM/dd HH:mm:ss",
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
            textFormat: "yyyy/MM/dd HH:mm:ss",
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
                            {{ Breadcrumbs::render('staff.documents') }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fa fa-file page-icon"></i>
                              پرونده پرسنلی
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
                                <div class="col-md-3">
                                    <div class="card" style="height: 140px;">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <h4 class="card-title IR">مشخصات شخصی</h4>
                                                    </div>
                                                    <div class="col-2">
                                                        <a href="{{ route('admin.staff.documents.personal') }}" class="fa fa-eye text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                                                    </div>
                                                </div>
                                                <strong class="IRANYekanRegular">
                                                    @if($document->personal_status == App\Enums\StaffDocumentStatus::pending)
                                                        <span class="badge badge-light-warning IR float-right mt-3">در انتظار تایید</span>
                                                    @elseif($document->personal_status == App\Enums\StaffDocumentStatus::confirm)
                                                        <span class="badge badge-light-primary IR float-right mt-3">تایید شده</span>
                                                    @elseif($document->personal_status == App\Enums\StaffDocumentStatus::reject)
                                                        <span class="badge badge-light-danger IR float-right mt-3" data-toggle="tooltip" data-placement="bottom" title="{{ $document->personal_desc }}">رد شده</span>
                                                    @endif
                                                </strong>
                                                <img width="50px" src="{{ url('images/front/personal-info.png') }}" alt="مشخصات فردی">
                                                <br>
                                            </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card" style="height: 140px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="card-title IR">مدارک تحصیلی</h4>
                                                </div>
                                                <div class="col-2">
                                                    <a href="{{ route('admin.staff.documents.educations.index') }}" class="fa fa-eye text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                                                </div>
                                            </div>
                                            <strong class="IRANYekanRegular">
                                                @if($document->education_status == App\Enums\StaffDocumentStatus::pending)
                                                    <span class="badge badge-light-warning IR float-right mt-3">در انتظار تایید</span>
                                                @elseif($document->education_status == App\Enums\StaffDocumentStatus::confirm)
                                                    <span class="badge badge-light-primary IR float-right mt-3">تایید شده</span>
                                                @elseif($document->education_status == App\Enums\StaffDocumentStatus::reject)
                                                    <span class="badge badge-light-danger IR float-right mt-3" data-toggle="tooltip" data-placement="bottom" title="{{ $document->education_desc }}">رد شده</span>
                                                @endif
                                            </strong>
                                            <img width="50px" src="{{ url('images/front/education.png') }}" alt="مدارک تحصیلی">
                                            <br>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card" style="height: 140px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="card-title IR">شبکه های اجتماعی</h4>
                                                </div>
                                                <div class="col-2">
                                                    <a href="{{ route('admin.staff.documents.socialmedias.index') }}" class="fa fa-eye text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                                                </div>
                                            </div>
                                            <strong class="IRANYekanRegular">
                                                @if($document->socialmedia_status == App\Enums\StaffDocumentStatus::pending)
                                                    <span class="badge badge-light-warning IR float-right mt-3">در انتظار تایید</span>
                                                @elseif($document->socialmedia_status == App\Enums\StaffDocumentStatus::confirm)
                                                    <span class="badge badge-light-primary IR float-right mt-3">تایید شده</span>
                                                @elseif($document->socialmedia_status == App\Enums\StaffDocumentStatus::reject)
                                                    <span class="badge badge-light-danger IR float-right mt-3" data-toggle="tooltip" data-placement="bottom" title="{{ $document->socialmedia_desc }}">رد شده</span>
                                                @endif
                                            </strong>
                                            <img width="50px" src="{{ url('images/front/instagram.png') }}" alt="مدارک تحصیلی">
                                           <br>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card" style="height: 140px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="card-title IR">مشخصات بانکی</h4>
                                                </div>
                                                <div class="col-2">
                                                    <a href="{{ route('admin.staff.documents.banks.index') }}" class="fa fa-eye text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                                                </div>
                                            </div>
                                            <strong class="IRANYekanRegular">
                                                @if($document->bank_status == App\Enums\StaffDocumentStatus::pending)
                                                    <span class="badge badge-light-warning IR float-right mt-3">در انتظار تایید</span>
                                                @elseif($document->bank_status == App\Enums\StaffDocumentStatus::confirm)
                                                    <span class="badge badge-light-primary IR float-right mt-3">تایید شده</span>
                                                @elseif($document->bank_status == App\Enums\StaffDocumentStatus::reject)
                                                    <span class="badge badge-light-danger IR float-right mt-3" data-toggle="tooltip" data-placement="bottom" title="{{ $document->bank_desc }}">رد شده</span>
                                                @endif
                                            </strong>
                                            <img width="50px" src="{{ url('images/front/bank.png') }}" alt="مدارک تحصیلی">
                                            <br>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card" style="height: 140px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="card-title IR">مشخصات بازآموزی</h4>
                                                </div>
                                                <div class="col-2">
                                                    <a href="{{ route('admin.staff.documents.retrainings.index') }}" class="fa fa-eye text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                                                </div>
                                            </div>
                                            <strong class="IRANYekanRegular">
                                                @if($document->retraining_status == App\Enums\StaffDocumentStatus::pending)
                                                    <span class="badge badge-light-warning IR float-right mt-3">در انتظار تایید</span>
                                                @elseif($document->retraining_status == App\Enums\StaffDocumentStatus::confirm)
                                                    <span class="badge badge-light-primary IR float-right mt-3">تایید شده</span>
                                                @elseif($document->retraining_status == App\Enums\StaffDocumentStatus::reject)
                                                    <span class="badge badge-light-danger IR float-right mt-3" data-toggle="tooltip" data-placement="bottom" title="{{ $document->retraining_desc }}">رد شده</span>
                                                @endif
                                            </strong>
                                            <img width="50px" src="{{ url('images/front/retraining.png') }}" alt="مدارک تحصیلی">
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
