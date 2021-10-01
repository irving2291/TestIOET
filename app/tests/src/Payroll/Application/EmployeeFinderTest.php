<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Test\Payroll\Application;

use Irving2291\Ioettest\Payroll\Application\EmployeeFinder;
use Irving2291\Ioettest\Payroll\Domain\Employee;
use Irving2291\Ioettest\Payroll\Domain\EmployeeRepository;
use Irving2291\Ioettest\Payroll\Domain\Payment;
use Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File\FileReader;
use Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File\Mutator;
use Irving2291\Ioettest\Payroll\Infrastructure\Persistence\FileRepository;
use PHPUnit\Framework\TestCase;

class EmployeeFinderTest extends TestCase
{
    /** @test */
    public function it_should_find_a_valid_employee(): void
    {
        $repository = $this->createMock(EmployeeRepository::class);
        $repository->method('all');
        $finder = new EmployeeFinder($repository);
    }

    /**
     * this should return the arrangement obtained by the finder with the employee instance
     * @test
     */
    public function should_return_array_finder_employee_instance()
    {
        $finder = new EmployeeFinder(new FileRepository());

        foreach ($finder->all() as $employee) {
            $this->assertInstanceOf(Employee::class, $employee);
        }
    }
}