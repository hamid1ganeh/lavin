<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AnaliseStatus;
use App\Enums\ReserveStatus;
use App\Http\Controllers\Controller;
use App\Models\AnalyseReserve;
use App\Models\City;
use App\Models\ReserveUpgrade;
use App\Models\ServiceDetail;
use App\Models\User;
use App\Models\ServiceReserve;
use App\Models\ReservePayment;
use App\Models\Order;
use App\Models\Admin;
use App\Models\Level;
use App\Models\Ticket;
use App\Models\Review;
use App\Models\Number;
use App\Models\Adviser;
use App\Enums\Status;
use App\Enums\PaymentStatus;
use App\Enums\DeliveryStatus;
use App\Enums\NumberStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\DoctorCollection;
use \Morilog\Jalali\Jalalian;
use App\Services\VideoService;

class HomeController extends Controller
{


    public function home()
    {
        return  view('admin.home');
    }
     public function static()
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('static');

        $reserves = ServiceReserve::whereHas('payment',function($q){
          $q->where('status',PaymentStatus::paid);
        })->count();

        $reservesSum = ReservePayment::where('status',PaymentStatus::paid)
        ->whereHas('reserve',function($q){
          $q->filter();
        })->sum('price');

        $upgrades = ReserveUpgrade::where('status',ReserveStatus::confirm)->filter()->count();
        $upgradesSum = ReserveUpgrade::where('status',ReserveStatus::confirm)->filter()->sum('price');

        $confirmAnalysis = AnalyseReserve::where('status',AnaliseStatus::accept)->filter()->count();
        $responseAalysis = AnalyseReserve::where('status',AnaliseStatus::response)->filter()->count();



        $orders = Order::where([['status',PaymentStatus::paid],['delivery','<>',DeliveryStatus::repay]])->filter()->count();
        $ordersSum = Order::where([['status',PaymentStatus::paid],['delivery','<>',DeliveryStatus::repay]])->filter()->sum('price');

        $users = User::filter()->count();
        $doctors = Admin::whereHas('roles', function($q){$q->where('name', 'doctor');})->filter()->count();
        $tickets = Ticket::FilterDashboard()->count();
        $reviews = Review::filter()->count();

        $levels = Level::all();

        $doctorsList = Admin::whereHas('roles', function($q){$q->where('name', 'doctor');})->get();

        //آمار تماس های تلفنی
        $noActionNumbers = Number::where('status',NumberStatus::NoAction)->count();
        $operatorsNumbers = Number::where('status',NumberStatus::Operator)->orWhere('status',NumberStatus::NoAnswer)
        ->orWhere('status',NumberStatus::Answer)->count();
        $advisersNumbers = Number::where('status',NumberStatus::WaitingForAdviser)->orWhere('status',NumberStatus::Adviser)->count();
        $reservedNumbers = Number::where('status',NumberStatus::Reservicd)->count();

        //آمار نمودار ماهانه تماسهای تلفنی
         $year =  Jalalian::forge('today')->format('Y');
         $startFarvardin = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/01/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endFarvardin = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/01/31 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startOrdibehesht = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/02/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endOrdibehesht = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/02/31 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startKhordad = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/03/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endKhordad = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/03/31 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startTir = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/04/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endTir = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/04/31 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startMordad = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/05/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endMordad = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/05/31 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startShahrivar = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/06/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endShahrivar = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/06/31 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startMehr = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/07/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endMehr = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/07/30 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startAban = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/08/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endAban = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/08/30 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startAzar = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/09/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endAzar = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/09/30 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startDei = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/10/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endDei = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/09/30 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startBahman = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/11/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endBahman = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/11/30 23:59:59")->toCarbon("Y-m-d H:i:s");
         $startEsfand = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/12/01 00:00:00")->toCarbon("Y-m-d H:i:s");
         $endEsfand = Jalalian::fromFormat('Y/m/d H:i:s',  $year."/12/29 23:59:59")->toCarbon("Y-m-d H:i:s");

         $OperatorFarvardin = Number::whereBetween('operator_date_time',[$startFarvardin,$endFarvardin])->count();
         $AdviserFarvardin = Adviser::whereBetween('adviser_date_time',[$startFarvardin,$endFarvardin])->count();
         $AcceptFarvardin = Number::whereBetween('accept_date_time',[$startFarvardin,$endFarvardin])->count();
         $OperatorOrdibehesht = Number::whereBetween('operator_date_time',[$startOrdibehesht,$endOrdibehesht])->count();
         $AdviserOrdibehesht = Adviser::whereBetween('adviser_date_time',[$startOrdibehesht,$endOrdibehesht])->count();
         $AcceptOrdibehesht = Number::whereBetween('accept_date_time',[$startOrdibehesht,$endOrdibehesht])->count();
         $OperatorKhordad = Number::whereBetween('operator_date_time',[$startKhordad,$endKhordad])->count();
         $AdviserKhordad = Adviser::whereBetween('adviser_date_time',[$startKhordad,$endKhordad])->count();
         $AcceptKhordad = Number::whereBetween('accept_date_time',[$startKhordad,$endKhordad])->count();
         $OperatorTir = Number::whereBetween('operator_date_time',[$startTir,$endTir])->count();
         $AdviserTir = Adviser::whereBetween('adviser_date_time',[$startTir,$endTir])->count();
         $AcceptTir = Number::whereBetween('accept_date_time',[$startTir,$endTir])->count();
         $OperatorMordad = Number::whereBetween('operator_date_time',[$startMordad,$endMordad])->count();
         $AdviserMordad = Adviser::whereBetween('adviser_date_time',[$startMordad,$endMordad])->count();
         $AcceptMordad = Number::whereBetween('accept_date_time',[$startMordad,$endMordad])->count();
         $OperatorShahrivar = Number::whereBetween('operator_date_time',[$startShahrivar,$endShahrivar])->count();
         $AdviserShahrivar = Adviser::whereBetween('adviser_date_time',[$startShahrivar,$endShahrivar])->count();
         $AcceptShahrivar = Number::whereBetween('accept_date_time',[$startShahrivar,$endShahrivar])->count();
         $OperatorMehr = Number::whereBetween('operator_date_time',[$startMehr,$endMehr])->count();
         $AdviserMehr = Adviser::whereBetween('adviser_date_time',[$startMehr,$endMehr])->count();
         $AcceptMehr = Number::whereBetween('accept_date_time',[$startMehr,$endMehr])->count();
         $OperatorAban = Number::whereBetween('operator_date_time',[$startAban,$endAban])->count();
         $AdviserAban = Adviser::whereBetween('adviser_date_time',[$startAban,$endAban])->count();
         $AcceptAban = Number::whereBetween('accept_date_time',[$startAban,$endAban])->count();
         $OperatorAzar = Number::whereBetween('operator_date_time',[$startAzar,$endAzar])->count();
         $AdviserAzar = Adviser::whereBetween('adviser_date_time',[$startAzar,$endAzar])->count();
         $AcceptAzar = Number::whereBetween('accept_date_time',[$startAzar,$endAzar])->count();
         $OperatorDei = Number::whereBetween('operator_date_time',[$startDei,$endDei])->count();
         $AdviserDei = Adviser::whereBetween('adviser_date_time',[$startDei,$endDei])->count();
         $AcceptDei = Number::whereBetween('accept_date_time',[$startDei,$endDei])->count();
         $OperatorBahman = Number::whereBetween('operator_date_time',[$startBahman,$endBahman])->count();
         $AdviserBahman = Adviser::whereBetween('adviser_date_time',[$startBahman,$endBahman])->count();
         $AcceptBahman = Number::whereBetween('accept_date_time',[$startBahman,$endBahman])->count();
         $OperatorEsfand = Number::whereBetween('operator_date_time',[$startEsfand,$endEsfand])->count();
         $AdviserEsfand = Adviser::whereBetween('adviser_date_time',[$startEsfand,$endEsfand])->count();
         $AcceptEsfand = Number::whereBetween('accept_date_time',[$startEsfand,$endEsfand])->count();


         $mount = Jalalian::forge('today')->format('m');

         $Operator1 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/01 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/01 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator2 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/02 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/02 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator3 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/03 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/03 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator4 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/04 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/04 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator5 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/05 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/05 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator6 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/06 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/06 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator7 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/07 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/07 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator8 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/08 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/08 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator9 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/09 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/09 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator10 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/10 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/10 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator11 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/11 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/11 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator12 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/12 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/12 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator13 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/13 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/13 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator14 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/14 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/14 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator15 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/15 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/15 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator16 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator17 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/17 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/17 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator18 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/18 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/18 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator19 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/19 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/19 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator20 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/20 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/20 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator21 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/21 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/21 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator22 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/22 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator23 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/23 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/23 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator24 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/24 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/24 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator25 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/25 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/25 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator26 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/26 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/26 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator27 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/27 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/27 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator28 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/28 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/28 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator29 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/29 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/29 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator30 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/30 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/30 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Operator31 = Number::whereBetween('operator_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/31 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/31 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();

         $Adviser1 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/01 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/01 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser2 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/02 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/02 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser3 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/03 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/03 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser4 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/04 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/04 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser5 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/05 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/05 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser6 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/06 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/06 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser7 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/07 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/07 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser8 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/08 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/08 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser9 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/09 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/09 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser10 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/10 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/10 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser11 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/11 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/11 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser12 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/12 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/12 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser13 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/13 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/13 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser14 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/14 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/14 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser15 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/15 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/15 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser16 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser17 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/17 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/17 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser18 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/18 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/18 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser19 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/19 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/19 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser20 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/20 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/20 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser21 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/21 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/21 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser22 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/22 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser23 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/23 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/23 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser24 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/24 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/24 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser25 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/25 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/25 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser26 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/26 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/26 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser27 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/27 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/27 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser28 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/28 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/28 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser29 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/29 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/29 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser30 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/30 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/30 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Adviser31 = Adviser::whereBetween('adviser_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/31 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/31 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();


         $Accept1 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/01 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/01 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept2 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/02 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/02 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept3 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/03 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/03 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept4 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/04 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/04 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept5 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/05 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/05 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept6 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/06 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/06 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept7 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/07 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/07 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept8 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/08 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/08 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept9 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/09 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/09 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept10 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/10 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/10 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept11 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/11 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/11 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept12 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/12 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/12 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept13 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/13 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/13 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept14 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/14 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/14 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept15 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/15 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/15 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept16 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept17 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/17 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/17 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept18 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/18 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/18 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept19 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/19 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/19 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept20 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/20 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/20 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept21 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/21 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/21 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept22 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/22 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/16 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept23 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/23 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/23 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept24 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/24 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/24 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept25 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/25 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/25 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept26 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/26 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/26 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept27 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/27 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/27 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept28 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/28 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/28 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept29 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/29 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/29 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept30 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/30 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/30 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();
         $Accept31 = Number::whereBetween('accept_date_time',[Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/31 00:00:00")->toCarbon("Y-m-d H:i:s"),
             Jalalian::fromFormat('Y/m/d H:i:s',  $year."/".$mount."/31 23:59:59")->toCarbon("Y-m-d H:i:s")])->count();


         return view('admin.static',
         compact('reserves','reservesSum','levels','orders','ordersSum','users','doctors','tickets','reviews','doctorsList',
         'noActionNumbers','operatorsNumbers','advisersNumbers','reservedNumbers','upgrades','upgradesSum','confirmAnalysis','responseAalysis',
        'OperatorFarvardin','AdviserFarvardin','AcceptFarvardin','OperatorOrdibehesht','AdviserOrdibehesht','AcceptOrdibehesht',
        'OperatorKhordad','AdviserKhordad','AcceptKhordad','OperatorTir','AdviserTir','AcceptTir',
        'OperatorMordad','AdviserMordad','AcceptMordad','OperatorShahrivar','AdviserShahrivar','AcceptShahrivar',
        'OperatorMehr','AdviserMehr','AcceptMehr','OperatorAban','AdviserAban','AcceptAban',
        'OperatorAzar','AdviserAzar','AcceptAzar','OperatorDei','AdviserDei','AcceptDei',
        'OperatorBahman','AdviserBahman','AcceptBahman','OperatorEsfand','AdviserEsfand','AcceptEsfand','mount',
        'Operator1','Operator2','Operator3','Operator4','Operator5','Operator6','Operator7','Operator8','Operator9','Operator10','Operator11','Operator12',
        'Operator13','Operator14','Operator15','Operator16','Operator17','Operator18','Operator19','Operator20','Operator21','Operator22','Operator23','Operator24',
        'Operator25','Operator26','Operator27','Operator28','Operator29','Operator30','Operator31',
         'Adviser1','Adviser2','Adviser3','Adviser4','Adviser5','Adviser6','Adviser7','Adviser8','Adviser9','Adviser10','Adviser11','Adviser12',
         'Adviser13','Adviser14','Adviser15','Adviser16','Adviser17','Adviser18','Adviser19','Adviser20','Adviser21','Adviser22','Adviser23','Adviser24',
         'Adviser25','Adviser26','Adviser27','Adviser28','Adviser29','Adviser30','Adviser31',
         'Accept1','Accept2','Accept3','Accept4','Accept5','Accept6','Accept7','Accept8','Accept9','Accept10','Accept11','Accept12',
         'Accept13','Accept14','Accept15','Accept16','Accept17','Accept18','Accept19','Accept20','Accept21','Accept22','Accept23','Accept24',
         'Accept25','Accept26','Accept27','Accept28','Accept29','Accept30','Accept31'));

     }


    public function fetch_cities()
    {
        $provance_id = request('provance_id');
        $cities = City::where('province_id',$provance_id)->get();
        return response()->json(['cities'=>$cities],200);
    }

    public function servicefetch()
    {
      $keyword = request('term');
      if(isset($keyword) && strlen($keyword)>2)
      {
        $services = ServiceDetail::where('name','like','%'.$keyword.'%')->get();
      }
      else
      {
         return null;
      }

      return new ServiceCollection($services);
    }

    public function detailsfetch()
    {
        $service = request('service');
        $details = ServiceDetail::where('status',Status::Active)->where('service_id',$service)
        ->orderBy('name','asc')->get();

        return response()->json(['details'=>$details],200);
    }

    public function doctorsfetch()
    {
       $service = request('service');
       $service = ServiceDetail::with('doctors','advisers','branches')->find($service);
       $docors = new DoctorCollection($service->doctors);
       $advisers = new DoctorCollection($service->advisers);
       return response()->json(['doctors'=>$docors,
                                'advisers'=>$advisers,
                                'branches'=>$service->branches],200);
    }

    public function videoupload(Request $request)
    {
        //        //اجازه دسترسی
        //        config(['auth.defaults.guard' => 'admin']);
        //        $this->authorize('doctors.upload');

        $request->validate(
            [
                'video' =>'required|mimes:mp4,mov,ogg | max:102400',
            ],
            [
                "video.required" => "* الزامی است.",
                "video.max"=>"حداکثر حجم مجاز برای تصویر شما 100 مگابایت است.",
                "video.image"=>"* فرمت‌های مجاز mp4,mov,ogg",
            ]);

         $videoService = new VideoService();
         $path = $videoService->path();
         $video = $videoService->upload($request->video,$path);
         $url =  url('/').'/'.$video;

        return response()->json([
            'url'=> $url
        ],200);
    }

}
