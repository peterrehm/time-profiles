<?php

namespace peterrehm\solar\TimeProfiles;

class MonthProfile
{
    public $profile = [];

    public function __construct($defaultValue = 0.00)
    {
        for ($month = 1; $month <= 12; $month++) {
            $this->profile[$month] = $defaultValue;
        }
    }
}
