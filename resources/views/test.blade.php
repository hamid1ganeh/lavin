<html lang="en" dir="rtl">

    <link href="https://pars-hospital.com/login/include/diagram/morris/morris.css" rel="stylesheet">
    <link href="https://pars-hospital.com/login/include/diagram/chart/style.css" rel="stylesheet">

</head>

<body>

		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body">
						<div id="bar-chart-report" style="width:100%; height:400px;"></div>
					</div>
				</div>
			</div>	
 
       </div>
 
    <!-- Chart JS -->
    <script src="https://pars-hospital.com/login/assets/node_modules/echarts/echarts-all.js"></script>
    
<script type="text/javascript">


    //گزارش خطا ماهانه 
    var ReportChart = echarts.init(document.getElementById('bar-chart-report'));

    var MorningFarvardin  = 3;
    var AfternoonFarvardin = 1;
    var NaightFarvardin = 0;
    var MorningOrdibehesht = 1;
    var AfternoonOrdibehesht = 0;
    var NaightOrdibehesht = 0;
    var MorningKhordad = 0;
    var AfternoonKhordad = 0;
    var NaightKhordad  = 0;
    var MorningTir = 14;
    var AfternoonTir = 14;
    var NaightTir = 0;
    var MorningMordad = 9;
    var AfternoonMordad = 0;
    var NaightMordad = 0;
    var MorningShahrivar = 187;
    var AfternoonShahrivar = 1;
    var NaightShahrivar = 1;
    var MorningMehr = 0;
    var AfternoonMehr = 0;
    var NaightMehr = 0;
    var MorningAban = 0;
    var AfternoonAban = 0;
    var NaightAban = 0;
    var MorningAzar = 0;
    var AfternoonAzar = 0;
    var NaightAzar = 0;
    var MorningDei = 0;
    var AfternoonDei = 0;
    var NaightDei = 0;
    var MorningBahman = 0;
    var AfternoonBahman = 0;
    var NaightBahman = 0;
    var MorningEsfand = 0;
    var AfternoonEsfand = 0;
    var NaightEsfand = 0;


    option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['شیفت صبح','شیفت عصر','شیفت شب']
        },
        toolbox: {
            show : true,
            feature : {
                
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        color: ["#e46a76", "#01c0c8", "#fb9678"],
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
                name:'شیفت صبح',
                type:'line',
                data:[MorningFarvardin,MorningOrdibehesht,MorningKhordad,MorningTir, MorningMordad, MorningShahrivar, MorningMehr,MorningAban,
                MorningAzar,MorningDei,MorningBahman,MorningEsfand],
                markPoint : {
                    data : [
                        {type : 'max', name: 'بیشترین گزارش'},
                        {type : 'min', name: 'کمترین گزارش'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: 'میانگین'}
                    ]
                }
            },
            {
                name:'شیفت عصر',
                type:'line',
                data:[AfternoonFarvardin, AfternoonOrdibehesht, AfternoonKhordad, AfternoonTir, AfternoonMordad,AfternoonShahrivar, AfternoonMehr,
                AfternoonAban,AfternoonAzar,AfternoonDei,AfternoonBahman,AfternoonEsfand],
                markPoint : {
                    data : [
                        {type : 'max', name: 'بیشترین گزارش'},
                        {type : 'min', name: 'کمترین گزارش'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name : 'میانگین'}
                    ]
                }
            },
            {
                name:'شیفت شب',
                type:'line',
                data:[NaightFarvardin, NaightOrdibehesht, NaightKhordad, NaightTir, NaightMordad,NaightShahrivar, NaightMehr,NaightAban,
                NaightAzar,NaightDei,NaightBahman,NaightEsfand],
                markPoint : {
                    data : [
                        {type : 'max', name: 'بیشترین گزارش'},
                        {type : 'min', name: 'کمترین گزارش'}
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
 
</script>
</body>
    
</html>
