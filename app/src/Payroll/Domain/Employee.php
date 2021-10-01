<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Domain;

use Irving2291\Ioettest\Payroll\Domain\ValueObject\Name;

final class Employee
{
    private Name $name;
    private Payment $payment;

    public function __construct(Name $name, array $paymentPayload, array $hourly)
    {
        $this->name = $name;
        $this->payment = new Payment($paymentPayload, $hourly);
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function __toString()
    {
        return "The amount to pay {$this->name->value()} is {$this->getPayment()->getRemuneration()->getFormatValue()} USD";
    }
}