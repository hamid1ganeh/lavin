<?php

namespace App\Models;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Model;

class PhoneOperatorHistory extends Model
{
    protected $table = "phone_operator_histories";
    protected $fillable=['number_id','admin_id','until','description','festival_id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }

    public function number()
    {
        return $this->belongsTO(Number::class);
    }

    public function festival()
    {
        return $this->belongsTo(Festival::class);
    }

    public function since()
    {
      return Jalalian::forge($this->created_at)->format('d %B Y H:i:s');
    }

    public function until()
    {
        if($this->until === null)
        {
            return "هم اکنون";
        }
        else
        {
            return Jalalian::forge($this->until)->format('d %B Y H:i:s');
        }
    }

    public function updatedAt()
    {
        return Jalalian::forge($this->updated_at)->format('d %B Y H:i:s');
    }

    public function scopeFilter($query)
    {
        $operators = request('operators');
        if(isset($operators) && $operators != '')
        {
            $query->whereIn('admin_id',$operators);
        }

        $since_refer = request('since_refer');
        if(isset($since_refer) &&  $since_refer!='')
        {
            $since_refer =  faToEn($since_refer);
            $since_refer = Jalalian::fromFormat('Y/m/d H:i', $since_refer)->toCarbon("Y-m-d H:i");
            $query->where('created_at','>=', $since_refer);
        }

        $until_refer = request('until_refer');
        if(isset($until_refer) &&  $until_refer!='')
        {
            $until_refer =  faToEn($until_refer);
            $until_refer = Jalalian::fromFormat('Y/m/d H:i', $until_refer)->toCarbon("Y-m-d H:i");
            $query->where('until','<=', $until_refer);
        }

        $since_response = request('since_response');
        if(isset($since_response) &&  $since_response!='')
        {
            $since_response =  faToEn($since_response);
            $since_response = Jalalian::fromFormat('Y/m/d H:i', $since_response)->toCarbon("Y-m-d H:i");
            $query->where('updated_at','>=', $since_response)->whereNotNull('description');
        }

        $until_response = request('until_response');
        if(isset($until_response) &&  $until_response!='')
        {
            $until_response =  faToEn($until_response);
            $until_response = Jalalian::fromFormat('Y/m/d H:i', $until_response)->toCarbon("Y-m-d H:i");
            $query->where('updated_at','<=', $until_response)->whereNotNull('description');
        }
    }
}
