<?php

namespace peterrehm\TimeProfiles\Config;

use Assert\Assertion;

class CalculationMonths
{
    public static $days = [
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31
    ];

    public static function getDays(int $month) : int
    {
        Assertion::range($month, 1, 12);
        return self::$days[$month];
    }
}
