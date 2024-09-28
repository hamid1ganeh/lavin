<?php


namespace App\Enums;


class Status
{
    const   Deactive  = '0';
    const   Active  = '1';
    const   Pending = '2';

    const getStatusList = [ 'غیرفعال'=> self::Deactive,
        'فعال'=>self::Active,
        'در انتظار'=>self::Pending];
}
