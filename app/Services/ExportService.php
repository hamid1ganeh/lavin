<?php


namespace App\Services;

use App\Models\Number;

class ExportService
{
    public function exel(string $titles,string $records,string $filename)
    {
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Content-Transfer-Encoding: binary');
        header("Pragma: no-cache");
        header("Expires: 0");

        echo chr(255).chr(254).iconv("UTF-8", "UTF-16LE//IGNORE", $titles . "\n" . $records . "\n");

        exit();
    }

}
