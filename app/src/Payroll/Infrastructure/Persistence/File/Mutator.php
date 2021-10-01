<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File;

use DateInterval;
use Exception;
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

    public function getData(string $dir = 'input.txt'):array
    {
        try {
            $input = FileReader::nextLine($dir);

            if (empty($input)) throw new Exception('no records found in payroll');
            $data = [];
            foreach ($input as $item) {
                $data[] = $this->getWorkingDays($item);
            }

            return $data;
        } catch (Exception $e) {
            echo json_encode([
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }

    private function getWorkingDays(string $item): Employee
    {
        try {
            $row = explode('=', $item);
            $name =& $row[0];
            $days = explode(',', $row[1]);
            $data = [];
            foreach ($days as $value) {
                $d = preg_replace("/[^A-Z]+/", "", $value);
                $normalDay =& $this->days[$d];

                if (is_null($normalDay))
                    throw new Exception("there is an error in the register, it may be due to the order and format of the register for the employee named $name");

                $data[$d] = array_map(function ($hour) use ($normalDay, $name) {
                    $myNormalDay = clone $normalDay;
                    $hm = explode(':', $hour);

                    if (!is_numeric($hm[1])) throw new Exception("There is no numeric value in your timestamp for the employee named $name");

                    $minutes = $hm[1]*60;// in seconds
                    $myNormalDay->add(new DateInterval("PT$hm[0]H{$minutes}S"));
                    return $myNormalDay;
                }, explode('-', str_replace($d,'',$value)));
            }
            return new Employee(new Name($name), $data, $this->hourly);
        } catch (Exception $e) {
            echo json_encode([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
            exit();
        }
    }
}