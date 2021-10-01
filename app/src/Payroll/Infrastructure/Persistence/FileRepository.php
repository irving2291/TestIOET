<?php

namespace Irving2291\Ioettest\Payroll\Infrastructure\Persistence;

use Irving2291\Ioettest\Payroll\Domain\Employee;
use Irving2291\Ioettest\Payroll\Domain\EmployeeRepository;
use Irving2291\Ioettest\Payroll\Domain\ValueObject\Name;

final class FileRepository implements EmployeeRepository
{

    public function all(): array
    {
        return (new \Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File\Mutator())->getData();
    }

    public function find(Name $name): Employee
    {
        return new Employee($name, []);
    }
}