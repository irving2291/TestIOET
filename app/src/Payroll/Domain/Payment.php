<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Domain;

use DateTime;
use Irving2291\Ioettest\Payroll\Domain\Adapters\HourlyAdapter;
use Irving2291\Ioettest\Payroll\Domain\ValueObject\ToPay;
use function Couchbase\defaultDecoder;

final class Payment
{
    private array $payload;
    private ToPay $toPay;
    private array $hourly;
    private HourlyAdapter $hourlyAdapter;

    public function __construct(array $payload, $hourly)
    {
        $this->payload = $payload;
        $this->toPay = new ToPay(0.0);
        $this->hourlyAdapter = new HourlyAdapter($hourly);
        $this->hourly = $hourly;
    }

    /**
     * string to second hour and minutes
     * @param string $h
     * @param string $m
     * @return int
     */
    private function toSecond(string $h, string $m): int
    {
        return (intval($h)*60) + intval($m);
    }

    private function calculateSalary(float $salaryHour, int $seconds):float
    {
        return floatval(($salaryHour / 60) * $seconds);
    }

    /**
     * The algorithm of this method evaluates the amount to be paid per hour worked by inverting
     * the hours of the working day and taking the coincidence of the entry time and at the same
     * time calculating the time difference against the end of the range of the working day
     * @param float $toPay
     * @param array $hourly
     * @param int $hoursWorked
     * @param $initTime
     * @return float
     */
    private function sumRecursive(float $toPay, array $hourly, int $hoursWorked, $initTime):float
    {
        foreach ($hourly as $betweenHour => $salary) {
            $betweenData = explode('-', $betweenHour);
            if ($initTime>=$betweenData[0]) {
                unset($hourly[$betweenHour]);
                $diffHour = $toPay>0?($betweenData[1]-$initTime)+1:($betweenData[1]-$initTime);
                return (($diffHour - $hoursWorked) > 0)
                    ? $toPay + $this->calculateSalary($salary, $hoursWorked)
                    : $this->sumRecursive(
                        $toPay + $this->calculateSalary($salary, $diffHour)
                        , $hourly, ($hoursWorked-$diffHour), ($initTime+$diffHour)+1);
            }
        }
        return $toPay;
    }

    public function calculate(): void
    {
        $toPay = 0;
        foreach ($this->payload as $keyDay => $workTime) {
            $initTime = $this->toSecond(
                $workTime[0]->format("H"),
                $workTime[0]->format("i")
            );
            $endTime = $this->toSecond(
                $workTime[1]->format("H"),
                $workTime[1]->format("i")
            );

            $hoursWorked = $endTime - $initTime;

            $hourly = $this->getHourly();
            $hourlyReverse = $this->getHourly()->getList($workTime[0]);
            $toPay += $this->sumRecursive(0, $hourlyReverse, $hoursWorked, $initTime);
        }

        $this->toPay->setValue($toPay);
    }

    /**
     * detect if it is weekday or weekend
     * @param DateTime $date
     * @return bool
     */
    private function isWeekend(DateTime $date): bool
    {
        return (date('N', strtotime($date->format('y-m-d'))) >= 6);
    }

    public function getHourly(): HourlyAdapter
    {

        return $this->hourlyAdapter;
    }

    /**
     * returns the value to pay, if it is empty it tries to calculate it
     * @return ToPay
     */
    public function getRemuneration(): toPay
    {
        if (!$this->toPay->isEmpty()) return $this->toPay;
        else {
            $this->calculate();
            return $this->toPay;
        }
    }

    public function setHourly(array $hourly)
    {
        $this->hourly = $hourly;
    }
}