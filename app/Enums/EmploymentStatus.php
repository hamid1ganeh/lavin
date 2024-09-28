<?php


namespace App\Enums;


class EmploymentStatus
{
    const   pending = '0';//در انتظار
    const   refer = '1';//در انتظار
    const   checked  = '2';//بررسی شده
    const   reject  = '3';//رد شده
    const   interview  = '4';// مصاحبه
    const   education  = '5';// آموزش
    const   confirm  = '6';// تایید شده

    const getStatusList = ['در انتظار'=>self::pending,
                            'ارجاع'=>self::refer,
                       'بررسی شده'=>self::checked,
                       'رد شده'=>self::reject,
                       'مصاحبه'=>self::interview,
                        'آموزش'=>self::education,
                       'تایید شده'=>self::confirm];
}
