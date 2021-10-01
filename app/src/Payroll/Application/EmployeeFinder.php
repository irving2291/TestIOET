<?php

namespace Irving2291\Ioettest\Payroll\Application;

use Irving2291\Ioettest\Payroll\Domain\EmployeeRepository;

class EmployeeFinder
{
    private EmployeeRepository $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke()
    {

    }

    public function all()
    {
        return $this->repository->all();
    }
}