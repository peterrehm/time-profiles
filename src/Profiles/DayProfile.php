<?php

namespace peterrehm\TimeProfiles\Profiles;

use peterrehm\TimeProfiles\Config\CalculationMonths;

class DayProfile implements TimeProfile
{
    public $profile = [];

    public function __construct(float $defaultValue = 0.00)
    {
        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                $this->profile[$month][$day] = $defaultValue;
            }
        }
    }

    public function getTotal() : float
    {
        $total = 0.00;

        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                $total += $this->profile[$month][$day];
            }
        }

        return $total;
    }
}
