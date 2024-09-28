<?php


namespace App\Enums;


class NumberStatus
{
    const   NoAction  = '0'; //بلاتکلیف
    const   Operator  = '1'; //اپراتور
    const   NoAnswer  = '2'; //عدم پاسخگویی
    const   Answer  = '3'; //پاسخ داده
    const   WaitingForAdviser  = '4'; //درخواست مشاور
    const   Adviser  = '5'; //مشاور
    const   Accept  = '7'; //پذیرش
    const   Cancel  = '8'; //لغو
    const   WaitnigForDocuments = '9';//در انتظار ارسال مدارک
    const   RecivedDocuments = '10';// دریافت مدارک
    const   Reservicd = '11'; //رزرو شده
    const   NextNotice  = '12'; //اطلاع بعدی
    const   Confirm = '13';//تایید شده
    const   Done = '14';//انجام شده


}
