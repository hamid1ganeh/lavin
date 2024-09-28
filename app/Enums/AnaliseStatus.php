<?php


namespace App\Enums;


class AnaliseStatus
{
    const   pending  = '0'; //در انتظار
    const   doctor  = '1';//ارجاع به پزشک
    const   response  = '2';//پاسخ پزشک
    const   reject  = '3';//رد شده
    const   accept  = '4';//تایید شده
}
