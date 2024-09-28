<?php

namespace App\Enums;

class TicketPriority
{
    const   Low  = '0';//کم
    const   Medium  = '1';//متوسط
    const   High = '2';//زیاد

    const getTicketPriorityList = [ 'کم'=> self::Low,
                                'متوسط'=>self::Medium,
                                'زیاد'=>self::High];

}
