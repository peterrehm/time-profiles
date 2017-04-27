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
     * @param float       $latitude
     * @param float       $longitude
     * @param null|string $country
     * @param float|null  $offset
     *
     * @return string
     */
    public static function getNearestTimezone(float $latitude, float $longitude, ?string $country = null, ?float $offset = null) : string
    {
        // iterate over all timezones unless restricted with country code
        if ($country !== null && strlen($country) == 2) {
            $timezoneIdentifiers = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country);
        } else {
            $timezoneIdentifiers = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        }

        $timeZoneDistance = false;
        $timeZoneId = '';

        // iterate over all identifiers and calculate the distances to find the closest
        // timezone
        foreach ($timezoneIdentifiers as $id) {
            $timezone = new DateTimeZone($id);

            if ($offset !== null) {
                $yearStart = new \DateTime('01.01.2017', $timezone);
                if ($offset !== (float) $timezone->getOffset($yearStart)/3600) {
                    continue;
                }
            }

            $location = $timezone->getLocation();
            $distance = GpsHelper::haversineGreatCircleDistance($latitude, $longitude, $location['latitude'], $location['longitude']);

            if ($timeZoneDistance === false || $distance < $timeZoneDistance) {
                $timeZoneId = $id;
                $timeZoneDistance = $distance;
            }
        }

        if (empty($timeZoneId)) {
            throw new \RuntimeException(
                sprintf(
                    'Timezone for lat: %s lon: %s country: %s with offset: %s could not be located.',
                    $latitude,
                    $longitude,
                    $country,
                    $offset
                )
            );
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
        $timezoneId = self::getNearestTimezone($latitude, $longitude, $countryCode);
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
