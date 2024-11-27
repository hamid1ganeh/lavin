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
                                {{ Breadcrumbs::render('reserves.determining',$reserve) }}
                            </ol>
                        </div>
                        <h4 class="page-title">
                             <i class="fab fa-first-order-alt page-icon"></i>
                             تعیین وضعیت رزرو
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="margin:auto">
                                <form class="form-horizontal" action="{{ route('admin.reserves.determining',$reserve) }}" method="post">
                                   @csrf
                                   @method('PATCH')
                                    <div class="form-row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="account" class="col-form-label IRANYekanRegular">وضعیت</label>
                                             <select name="status" id="st" class="form-control dropdown IR">
                                                <option value="{{ App\Enums\ReserveStatus::waiting }}" {{ $reserve->status==App\Enums\ReserveStatus::waiting?'selected':'' }}>درانتظار رزرو</option>
                                                <option value="{{ App\Enums\ReserveStatus::confirm }}" {{ $reserve->status==App\Enums\ReserveStatus::confirm?'selected':'' }}>رزرو</option>
                                                 <option value="{{ App\Enums\ReserveStatus::accept }}" {{ $reserve->status==App\Enums\ReserveStatus::accept?'selected':'' }}>پذیرش</option>
                                                 <option value="{{ App\Enums\ReserveStatus::done }}" {{ $reserve->status==App\Enums\ReserveStatus::done?'selected':'' }}>انجام شد</option>
                                                 <option value="{{ App\Enums\ReserveStatus::cancel }}"  {{ $reserve->status==App\Enums\ReserveStatus::cancel?'selected':'' }}>لغو</option>
                                             </select>
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('status') }} </span>
                                        </div>

                                        <div class="form-group col-12 col-md-6">
                                            <label for="account" class="col-form-label IRANYekanRegular">نوبت</label>
                                            <input type="text"   class="form-control text-center" id="time" name="time"  readonly value="{{  $reserve->time!=null?$reserve->round_time2():'' }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('time') }} </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <label for="reception-desc" class="control-label IRANYekanRegular">توضیحات پذیرش</label>
                                            <input type="text" class="form-control input" name="reception_desc" id="reception-desc" placeholder="توضیحات پذیرش را وارد کنید..." value="{{ old('reception_desc') ??  $reserve->reception_desc }}">
                                            <span class="form-text text-danger erroralarm"> {{ $errors->first('reception_desc') }} </span>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success">بروزرسانی</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
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

        $("#time").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#time",
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

