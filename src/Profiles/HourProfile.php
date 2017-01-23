<?php

namespace peterrehm\TimeProfiles\Profiles;

use peterrehm\TimeProfiles\Config\CalculationMonths;

class HourProfile implements TimeProfile
{
    public $profile = [];

    public function __construct(float $defaultValue = 0.00)
    {
        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    $this->profile[$month][$day][$hour] = $defaultValue;
                }
            }
        }
    }

    public function getTotal() : float
    {
        $total = 0.00;

        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    $total += $this->profile[$month][$day][$hour];
                }
            }
        }

        return $total;
    }
}
