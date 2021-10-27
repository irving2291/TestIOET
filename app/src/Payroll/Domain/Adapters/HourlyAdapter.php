<?php

namespace Irving2291\Ioettest\Payroll\Domain\Adapters;


use DateTime;

class HourlyAdapter
{
    private array $hourly;

    const WEEKEND = 'weekend';
    const WEEKDAY = 'weekday';

    public function __construct(array $hourly)
    {
        $this->hourly = $hourly;
    }

    public function isWeekend(DateTime $date): bool
    {
        return (date('N', strtotime($date->format('y-m-d'))) >= 6);
    }

    public function getList(DateTime $workTime): array
    {
        $this->hourly[$this->isWeekend($workTime)];
        return array_reverse(
            $this->hourly[$this->isWeekend($workTime)
                ?self::WEEKEND
                :self::WEEKDAY]);
    }
}