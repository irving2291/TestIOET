<?php

namespace Irving2291\Ioettest\Payroll\Domain\ValueObject;

use Exception;

class ToPay
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function isEmpty():bool
    {
        return empty($this->value);
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return number_format($this->value, 2);
    }

    /*private function isValueToPay(float $value)
    {
        try {
            if ()
                throw new Exception('only uppercase alphabet allowed');
        } catch () {
            echo json_encode([
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }*/
}