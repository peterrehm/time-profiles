<?php

namespace peterrehm\TimeProfiles\Tests\Util;

use peterrehm\TimeProfiles\Util\GpsHelper;
use PHPUnit\Framework\TestCase;

class GpsHelperTest extends TestCase
{
    public function distancesDataProvider()
    {
        return array(
            'unterschwarzach -> bad wurzach' => array(47.9467994, 9.871588299999985, 47.90769063815921, 9.890814374218735, 4.5785636850952),
            'unterschwarzach -> sydney' => array(47.9467994, 9.871588299999985, -33.87, 151.22, 16458.069554004),
            'sydney -> unterschwarzach' => array(-33.87, 151.22, 47.9467994, 9.871588299999985, 16458.069554004),
            'buenos aires -> unterschwarzach' => array(-34.6, -58.449999, 47.9467994, 9.871588299999985, 11407.462119157),
            'buenos aires -> nuku alofa' => array(-34.6, -58.449999, -21.133, -175.21699, 10909.189880031),
            'nuku alofa -> buenos aires' => array(-21.133, -175.21699, -34.6, -58.449999, 10909.189880031),
        );
    }

    public function locationDataProvider()
    {
        return array(
            'unterschwarzach' => array(47.9467994, 9.871588299999985),
            'sydney' => array(-33.87, 151.22),
            'buenos aires' => array(-34.6, -58.449999),
            'nuku alofa' => array(-21.133, -175.21699, 500),
            'nuku alofa 1000m' => array(-21.133, -175.21699, 1000),
            'nuku alofa 5000m' => array(-21.133, -175.21699, 1000),
        );
    }

    /**
     * @dataProvider distancesDataProvider
     */
    public function testExpectedDistances($latFrom, $lonFrom, $latTo, $lonTo, $result)
    {
        $this->assertEquals($result, GpsHelper::haversineGreatCircleDistance($latFrom, $lonFrom, $latTo, $lonTo), '', 0.000001);
    }

    /**
     * @dataProvider locationDataProvider
     */
    public function testShiftLatLon($lat, $lon, $max = 1000)
    {
        $result = GpsHelper::shiftLatLon($lat, $lon, $max);

        $shiftedLat = $result['lat'];
        $shiftedLon = $result['lon'];

        $this->assertThat(
            GpsHelper::haversineGreatCircleDistance($lat, $lon, $shiftedLat, $shiftedLon) * 1000,
            $this->logicalAnd(
                $this->greaterThan(100),
                $this->lessThan($max)
            )
        );
    }

    public function testShiftLatLonInvalidMax()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The parameter max must be either 0 or larger than 100m. 50 has been provided.');
        GpsHelper::shiftLatLon(47.9467994, 9.871588299999985, 50);
    }
}
