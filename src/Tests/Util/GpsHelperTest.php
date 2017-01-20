<?php

namespace peterrehm\TimeProfiles\Tests\Util;

use peterrehm\TimeProfiles\Util\GpsHelper;

class GpsHelperTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @dataProvider distancesDataProvider
     */
    public function testExpectedDistances($latFrom, $lonFrom, $latTo, $lonTo, $result)
    {
        $this->assertEquals($result, GpsHelper::haversineGreatCircleDistance($latFrom, $lonFrom, $latTo, $lonTo), '', 0.000001);
    }
}
