<?php

namespace peterrehm\TimeProfiles\Util;

use peterrehm\TimeProfiles\Config\CalculationMonths;

/**
 * Helper to rotate TimeProfiles to adjust according an timezone offset.
 * This is simplified as it supports only an integer as an offset rather than
 * a float as an offset like 1.5 would be possible. For now it is accepted as accurate
 * enough to ignore that difference.
 */
class TimeProfileHelper
{
    /**
     * Expects an array with the keys in the range 0-23
     *
     * @param array $array
     * @param int   $offset
     * @return array
     */
    public static function rotateHourProfile(array $array, int $offset) : array
    {
        if ($offset === 0) {
            return $array;
        }

        // adjust offset in case of a negative shift
        if ($offset < 0 && $offset >= -23) {
            $offset = 24 + $offset;
        }

        if ($offset > 0 && $offset <= 23) {
            return array_merge(array_slice($array, 24 - $offset, $offset), array_slice($array, 0, 24 -$offset));
        }

        throw new \RuntimeException(sprintf('Invalid offset "%d" has been provided.', $offset));
    }

    /**
     * Expects an array with the structure [month][day][hour]
     *
     * @param array $profile
     * @param int   $offset
     *
     * @return array
     */
    public static function rotateAnnualProfile(array $profile, int $offset) : array
    {
        $rotatedProfile = [];

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                $rotatedProfile[$month][$day] = self::rotateHourProfile($profile[$month][$day], $offset);
            }
        }

        return $rotatedProfile;
    }

    /**
     * @param array  $profile
     * @param string $sourceTimeZone
     * @param string $targetTimeZone
     * @param int    $year
     *
     * @return array
     * @throws \peterrehm\TimeProfiles\Exception\NoResultException
     */
    public static function adjustToTimeZone(array $profile, string $sourceTimeZone, string $targetTimeZone, int $year = 2013) : array
    {
        $rotatedProfile = [];
        $sourceTimeZone = TimezoneHelper::getTimezoneInformationById($sourceTimeZone, $year);
        $targetTimeZone = TimezoneHelper::getTimezoneInformationById($targetTimeZone, $year);

        $targetOffset = $targetTimeZone['transitions'][0]['offset'] / 3600;
        $sourceOffset = $sourceTimeZone['transitions'][0]['offset'] / 3600;

        $offsetDifference = $targetOffset - $sourceOffset;

        $sourceTransitions = self::parseTransitions($sourceTimeZone);
        $targetTransitions = self::parseTransitions($targetTimeZone);

        // if initial time zone and all transition are equal nothing has to be updated
        if ($targetOffset === $sourceOffset && $sourceTransitions === $targetTransitions) {
            return $profile;
        }

        for ($month = 1; $month <= 12; $month++) {
            $daysPerMonth = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $daysPerMonth; $day++) {
                // if either timezone has more than one transitions the transition must be calculated for each day
                $hasTransitions = count($sourceTransitions) >= 1 || count($targetTransitions) >= 1;
                $hasTransitionsForDay = isset($sourceTransitions[$month][$day]) || isset($targetTransitions[$month][$day]);
                if ($hasTransitions && $hasTransitionsForDay) {
                    if (isset($sourceTransitions[$month][$day])) {
                        $sourceOffset = $sourceTransitions[$month][$day];
                    }

                    if (isset($targetTransitions[$month][$day])) {
                        $targetOffset = $targetTransitions[$month][$day];
                    }

                    $offsetDifference = $targetOffset - $sourceOffset;
                }

                $rotatedProfile[$month][$day] = self::rotateHourProfile($profile[$month][$day], $offsetDifference);
            }
        }
        
        return $rotatedProfile;
    }

    /**
     * @param array $timezoneInformation Provided by TimezoneHelper::getTimezoneInformation*
     *
     * @return array
     */
    private static function parseTransitions(array $timezoneInformation) : array
    {
        $transitions = [];

        // ignore initial timezone information as we only care about further transitions
        array_shift($timezoneInformation['transitions']);

        foreach ($timezoneInformation['transitions'] as $transition) {
            $month = (int) substr($transition['time'], 5, 2);
            $day = (int) substr($transition['time'], 8, 2);
            $transitions[$month][$day] = $transition['offset'] / 3600;
        }

        return $transitions;
    }
}
