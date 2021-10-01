<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File;

use DateInterval;
use Irving2291\Ioettest\Payroll\Domain\Employee;
use Irving2291\Ioettest\Payroll\Domain\ValueObject\Name;

final class Mutator
{
    private array $days;
    private array $hourly;
    public function __construct()
    {
        $rootPath = dirname(__DIR__);
        $this->hourly = require_once "$rootPath/File/hourly.php";
        $this->days = require_once "$rootPath/File/days.php";
    }

    public function getData():array
    {
        $input = FileReader::nextLine('input.txt');
        $data = [];
        foreach ($input as $item) {
            $data[] = $this->getWorkingDays($item);
        }

        return $data;
    }

    private function getWorkingDays(string $item): Employee
    {


        $row = explode('=', $item);
        $days = explode(',', $row[1]);

        $data = [];
        foreach ($days as $value) {
            $d = preg_replace("/[^A-Z]+/", "", $value);
            $normalDay = $this->days[$d];
            $data[$d] = array_map(function ($hour) use ($normalDay) {
                $myNormalDay = clone $normalDay;
                $hm = explode(':', $hour);
                $minutes = $hm[1]*60;// in seconds
                $myNormalDay->add(new DateInterval("PT{$hm[0]}H{$minutes}S"));
                return $myNormalDay;
            }, explode('-', str_replace($d,'',$value)));
        }
        $employee = new Employee(new Name($row[0]), $data);
        $employee->setHourly($this->hourly);
        return $employee;
    }
}