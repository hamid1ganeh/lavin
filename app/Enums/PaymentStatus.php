<?php


namespace App\Enums;


class PaymentStatus
{
    const   unpaid  = '0';//پرداخت نشد
    const   payding  = '1';//درحال پرداخت
    const   paid = '2';//پرداخت موفق
    const   feild = '3';//پرداخت ناموفق
}
