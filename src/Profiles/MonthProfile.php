<?php

namespace peterrehm\TimeProfiles\Profiles;

class MonthProfile implements TimeProfile
{
    public $profile = [];

    public function __construct(float $defaultValue = 0.00)
    {
        for ($month = 1; $month <= 12; $month++) {
            $this->profile[$month] = $defaultValue;
        }
    }

    public function getTotal() : float
    {
        $total = 0.00;

        for ($month = 1; $month <= 12; $month++) {
            $total += $this->profile[$month];
        }

        return $total;
    }
}
