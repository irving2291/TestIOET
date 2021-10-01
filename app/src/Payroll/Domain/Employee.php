<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Domain;

use Irving2291\Ioettest\Payroll\Domain\ValueObject\Name;

final class Employee
{
    private Name $name;
    private Payment $payment;
    private array $hourly;

    public function __construct(Name $name, array $paymentPayload)
    {
        $this->name = $name;
        $this->payment = new Payment($paymentPayload);
    }

    public function getPayment(): Payment
    {
        $this->payment->setHourly($this->hourly);
        return $this->payment;
    }

    public function setHourly(array $hourly)
    {
        $this->hourly = $hourly;
    }

    public function __toString()
    {
        return "The amount to pay {$this->name->value()} is {$this->getPayment()->getRemuneration()->value()} USD";
    }
}