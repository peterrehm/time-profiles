<?php

namespace peterrehm\TimeProfiles\Util;

use peterrehm\TimeProfiles\Config\CalculationMonths;
use peterrehm\TimeProfiles\Profiles\HourProfile;
use peterrehm\TimeProfiles\Profiles\MinuteProfile;
use peterrehm\TimeProfiles\Profiles\QuarterHourProfile;

class TimeProfileConverter
{
    /**
     * @param HourProfile $hourProfile
     * @return QuarterHourProfile
     */
    public static function hourToQuarterHour(HourProfile $hourProfile) : QuarterHourProfile
    {
        $profile = new QuarterHourProfile();

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    $quarterHourProduction = $hourProfile->profile[$month][$day][$hour] / 4;

                    for ($interval = 0; $interval <= 45; $interval +=15) {
                        $profile->profile[$month][$day][$hour][$interval] = $quarterHourProduction;
                    }
                }
            }
        }

        return $profile;
    }

    /**
     * @param HourProfile $hourProfile
     * @return MinuteProfile
     */
    public static function hourToMinute(HourProfile $hourProfile) : MinuteProfile
    {
        $profile = new MinuteProfile();

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    $minuteProduction = $hourProfile->profile[$month][$day][$hour] / 60;

                    for ($interval = 0; $interval <= 59; $interval++) {
                        $profile->profile[$month][$day][$hour][$interval] = $minuteProduction;
                    }
                }
            }
        }

        return $profile;
    }

    /**
     * @param QuarterHourProfile $quarterHourProfile
     * @return HourProfile
     */
    public static function quarterHourToHour(QuarterHourProfile $quarterHourProfile) : HourProfile
    {
        $profile = new HourProfile();

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    $hourlyProduction = 0.00;
                    for ($interval = 0; $interval <= 45; $interval+=15) {
                        $hourlyProduction += $quarterHourProfile->profile[$month][$day][$hour][$interval];
                    }
                    $profile->profile[$month][$day][$hour] = $hourlyProduction;
                }
            }
        }

        return $profile;
    }

    /**
     * @param QuarterHourProfile $quarterHourProfile
     * @return MinuteProfile
     */
    public static function quarterHourToMinute(QuarterHourProfile $quarterHourProfile) : MinuteProfile
    {
        $profile = new MinuteProfile();

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                for ($hour = 0; $hour <= 23; $hour++) {
                    for ($interval = 0; $interval <= 45; $interval+=15) {
                        $minuteProduction = $quarterHourProfile->profile[$month][$day][$hour][$interval] / 15;
                        for ($i = $interval; $i < ($interval + 15); $i++) {
                            $profile->profile[$month][$day][$hour][$i] = $minuteProduction;
                        }
                    }
                }
            }
        }

        return $profile;
    }
}
