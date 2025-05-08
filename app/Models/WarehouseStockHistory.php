<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;
use App\Enums\WareHoseOrderResult;

class WarehouseStockHistory extends Model
{
    protected $fillable = ['number', 'warehouse_id', 'moved_warehouse_id', 'goods_id', 'event', 'stock', 'less', 'less_result', 'created_by', 'delivered_by', 'confirmed_by', 'delivered_at'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function movedWarehose()
    {
        return $this->belongsTo(Warehouse::class, 'moved_warehouse_id', 'id');
    }

    public function good()
    {
        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(Admin::class, 'delivered_by', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }

    public function deliveredAt()
    {
        if (is_null($this->delivered_at)) {
            return null;
        }
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s - Y/m/d', strtotime($this->delivered_at)));
    }

    public function createdAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d', strtotime($this->created_at)));
    }

    public function event()
    {
        if ($this->event == '+') {
            return 'دریافتی';
        }

        if ($this->event == '-') {
            return 'مرجوعی';
        }

        if ($this->event == '0') {
            return 'ارسالی';
        }
    }

    public function countStock()
    {
        return (int)(($this->stock - $this->less) / $this->good->value_per_count);
    }

    public function remainderStock()
    {
        return fmod(($this->stock - $this->less), $this->good->value_per_count);
    }

    public function stock()
    {
        $count = $this->countStock();
        $remainder = $this->remainderStock();
        $result = '';

        if ($count > 0) {
            $result .= $count . ' عدد ';

            if ($remainder > 0) {
                $result .= ' و ';
            }
        }

        if ($remainder > 0) {
            $result .= $remainder . ' ' . $this->good->unit;
        }

        return $result;
    }

    public function countLess()
    {
        return (int)($this->less / $this->good->value_per_count);
    }

    public function remainderLess()
    {
        return fmod($this->less, $this->good->value_per_count);
    }

    public function less()
    {
        $count = $this->countLess();
        $remainder = $this->remainderLess();
        $result = '';

        if ($count > 0) {
            $result .= $count . ' عدد ';

            if ($remainder > 0) {
                $result .= ' و ';
            }
        }

        if ($remainder > 0) {
            $result .= $remainder . ' ' . $this->good->unit;
        }

        return $result;
    }


    public function getLessResult()
    {
        if (is_null($this->less_result)) {
            return "";
        }

        return  WareHoseOrderResult::validValues()[$this->less_result];
    }

    public function scopeFilter($query)
    {

        $event = request('event');
        if (isset($event) && $event != '') {
            $query->whereIn('event', $event);
        }

        $goods = request('goods');
        if (isset($goods) && $goods != '') {
            $query->whereIn('goods_id', $goods);
        }

        $lessResult = request('less_result');
        if (isset($lessResult) && $lessResult != '') {
            $query->whereIn('less_result', $lessResult);
        }


        //فیلتر تاریخ  از
        $since = request('since');
        if (isset($since) && $since != '') {
            $since = faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d");
            $query->where('created_at', '>=', $since);
        }

        //فیلتر  تاریخ تا
        $until = request('until');
        if (isset($until) && $until != '') {
            $until = faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d");
            $query->where('created_at', '<=', $until);
        }

        //فیلتر شماره حواله
        $number = request('number');
        if (isset($number) && $number != '') {
            $query->where('number', 'like', '%' . $number . '%');
        }

        return $query;
    }
}
