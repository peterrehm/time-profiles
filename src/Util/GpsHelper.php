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

    /**
     * @param float $lat    Latitude
     * @param float $lon    Longitude
     * @param int   $max    Default is 500m. The position will be randomized by about 50-500m.
     *                      The adjustment is +/- a few meters.
     *                      Inspired by https://gis.stackexchange.com/questions/2951/algorithm-for-offsetting-a-latitude-longitude-by-some-amount-of-meters
     * @return array
     * @throws \Exception
     */
    public static function shiftLatLon(float $lat, float $lon, int $max = 500)
    {
        if ($max < 50) {
            throw new \Exception(sprintf('The parameter max must be either 0 or larger than 50m. %d has been provided.', $max));
        }

        $targetOffset = rand(80, $max);
        $shiftLatPercentage = rand(20,80);

        $shiftLat = ($shiftLatPercentage/100 * $targetOffset) / 111111;
        // longitude distance needs to be calculated according to the formula lat^2 + lon^2 = distance^2
        $shiftLon = (sqrt(pow($targetOffset, 2) - pow($shiftLatPercentage/100 * $targetOffset,2)) / (111111 * cos(deg2rad($lat))));

        $randomizeOperator = rand(0, 1);

        if ($randomizeOperator === 0) {
            $shiftedLat = $lat + $shiftLat;
            $shiftedLon = $lon + $shiftLon;
        } else {
            $shiftedLat = $lat - $shiftLat;
            $shiftedLon = $lon - $shiftLon;
        }

        return [
            'lat' => $shiftedLat,
            'lon' => $shiftedLon,
            'distanceM' => GpsHelper::haversineGreatCircleDistance($lat, $lon, $shiftedLat, $shiftedLon) * 1000
        ];
    }
}
