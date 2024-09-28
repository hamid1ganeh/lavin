<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use nusoap_client;
use App\Modles\SmsHistory;


class SMS
{
    public function send($mobiles,$text)
    {
        if (env('APP_ENV') == 'local'){
            foreach ($mobiles as $mobile){
                Log::info("SMS ".$text." is sand to:".$mobile);
            }
        }else {
            $client = new nusoap_client('http://my.candoosms.com/services/?wsdl', true);
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = false;
            $client->call('Send', array('username' => env('SMS_USERNAME'),'password' => env('SMS_PASSWORD'),'srcNumber' => env('SMS_NUMBER'),'body' => $text,'destNo' => $mobiles,'flash' => '0'));
        }

    }
}
