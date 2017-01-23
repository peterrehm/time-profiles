<?php

namespace peterrehm\TimeProfiles\Tests\Profile;

use peterrehm\TimeProfiles\Profiles\TimeProfile;

class TimeProfilesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider timeProfilesDataProvider
     *
     * @param string $profile
     * @param float  $default
     * @param float  $total
     */
    public function testTotalValues(string $profile, float $default, float $total)
    {
        /** @var TimeProfile $profile */
        $profile = new $profile($default);
        $this->assertEquals($total, $profile->getTotal());
    }

    public function timeProfilesDataProvider() : array
    {
        return [
            ['peterrehm\TimeProfiles\Profiles\MonthProfile', 1, 12],
            ['peterrehm\TimeProfiles\Profiles\DayProfile', 1, 365],
            ['peterrehm\TimeProfiles\Profiles\HourProfile', 1, 8760],
            ['peterrehm\TimeProfiles\Profiles\QuarterHourProfile', 1, 35040],
            ['peterrehm\TimeProfiles\Profiles\MinuteProfile', 1, 525600],
        ];
    }
}
