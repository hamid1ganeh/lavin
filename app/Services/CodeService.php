<?php
namespace App\Services;

class CodeService
{

  public function create($moel,$length)
  {
    $code = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,$length);
    if(!is_null($moel::where('code',$code)->first()))
    {
       return $this->create();
    }
    return $code;
  }

    public function refer_code($model)
    {
        $code = substr(str_shuffle(str_repeat('0123456789',5)),0,5);
        if(!is_null($model::where('code',$code)->first()))
        {
            return $this->refer_code();
        }
        return $code;
    }


}
