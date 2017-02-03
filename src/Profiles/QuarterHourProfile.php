<?php

namespace peterrehm\TimeProfiles\Profiles;

use peterrehm\TimeProfiles\Config\CalculationMonths;

class QuarterHourProfile extends AbstractTimeProfile
{
    public function __construct(float $defaultValue = 0.00)
    {
        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    for ($interval = 0; $interval <= 45; $interval += 15) {
                        $this->profile[$month][$day][$hour][$interval] = $defaultValue;
                    }
                }
            }
        }
    }
}
