<?php

namespace peterrehm\TimeProfiles\Profiles;

class MonthProfile extends AbstractTimeProfile
{
    public function __construct(float $defaultValue = 0.00)
    {
        for ($month = 1; $month <= 12; $month++) {
            $this->profile[$month] = $defaultValue;
        }
    }
}
