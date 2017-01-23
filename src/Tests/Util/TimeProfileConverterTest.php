<?php

namespace peterrehm\TimeProfile\Tests\Util;

use peterrehm\TimeProfiles\Profiles\HourProfile;
use peterrehm\TimeProfiles\Profiles\QuarterHourProfile;
use peterrehm\TimeProfiles\Util\TimeProfileConverter;

class TimeProfileConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testHourToQuarterHour()
    {
        $hourProfile = new HourProfile(60);
        $quarterProfile = TimeProfileConverter::hourToQuarterHour($hourProfile);

        $this->assertEquals($hourProfile->getTotal(), $quarterProfile->getTotal());
        $this->assertEquals(15, $quarterProfile->profile[1][1][0][0]);
        $this->assertEquals(44177, count($quarterProfile->profile, COUNT_RECURSIVE));
    }

    public function testHourToMinute()
    {
        $hourProfile = new HourProfile(60);
        $minuteProfile = TimeProfileConverter::hourToMinute($hourProfile);

        $this->assertEquals($hourProfile->getTotal(), $minuteProfile->getTotal());
        $this->assertEquals(1, $minuteProfile->profile[1][1][0][0]);
        $this->assertEquals(534737, count($minuteProfile->profile, COUNT_RECURSIVE));
    }

    public function testQuarterHourToHour()
    {
        $quarterProfile = new QuarterHourProfile(15);
        $hourProfile = TimeProfileConverter::quarterHourToHour($quarterProfile);

        $this->assertEquals($quarterProfile->getTotal(), $hourProfile->getTotal());
        $this->assertEquals(60, $hourProfile->profile[1][1][0]);
        $this->assertEquals(9137, count($hourProfile->profile, COUNT_RECURSIVE));
    }

    public function testQuarterHourToMinute()
    {
        $quarterProfile = new QuarterHourProfile(15);
        $minuteProfile = TimeProfileConverter::quarterHourToMinute($quarterProfile);

        $this->assertEquals($quarterProfile->getTotal(), $minuteProfile->getTotal());
        $this->assertEquals(1, $minuteProfile->profile[1][1][0][0]);
        $this->assertEquals(534737, count($minuteProfile->profile, COUNT_RECURSIVE));
    }
}
