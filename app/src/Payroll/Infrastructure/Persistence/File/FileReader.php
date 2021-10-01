<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File;


use Exception;

final class FileReader
{
    public static function nextLine(string $dir): array
    {
        try {
            if (!file_exists($dir)) throw new Exception('file does not exist');

            $output = [];
            $file = fopen($dir, "r");
            while(!feof($file)){
                $read = fgets($file);
                ($read && !empty(trim((string)$read))) && $output[] = trim((string)$read);
            }
            fclose($file);
            return $output;

        } catch (Exception $e) {
            echo json_encode([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }
}