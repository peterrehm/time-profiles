<?php

namespace peterrehm\TimeProfiles\Tests\Util;

use peterrehm\TimeProfiles\Util\TimezoneHelper;
use PHPUnit\Framework\TestCase;

class TimezoneHelperTest extends TestCase
{
    public function timezoneDataProvider()
    {
        return array(
            'sydney' => array('AU', -33.87, 151.22, 10),
            'darwin' => array('AU', -12.46, 130.841999, 9.5),
            'adeleide' => array('AU', -34.925, 138.6, 9.5),
            'berlin' => array('DE', 52.33, 13.2999999, 1),
            'denver' => array('US', 39.74, -104.99, -7),
            'new york' => array('US', 42.35, -71.05, -5),
            'san francisco' => array('US', 37.77, -122.42, -8),
            'rome' => array('IT', 41.9, 12.48, 1),
            'helsinki' => array('FI', 60.17, 24.97, 2),
            'karachi' => array('PK', 24.892, 67.027, 5),
            'cape town' => array('ZA', -33.92, 18.37, 2),
            'bombay' => array('IN', 18.93, 72.8299999, 5.5),
            'kathmandu' => array('NP', 27.7, 85.317, 5.75),
            'hawaii' => array('US', 21.3, -157.85, -10),
            'nuku alofa' => array('TO', -21.133, -175.21699, 13),
            'buenos aires' => array('AR', -34.6, -58.449999, -3),
            'tokyo' => array('JP', 35.7, 139.769999, 9),
            'nuuk' => array('GL', 64.183, -51.716999, -3),
            'reykjavik' => array('IS', 64.133, -21.899999, 0),
            'unterschwarzach' => array('DE', 47.94, 9.781, 1),
            'dakar' => array('SN', 14.687, -17.45, 0),
            'beijing' => array('CN', 39.92, 116.42, 8),
            'lisbon' => array('PT', 38.707, -9.133, 0),
            'paris' => array('FR', 48.87, 2.66999999, 1),
            'london' => array('GB', 51.5, -0.1299999, 0),
        );
    }

    /**
     * @dataProvider timezoneDataProvider
     */
    public function testExpectedTimezoneOffsetsByLocation($country, $latitude, $longitude, $result)
    {
        $this->assertEquals($result, TimezoneHelper::getTimezoneInformationByLocation($country, $latitude, $longitude)['offset']);
    }
}
