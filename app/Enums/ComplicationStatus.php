<?php


namespace App\Enums;


class ComplicationStatus
{
    const   pending  = '0'; //درانتظار
    const   following   = '1'; // در حال پیگیری
    const   followed  = '2';//پیگیری شده
    const   cancel  = '3';//رد شده
    const   treatment  = '4';// درمان عارضه
}
