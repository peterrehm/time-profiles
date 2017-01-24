<?php

namespace peterrehm\TimeProfiles\Util;

use peterrehm\TimeProfiles\Config\CalculationMonths;
use peterrehm\TimeProfiles\Profiles\DayProfile;
use peterrehm\TimeProfiles\Profiles\HourProfile;
use peterrehm\TimeProfiles\Profiles\MinuteProfile;
use peterrehm\TimeProfiles\Profiles\MonthProfile;
use peterrehm\TimeProfiles\Profiles\QuarterHourProfile;

class TimeProfileAggregator
{
    public static function aggregateMinuteProfile(MinuteProfile ...$minuteProfiles) : MinuteProfile
    {
        $profileCount = count($minuteProfiles);

        if ($profileCount === 1) {
            return $minuteProfiles[0];
        }

        $profile = $minuteProfiles[0];

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    for ($interval = 0; $interval <= 59; $interval++) {
                        for ($i = 1; $i < 2; $i++) {
                            $profile->profile[$month][$day][$hour][$interval] += $minuteProfiles[$i]->profile[$month][$day][$hour][$interval];
                        }
                    }
                }
            }
        }

        return $profile;
    }

    public static function aggregateQuarterHourProfile(QuarterHourProfile ...$quarterHourProfiles) : QuarterHourProfile
    {
        $profileCount = count($quarterHourProfiles);

        if ($profileCount === 1) {
            return $quarterHourProfiles[0];
        }

        $profile = $quarterHourProfiles[0];

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    for ($interval = 0; $interval <= 45; $interval+=15) {
                        for ($i = 1; $i < 2; $i++) {
                            $profile->profile[$month][$day][$hour][$interval] += $quarterHourProfiles[$i]->profile[$month][$day][$hour][$interval];
                        }
                    }
                }
            }
        }

        return $profile;
    }

    public static function aggregateHourProfile(HourProfile ...$hourProfiles) : HourProfile
    {
        $profileCount = count($hourProfiles);

        if ($profileCount === 1) {
            return $hourProfiles[0];
        }

        $profile = $hourProfiles[0];

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    for ($i = 1; $i < 2; $i++) {
                        $profile->profile[$month][$day][$hour] += $hourProfiles[$i]->profile[$month][$day][$hour];
                    }
                }
            }
        }

        return $profile;
    }

    public static function aggregateDayProfile(DayProfile ...$dayProfiles) : DayProfile
    {
        $profileCount = count($dayProfiles);

        if ($profileCount === 1) {
            return $dayProfiles[0];
        }

        $profile = $dayProfiles[0];

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                for ($i = 1; $i < 2; $i++) {
                    $profile->profile[$month][$day] += $dayProfiles[$i]->profile[$month][$day];
                }
            }
        }

        return $profile;
    }

    public static function aggregateMonthProfile(MonthProfile ...$monthProfiles) : MonthProfile
    {
        $profileCount = count($monthProfiles);

        if ($profileCount === 1) {
            return $monthProfiles[0];
        }

        $profile = $monthProfiles[0];

        for ($month = 1; $month <= 12; $month++) {
            for ($i = 1; $i < 2; $i++) {
                $profile->profile[$month] += $monthProfiles[$i]->profile[$month];
            }
        }

        return $profile;
    }
}
