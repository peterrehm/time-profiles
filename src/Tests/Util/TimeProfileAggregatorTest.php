<?php

namespace peterrehm\TimeProfile\Tests\Util;

use peterrehm\TimeProfiles\Profiles\DayProfile;
use peterrehm\TimeProfiles\Profiles\HourProfile;
use peterrehm\TimeProfiles\Profiles\MinuteProfile;
use peterrehm\TimeProfiles\Profiles\MonthProfile;
use peterrehm\TimeProfiles\Profiles\QuarterHourProfile;
use peterrehm\TimeProfiles\Util\TimeProfileAggregator;
use PHPUnit\Framework\TestCase;

class TimeProfileAggregatorTest extends TestCase
{
    public function testMinuteAggregation()
    {
        $profile1 = new MinuteProfile(1);
        $profile2 = new MinuteProfile(2);

        $aggregatedProfile = TimeProfileAggregator::aggregateMinuteProfile($profile1, $profile2);

        $this->assertEquals(525600 + 525600*2, $aggregatedProfile->getTotal());

        $aggregatedProfile = TimeProfileAggregator::aggregateMinuteProfile($profile1);
        $this->assertSame($aggregatedProfile, $profile1);
    }

    public function testQuarterHourAggregation()
    {
        $profile1 = new QuarterHourProfile(1);
        $profile2 = new QuarterHourProfile(2);

        $aggregatedProfile = TimeProfileAggregator::aggregateQuarterHourProfile($profile1, $profile2);

        $this->assertEquals(35040 + 35040*2, $aggregatedProfile->getTotal());

        $aggregatedProfile = TimeProfileAggregator::aggregateQuarterHourProfile($profile1);
        $this->assertSame($aggregatedProfile, $profile1);
    }

    public function testHourAggregation()
    {
        $profile1 = new HourProfile(1);
        $profile2 = new HourProfile(2);

        $aggregatedProfile = TimeProfileAggregator::aggregateHourProfile($profile1, $profile2);

        $this->assertEquals(8760 + 8760*2, $aggregatedProfile->getTotal());

        $aggregatedProfile = TimeProfileAggregator::aggregateHourProfile($profile1);
        $this->assertSame($aggregatedProfile, $profile1);
    }

    public function testDayAggregation()
    {
        $profile1 = new DayProfile(1);
        $profile2 = new DayProfile(2);

        $aggregatedProfile = TimeProfileAggregator::aggregateDayProfile($profile1, $profile2);

        $this->assertEquals(365 + 365*2, $aggregatedProfile->getTotal());

        $aggregatedProfile = TimeProfileAggregator::aggregateDayProfile($profile1);
        $this->assertSame($aggregatedProfile, $profile1);
    }

    public function testMonthAggregation()
    {
        $profile1 = new MonthProfile(1);
        $profile2 = new MonthProfile(2);

        $aggregatedProfile = TimeProfileAggregator::aggregateMonthProfile($profile1, $profile2);

        $this->assertEquals(12 + 12*2, $aggregatedProfile->getTotal());

        $aggregatedProfile = TimeProfileAggregator::aggregateMonthProfile($profile1);
        $this->assertSame($aggregatedProfile, $profile1);
    }
}
