<?php


namespace App\Enums;


class ReserveStatus
{
    const   waiting  = '0';//در انتظار رزرو
    const   confirm  = '1';//تایید
    const   cancel = '2';//لغو
    const   accept = '3';//پذیرش
    const   secratry = '4';//ارجاع به منشی
    const   done = '5';//انجام شده
    const   paid = '6';//پرداخت شده
    const   end = '7';//پایان کار
    const   wittingForAdviser = '8';// در انتظار مشاور
    const   Adviser = '9';// مشاور


    const getReseveStatus = [self::waiting,
                            self::confirm,
                            self::cancel,
                            self::accept,
                            self::secratry,
                            self::done,
                            self::paid,
                            self::end,
                            self::wittingForAdviser,
                            self::Adviser];


}
