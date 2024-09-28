@extends('admin.master')


@section('content')

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0 IR">
                                    {{ Breadcrumbs::render('static') }}
                                </ol>
                            </div>
                            <h4 class="page-title">داشبورد</h4>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="m-2">
                        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="collapseExample" title="فیلتر">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>

                <div class="collapse" id="filter">
                    <div class="card card-body filter">
                        <form id="filter-form">
{{--                            <div class="row">--}}
{{--                                <div class="form-group justify-content-center col-6">--}}
{{--                                    <label for="levels-filter" class="control-label IRANYekanRegular">سطح کاربر</label>--}}
{{--                                        <select name="levels[]" id="levels-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... سطوح مورد نظر را انتخاب نمایید">--}}
{{--                                        @foreach($levels as $level)--}}
{{--                                        <option value="{{ $level->id }}" @if(request('levels')!=null) {{ in_array($level->id,request('levels'))?'selected':'' }} @endif>{{ $level->title }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}


{{--                                <div class="form-group justify-content-center col-6">--}}
{{--                                    <label for="doctors-filter" class="control-label IRANYekanRegular">پزشک</label>--}}
{{--                                        <select name="doctors[]" id="doctors-filter" class="form-control select2 select2-multiple text-right IRANYekanRegular" multiple="multiple" multiple data-placeholder="... پزشکان مورد نظر را انتخاب نمایید">--}}
{{--                                        @foreach($doctorsList as $doctor)--}}
{{--                                        <option value="{{ $doctor->id }}" @if(request('doctors')!=null) {{ in_array($doctor->id,request('doctors'))?'selected':'' }} @endif>{{ $doctor->fullname }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}

{{--                           </div>--}}

                            <div class="row">
                                <div class="form-group justify-content-center col-6">
                                    <label for="since" class="col-form-label IRANYekanRegular">از تاریخ</label>
                                    <input type="text"   class="form-control text-center" id="since" name="since"  readonly value="{{ request('since') }}">
                                </div>

                                <div class="form-group justify-content-center col-6">
                                    <label for="until" class="col-form-label IRANYekanRegular">تا تاریخ</label>
                                    <input type="text"   class="form-control text-center" id="until" name="until"  readonly value="{{ request('until') }}">
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
                                        document.getElementById("since").value = "";
                                        document.getElementById("until").value = "";
                                        document.getElementById("levels-filter").selectedIndex = "0";
                                        document.getElementById("doctors-filter").selectedIndex = "0";
                                    }
                                </script>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">

                     <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.reserves.index',['status'=>[App\Enums\ReserveStatus::paid]]) }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد رزرروهای پرداخت شده</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($reserves) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                            <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="نمایش"></i>
                            <h4 class="mt-0 font-16 IRANYekanRegular">مبلغ کل رزرو (تومان)</h4>
                            <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">{{  number_format($reservesSum) }}</h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                            <a href="#" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد ارتقاء</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($upgrades) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                            <a href="#" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">مجموع کل ارتقاء (تومان)</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($upgradesSum) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.shop.sells.index')  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد فروش محصول</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($orders) }}</span></h2>
                        </div>
                    </div>


                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                            <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="More Info"></i>
                            <h4 class="mt-0 font-16 IRANYekanRegular">کل فروش محصول (تومان)</h4>
                            <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">{{  number_format($ordersSum) }}</h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                            <a href="{{ route('admin.users.index')  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد آنالیزهای تایید شده</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($confirmAnalysis) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                            <a href="{{ route('admin.users.index')  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد آنالیزهای پاسخ داده شده</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($responseAalysis) }}</span></h2>
                        </div>
                    </div>


                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.users.index')  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد کاربران</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($users) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.doctors.index')  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد پزشکان</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($doctors) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.tickets.index')  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد تیکت ها</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($tickets) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.reviews.index')  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">تعداد بازخوردها</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($reviews) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.numbers.index',['status'=>[App\Enums\NumberStatus::NoAction]])  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">شماره های بلاتکلیف</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($noActionNumbers) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.numbers.index',['status'=>[App\Enums\NumberStatus::Operator]])  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">شماره های نزد اپراتور</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($operatorsNumbers) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.numbers.index',['status'=>[App\Enums\NumberStatus::Adviser]])  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">شماره های نزد مشاور</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($advisersNumbers) }}</span></h2>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card-box">
                        <a href="{{ route('admin.numbers.index',['status'=>[App\Enums\NumberStatus::Reservicd]])  }}" class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="نمایش" data-original-title="نمایش"></a>
                            <h4 class="mt-0 font-16 IRANYekanRegular">شماره های رزرو شده</h4>
                            <h2 class="text-primary my-3 text-center IR"><span data-plugin="counterup">{{  number_format($reservedNumbers) }}</span></h2>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <div id="container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <h4 class="mt-0 font-16 IRANYekanRegular">نمودار آمار ماهانه تماس های  تلفنی</h4>
                                <div id="bar-chart-report" style="width:100%; height:400px;"></div>
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

        $("#until").MdPersianDateTimePicker({
            targetDateSelector: "#showDate_class",
            targetTextSelector: "#until",
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

  <script src="https://pars-hospital.com/login/assets/node_modules/echarts/echarts-all.js"></script>
   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
   <script src="{{ url('/') }}/panel/assets/js/highcharts.js"></script>
   <script src="{{ url('/') }}/panel/assets/js/exporting.js"></script>

<script type="text/javascript">

    //گزارش خطا ماهانه
    var ReportChart = echarts.init(document.getElementById('bar-chart-report'));

    var OperatorFarvardin  = {{ $OperatorFarvardin }};
    var AdviserFarvardin = {{ $AdviserFarvardin }};
    var AcceptFarvardin = {{ $AcceptFarvardin }};
    var OperatorOrdibehesht = {{ $OperatorOrdibehesht }};
    var AdviserOrdibehesht = {{ $AdviserOrdibehesht }};
    var AcceptOrdibehesht = {{ $AcceptOrdibehesht }};
    var OperatorKhordad = {{ $OperatorKhordad }};
    var AdviserKhordad = {{ $AdviserKhordad }};
    var AcceptKhordad  = {{ $AcceptKhordad }};
    var OperatorTir = {{ $OperatorTir }};
    var AdviserTir = {{ $AdviserTir }};
    var AcceptTir = {{ $AcceptTir }};
    var OperatorMordad = {{ $OperatorMordad }};
    var AdviserMordad = {{ $AdviserMordad }};
    var AcceptMordad = {{ $AcceptMordad }};
    var OperatorShahrivar = {{ $OperatorShahrivar }};
    var AdviserShahrivar = {{ $AdviserShahrivar }};
    var AcceptShahrivar = {{ $AcceptShahrivar }};
    var OperatorMehr = {{ $OperatorMehr }};
    var AdviserMehr = {{ $AdviserMehr }};
    var AcceptMehr = {{ $AcceptMehr }};
    var OperatorAban = {{ $OperatorAban }};
    var AdviserAban = {{ $AdviserAban }};
    var AcceptAban = {{ $AcceptAban }};
    var OperatorAzar = {{ $OperatorAzar }};0;
    var AdviserAzar = {{ $AdviserAzar }};
    var AcceptAzar = {{ $AcceptAzar }};
    var OperatorDei = {{ $OperatorDei }};
    var AdviserDei = {{ $AdviserDei }};
    var AcceptDei = {{ $AcceptDei }};
    var OperatorBahman = {{ $OperatorBahman }};
    var AdviserBahman = {{ $AdviserBahman }};
    var AcceptBahman = {{ $AcceptBahman }};
    var OperatorEsfand = {{ $OperatorEsfand }};
    var AdviserEsfand = {{ $AdviserEsfand }};
    var AcceptEsfand = {{ $AcceptEsfand }};

    switch({{ $mount }}) {
        case 1:mount="فروردین ماه سال جاری";break;
        case 2:mount="اردیبهشت ماه سال جاری";break;
        case 3:mount="خرداد ماه سال جاری";break;
        case 4:mount="تیر ماه سال جاری";break;
        case 5:mount="مرداد ماه سال جاری";break;
        case 6:mount="شهریور ماه سال جاری";break;
        case 7:mount="مهر ماه سال جاری";break;
        case 8:mount="آبان ماه سال جاری";break;
        case 9:mount="آذر ماه سال جاری";break;
        case 10:mount="دی ماه سال جاری";break;
        case 11:mount="بهمن ماه سال جاری";break;
        case 12:mount="اسفند ماه سال جاری";break;
    }

    var Operator1 = {{ $Operator1 }};
    var Operator2 = {{ $Operator2 }};
    var Operator3 = {{ $Operator3 }};
    var Operator4 = {{ $Operator4 }};
    var Operator5 = {{ $Operator5 }};
    var Operator6 = {{ $Operator6 }};
    var Operator7 = {{ $Operator7 }};
    var Operator8 = {{ $Operator8 }};
    var Operator9 = {{ $Operator9 }};
    var Operator10 = {{ $Operator10 }};
    var Operator11 = {{ $Operator11 }};
    var Operator12 = {{ $Operator12 }};
    var Operator13 = {{ $Operator13 }};
    var Operator14 = {{ $Operator14 }};
    var Operator15 = {{ $Operator15 }};
    var Operator16 = {{ $Operator16 }};
    var Operator17 = {{ $Operator17 }};
    var Operator18 = {{ $Operator18 }};
    var Operator19 = {{ $Operator19 }};
    var Operator20 = {{ $Operator20 }};
    var Operator21 = {{ $Operator21 }};
    var Operator22 = {{ $Operator22 }};
    var Operator23 = {{ $Operator23 }};
    var Operator24 = {{ $Operator24 }};
    var Operator25 = {{ $Operator25 }};
    var Operator26 = {{ $Operator26 }};
    var Operator27 = {{ $Operator27 }};
    var Operator28 = {{ $Operator28 }};
    var Operator29 = {{ $Operator29 }};
    var Operator30 = {{ $Operator30 }};
    var Operator31 = {{ $Operator31 }};

    var Adviser1 = {{ $Adviser1 }};
    var Adviser2 = {{ $Adviser2 }};
    var Adviser3 = {{ $Adviser3 }};
    var Adviser4 = {{ $Adviser4 }};
    var Adviser5 = {{ $Adviser5 }};
    var Adviser6 = {{ $Adviser6 }};
    var Adviser7 = {{ $Adviser7 }};
    var Adviser8 = {{ $Adviser8 }};
    var Adviser9 = {{ $Adviser9 }};
    var Adviser10 = {{ $Adviser10 }};
    var Adviser11 = {{ $Adviser11 }};
    var Adviser12 = {{ $Adviser12 }};
    var Adviser13 = {{ $Adviser13 }};
    var Adviser14 = {{ $Adviser14 }};
    var Adviser15 = {{ $Adviser15 }};
    var Adviser16 = {{ $Adviser16 }};
    var Adviser17 = {{ $Adviser17 }};
    var Adviser18 = {{ $Adviser18 }};
    var Adviser19 = {{ $Adviser19 }};
    var Adviser20 = {{ $Adviser20 }};
    var Adviser21 = {{ $Adviser21 }};
    var Adviser22 = {{ $Adviser22 }};
    var Adviser23 = {{ $Adviser23 }};
    var Adviser24 = {{ $Adviser24 }};
    var Adviser25 = {{ $Adviser25 }};
    var Adviser26 = {{ $Adviser26 }};
    var Adviser27 = {{ $Adviser27 }};
    var Adviser28 = {{ $Adviser28 }};
    var Adviser29 = {{ $Adviser29 }};
    var Adviser30 = {{ $Adviser30 }};
    var Adviser31 = {{ $Adviser31 }};

    var Accept1 = {{ $Accept1 }};
    var Accept2 = {{ $Accept2 }};
    var Accept3 = {{ $Accept3 }};
    var Accept4 = {{ $Accept4 }};
    var Accept5 = {{ $Accept5 }};
    var Accept6 = {{ $Accept6 }};
    var Accept7 = {{ $Accept7 }};
    var Accept8 = {{ $Accept8 }};
    var Accept9 = {{ $Accept9 }};
    var Accept10 = {{ $Accept10 }};
    var Accept11 = {{ $Accept11 }};
    var Accept12 = {{ $Accept12 }};
    var Accept13 = {{ $Accept13 }};
    var Accept14 = {{ $Accept14 }};
    var Accept15 = {{ $Accept15 }};
    var Accept16 = {{ $Accept16 }};
    var Accept17 = {{ $Accept17 }};
    var Accept18 = {{ $Accept18 }};
    var Accept19 = {{ $Accept19 }};
    var Accept20 = {{ $Accept20 }};
    var Accept21 = {{ $Accept21 }};
    var Accept22 = {{ $Accept22 }};
    var Accept23 = {{ $Accept23 }};
    var Accept24 = {{ $Accept24 }};
    var Accept25 = {{ $Accept25 }};
    var Accept26 = {{ $Accept26 }};
    var Accept27 = {{ $Accept27 }};
    var Accept28 = {{ $Accept28 }};
    var Accept29 = {{ $Accept29 }};
    var Accept30 = {{ $Accept30 }};
    var Accept31 = {{ $Accept31 }};




    option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['اپراتور تلفنی','مشاوره تلفنی','پذیرش']
        },
        toolbox: {
            show : true,
            feature : {

                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        color: ["#f7b84b", "#37cde6", "#1abc9c"],
        calculable : true,
        xAxis : [
            {
                type : 'category',
                data : ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند']
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'اپراتور تلفنی',
                type:'line',
                data:[OperatorFarvardin,OperatorOrdibehesht,OperatorKhordad,OperatorTir, OperatorMordad, OperatorShahrivar, OperatorMehr,OperatorAban,
                OperatorAzar,OperatorDei,OperatorBahman,OperatorEsfand],
                markPoint : {
                    data : [
                        {type : 'max', name: 'بیشترین آمار'},
                        {type : 'min', name: 'کمترین آمار'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: 'میانگین'}
                    ]
                }
            },
            {
                name:'مشاوره تلفنی',
                type:'line',
                data:[AdviserFarvardin, AdviserOrdibehesht, AdviserKhordad, AdviserTir, AdviserMordad,AdviserShahrivar, AdviserMehr,
                AdviserAban,AdviserAzar,AdviserDei,AdviserBahman,AdviserEsfand],
                markPoint : {
                    data : [
                        {type : 'max', name: 'بیشترین آمار'},
                        {type : 'min', name: 'کمترین آمار'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name : 'میانگین'}
                    ]
                }
            },
            {
                name:'پذیرش',
                type:'line',
                data:[AcceptFarvardin, AcceptOrdibehesht, AcceptKhordad, AcceptTir, AcceptMordad,AcceptShahrivar, AcceptMehr,AcceptAban,
                AcceptAzar,AcceptDei,AcceptBahman,AcceptEsfand],
                markPoint : {
                    data : [
                        {type : 'max', name: 'بیشترین آمار'},
                        {type : 'min', name: 'کمترین آمار'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name : 'میانگین'}
                    ]
                }
            }
        ]
    };


    ReportChart.setOption(option, true), $(function() {
        function resize() {
            setTimeout(function() {
                myChart.resize()
            }, 100)
        }
        $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
    });



    $(function () {
        Highcharts.setOptions({
            chart: {
                style: {
                    fontSize: '12px',
                    fontFamily: 'Tahoma',
                    textAlign:'right'
                }
            }
        });
        $('#container').highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: 'نمودار آمار روزانه مشاوره های تلفنی',
                x: -20,
                style: {
                    fontWeight: 'bold'
                }
            },
            subtitle: {
                text:  mount,
                x: -20
            },
            xAxis: {
                categories: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]
            },
            yAxis: {
                title: {
                    text: 'تعداد'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                rtl: true
            },
            tooltip: {
                valueSuffix: '',
                crosshairs: true,
                shared: true,
                useHTML: true
            },
            series: [{
                name: 'اپراتور',
                data: [Operator1,Operator2,Operator3,Operator4,Operator5,Operator6,Operator7,Operator8,Operator9,Operator10,Operator11,Operator12,
                       Operator13,Operator14,Operator15,Operator16,Operator17,Operator18,Operator19,Operator20,Operator21,Operator22,Operator23,
                       Operator24,Operator25,Operator26,Operator27,Operator28,Operator29,Operator30,Operator31]
            }, {
                name: 'مشاوره',
                data: [Adviser1,Adviser2,Adviser3,Adviser4,Adviser5,Adviser6,Adviser7,Adviser8,Adviser9,Adviser10,Adviser11,Adviser12,
                    Adviser13,Adviser14,Adviser15,Adviser16,Adviser17,Adviser18,Adviser19,Adviser20,Adviser21,Adviser22,Adviser23,
                    Adviser24,Adviser25,Adviser26,Adviser27,Adviser28,Adviser29,Adviser30,Adviser31]
            }, {
                name: 'پذیرش',
                data: [Accept1,Accept2,Accept3,Accept4,Accept5,Accept6,Accept7,Accept8,Accept9,Accept10,Accept11,Accept12,
                    Accept13,Accept14,Accept15,Accept16,Accept17,Accept18,Accept19,Accept20,Accept21,Accept22,Accept23,
                    Accept24,Accept25,Accept26,Accept27,Accept28,Accept29,Accept30,Accept31]
            }]
        });
    });

</script>

@endsection

