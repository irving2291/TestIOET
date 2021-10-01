<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File;


final class FileReader
{
    public static function nextLine(string $dir): array
    {
        $output = [];
        $file = fopen($dir, "r");
        while(!feof($file)){
            $output[] = fgets($file);
        }
        fclose($file);
        return $output;
    }
}