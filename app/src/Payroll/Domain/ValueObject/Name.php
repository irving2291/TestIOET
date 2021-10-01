<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Domain\ValueObject;

use Irving2291\Ioettest\Shared\Domain\ValueObject\AlphabetAndUppercase;

final class Name extends AlphabetAndUppercase
{
    private string $value;
    public function __construct(string $value)
    {
        $this->validateOnlyAlphabetAndUppercase($value);
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}