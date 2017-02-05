<?php

namespace peterrehm\TimeProfile\Tests\Util;

use peterrehm\TimeProfiles\Profiles\DayProfile;
use peterrehm\TimeProfiles\Profiles\HourProfile;
use peterrehm\TimeProfiles\Profiles\MinuteProfile;
use peterrehm\TimeProfiles\Profiles\QuarterHourProfile;
use peterrehm\TimeProfiles\Util\TimeProfileConverter;
use PHPUnit\Framework\TestCase;

class TimeProfileConverterTest extends TestCase
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

    public function testQuarterHourToDay()
    {
        $quarterProfile = new QuarterHourProfile(15);
        $dayProfile = TimeProfileConverter::quarterHourToDay($quarterProfile);

        $this->assertEquals($quarterProfile->getTotal(), $dayProfile->getTotal());
        $this->assertEquals(1440, $dayProfile->profile[1][1]);
        $this->assertEquals(377, count($dayProfile->profile, COUNT_RECURSIVE));
    }

    public function testHourToDay()
    {
        $hourProfile = new HourProfile(60);
        $dayProfile = TimeProfileConverter::hourToDay($hourProfile);

        $this->assertEquals($hourProfile->getTotal(), $dayProfile->getTotal());
        $this->assertEquals(1440, $dayProfile->profile[1][1]);
        $this->assertEquals(377, count($dayProfile->profile, COUNT_RECURSIVE));
    }

    public function testMonthToDay()
    {
        $dayProfile = new DayProfile(1);
        $monthProfile = TimeProfileConverter::dayToMonth($dayProfile);

        $this->assertEquals($dayProfile->getTotal(), $monthProfile->getTotal());
        $this->assertEquals(31, $monthProfile->profile[1]);
        $this->assertEquals(12, count($monthProfile->profile, COUNT_RECURSIVE));
    }

    public function testMinuteToDay()
    {
        $minuteProfile = new MinuteProfile(1);
        $dayProfile = TimeProfileConverter::minuteToDay($minuteProfile);

        $this->assertEquals($minuteProfile->getTotal(), $dayProfile->getTotal());
        $this->assertEquals(1440, $dayProfile->profile[1][1]);
        $this->assertEquals(377, count($dayProfile->profile, COUNT_RECURSIVE));
    }
}
