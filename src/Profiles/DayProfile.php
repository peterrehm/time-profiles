<?php

namespace peterrehm\solar\Profiles;

use peterrehm\TimeProfiles\Config\CalculationMonths;

class DayProfile
{
    public $profile = [];

    public function __construct($defaultValue = 0.00)
    {
        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                $this->profile[$month][$day] = $defaultValue;
            }
        }
    }
}
