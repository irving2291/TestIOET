<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Shared\Domain\ValueObject;

use Exception;

class AlphabetAndUppercase
{
    /**
     * val
     * @param string $value
     */
    protected function validateOnlyAlphabetAndUppercase(string $value)
    {
        try {
            if (!preg_match("/^[A-Z]+$/", $value))
                throw new Exception('only uppercase alphabet allowed');

        } catch (Exception $e) {

            echo json_encode([
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }
}