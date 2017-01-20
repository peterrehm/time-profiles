<?php

namespace peterrehm\TimeProfiles\Util;

class GpsHelper
{
    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     *
     * @param float     $latitudeFrom  Latitude of start point in [deg decimal]
     * @param float     $longitudeFrom Longitude of start point in [deg decimal]
     * @param float     $latitudeTo    Latitude of target point in [deg decimal]
     * @param float     $longitudeTo   Longitude of target point in [deg decimal]
     *
     * @return float Distance between points in [km] (same as earthRadius)
     */
    public static function haversineGreatCircleDistance(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo) : float
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $angle = 2 *
            asin(
                sqrt(
                    pow(sin(($latTo - $latFrom) / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin(($lonTo - $lonFrom) / 2), 2)
                )
            );
        return $angle * 6371;
    }
}
