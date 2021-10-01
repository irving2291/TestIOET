<?php

namespace Irving2291\Ioettest\Payroll\Domain;

use Irving2291\Ioettest\Payroll\Domain\ValueObject\Name;

interface EmployeeRepository
{
    public function all();

    public function find(Name $name): Employee;
}