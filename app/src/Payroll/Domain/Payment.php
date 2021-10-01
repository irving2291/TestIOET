<?php

declare(strict_types=1);

namespace Irving2291\Ioettest\Payroll\Domain;

use DateTime;
use Irving2291\Ioettest\Payroll\Domain\ValueObject\ToPay;

final class Payment
{
    private array $payload;
    private ToPay $toPay;
    private array $hourly;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
        $this->toPay = new ToPay(0.0);
    }

    /**
     * The algorithm of this method evaluates the amount to be paid per hour worked by inverting
     * the hours of the working day and taking the coincidence of the entry time and at the same
     * time calculating the time difference against the end of the range of the working day
     *
     * @param DateTime $workTimeInit
     * @param DateTime $workTimeEnd
     * @param $hourly
     * @return float
     */
    private function sumValue(DateTime $workTimeInit, DateTime $workTimeEnd, $hourly):float
    {
        $interval = $workTimeInit->diff($workTimeEnd);
        $initTime = $workTimeInit->format("H:i");
        //$endTime = $workTimeEnd->format("H:i");

        $hoursWorked = $interval->format("%h:%i");
        $toPay = 0;
        foreach (array_reverse($hourly) as $betweenHour => $salary) {
            $betweenData = explode('-', $betweenHour);
            $betweenData[1] = ($betweenData[1] == !"00:00")?$betweenData[1]:"24:00"; //replacement 0 hours for 24
            if ($initTime>=$betweenData[0]) {//check if the start time of the working day is greater than or equal to that of a work period
                $diffHour = $betweenData[1]-$initTime;// obtain the time difference and it is calculated against the difference of the working period to know if values are generated from another period
                if ($diffHour - $hoursWorked > 0) {
                    $houMin = explode(':',$hoursWorked);
                    $toPay += ($salary / 60) * ((float)str_replace(':','', ($houMin[0] * 60 + $houMin[1])));
                    break;
                }
            }
        }
        return $toPay;
    }

    public function calculate(): void
    {
        $toPay = 0;
        foreach ($this->payload as $keyDay => $day) {

            $toPay += $this->sumValue(
                $day[0],
                $day[1],
                $this->getHourly()[$this->isWeekend($day[0])?'weekend':'weekday']
            );
        }

        $this->toPay->setValue($toPay);
    }

    private function isWeekend(DateTime $date): bool
    {
        return (date('N', strtotime($date->format('y-m-d'))) >= 6);
    }

    public function getHourly(): array
    {
        return $this->hourly;
    }

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