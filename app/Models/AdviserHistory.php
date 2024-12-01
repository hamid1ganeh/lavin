<?php

namespace App\Models;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Model;

class AdviserHistory extends Model
{
    protected $table = "adviser_histories";
    protected $fillable=['adviser_id','admin_id','until','description','festival_id','answered_at'];

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }

    public function adviser()
    {
        return $this->belongsTo(Adviser::class);
    }

    public function festival()
    {
        return $this->belongsTo(Festival::class);
    }

    public function since()
    {
      return Jalalian::forge($this->created_at)->format('d %B Y H:i:s');
    }

    public function answeredAt()
    {
        if(is_null($this->answered_at)){
            return null;
        }
        return Jalalian::forge($this->nswered_at)->format('d %B Y H:i:s');
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

    public function scopeFilter($query)
    {
        $advisers = request('advisers');
        if(isset($advisers) && $advisers != '')
        {
            $query->whereIn('admin_id',$advisers);
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
