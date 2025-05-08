<?php


namespace App\Enums;


class WareHoseOrderResult extends BaseEnum
{
    const   ACCEPT  = '0';//تایید شده
    const   COST  = '1'; // هزینه
    const   DOCUMENT  = '2'; // صدور سند مالی

    public static function validKeys()
    {
        return array(
            self::ACCEPT,
            self::COST,
            self::DOCUMENT

        );
    }

    public static function validValues()
    {
        return array(
            self::ACCEPT => 'تایید شده',
            self::COST => 'هزینه',
            self::DOCUMENT => 'صدور سند مالی'
        );
    }

    public function getKeys()
    {
        return self::validKeys();
    }

    public function getValues()
    {
        return self::validValues();
    }
}
