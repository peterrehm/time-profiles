<?php

namespace peterrehm\TimeProfiles\Util;

use peterrehm\TimeProfiles\Config\CalculationMonths;
use peterrehm\TimeProfiles\Profiles\DayProfile;
use peterrehm\TimeProfiles\Profiles\HourProfile;
use peterrehm\TimeProfiles\Profiles\MinuteProfile;
use peterrehm\TimeProfiles\Profiles\MonthProfile;
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

    /**
     * @param QuarterHourProfile $quarterHourProfile
     * @return DayProfile
     */
    public static function quarterHourToDay(QuarterHourProfile $quarterHourProfile) : DayProfile
    {
        $profile = new DayProfile();

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                $totalDay = 0.00;
                for ($hour = 0; $hour <= 23; $hour++) {
                    for ($interval = 0; $interval <= 45; $interval+=15) {
                        $totalDay += $quarterHourProfile->profile[$month][$day][$hour][$interval];
                    }
                }
                $profile->profile[$month][$day] = $totalDay;
            }
        }

        return $profile;
    }

    /**
     * @param HourProfile $hourProfile
     * @return DayProfile
     */
    public static function hourToDay(HourProfile $hourProfile) : DayProfile
    {
        $profile = new DayProfile();

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                $totalDay = 0.00;
                for ($hour = 0; $hour <= 23; $hour++) {
                    $totalDay += $hourProfile->profile[$month][$day][$hour];
                }
                $profile->profile[$month][$day] = $totalDay;
            }
        }

        return $profile;
    }

    /**
     * @param DayProfile $dayProfile
     * @return MonthProfile
     */
    public static function dayToMonth(DayProfile $dayProfile) : MonthProfile
    {
        $profile = new MonthProfile();

        for ($month = 1; $month <= 12; $month++) {
            $totalMonth = 0.00;
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                $totalMonth += $dayProfile->profile[$month][$day];
            }
            $profile->profile[$month] = $totalMonth;
        }

        return $profile;
    }

    /**
     * @param MinuteProfile $minuteProfile
     * @return DayProfile
     */
    public static function minuteToDay(MinuteProfile $minuteProfile) : DayProfile
    {
        $profile = new DayProfile();

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                $totalDay = 0.00;
                for ($hour = 0; $hour <= 23; $hour++) {
                    for ($interval = 0; $interval <= 59; $interval++) {
                        $totalDay += $minuteProfile->profile[$month][$day][$hour][$interval];
                    }
                }
                $profile->profile[$month][$day] = $totalDay;
            }
        }

        return $profile;
    }
}
