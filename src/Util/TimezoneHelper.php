<?php

namespace peterrehm\TimeProfiles\Util;

use DateTime;
use DateTimeZone;
use peterrehm\TimeProfiles\Exception\NoResultException;

class TimezoneHelper
{
    /**
     * Find the closest timezone for coordinates within a country
     *
     * @param string $country
     * @param float  $latitude
     * @param float  $longitude
     *
     * @return string timezone name
     */
    public static function getNearestTimezone(string $country, float $latitude, float $longitude) : string
    {
        $timezoneIdentifiers = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, strtoupper($country));
        $timeZoneDistance = false;
        $timeZoneId = '';

        // if only one time zone exists in the country return that timezone name
        if (count($timezoneIdentifiers) === 1) {
            return $timezoneIdentifiers[0];
        }

        // iterate over all identifiers and calculate the distances to find the closest
        // timezone
        foreach ($timezoneIdentifiers as $id) {
            $timezone = new DateTimeZone($id);
            $location = $timezone->getLocation();
            $distance = GpsHelper::haversineGreatCircleDistance($latitude, $longitude, $location['latitude'], $location['longitude']);

            if ($timeZoneDistance === false || $distance < $timeZoneDistance) {
                $timeZoneId = $id;
                $timeZoneDistance = $distance;
            }
        }

        return $timeZoneId;
    }

    /**
     * Get the timezone information for a given location
     *
     * @param string $countryCode
     * @param float  $latitude
     * @param float  $longitude
     * @param int    $year
     *
     * @return array
     * @throws NoResultException
     */
    public static function getTimezoneInformationByLocation(string $countryCode, float $latitude, float $longitude, int $year = 2013) : array
    {
        $timezoneId = self::getNearestTimezone($countryCode, $latitude, $longitude);
        return self::getTimezoneInformationById($timezoneId, $year);
    }

    /**
     * Get the timezone information for a given timezone
     *
     * @param string $timeZoneId
     * @param int    $year
     *
     * @return array
     * @throws NoResultException
     */
    public static function getTimezoneInformationById(string $timeZoneId, int $year = 2013) : array
    {
        $timezone = new DateTimeZone($timeZoneId);
        $yearStart = new DateTime('01.01.' . $year, $timezone);
        $yearEnd = new DateTime('31.12.' . $year, $timezone);
        $transitions = $timezone->getTransitions($yearStart->getTimestamp(), $yearEnd->getTimestamp());

        foreach ($transitions as $transition) {
            if ($transition['isdst'] === false) {
                return [
                    'name' => $timezone->getName(),
                    'location' => $timezone->getLocation(),
                    'offset' => $timezone->getOffset(new DateTime('@' . $transition['ts'], $timezone)) / 3600,
                    'transitions' => $transitions
                ];
            }
        }

        throw new NoResultException('Timezone could not be located.');
    }
}
