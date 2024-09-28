<?php

namespace App\Enums;

class genderType
{
    const   male = '0'; //مرد
    const   female  = '1'; //زن
    const   LGBTQ  = '2'; //LGBTQ
    const getGenderList = [ 'مرد'=> self::male,
                            'زن'=>self::female,
                            'LGBTQ'=>self::LGBTQ];
}
