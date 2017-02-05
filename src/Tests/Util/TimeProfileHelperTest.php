<?php

namespace peterrehm\TimeProfile\Tests\Util;

use peterrehm\TimeProfiles\Config\CalculationMonths;
use peterrehm\TimeProfiles\Profiles\HourProfile;
use peterrehm\TimeProfiles\Util\TimeProfileHelper;
use PHPUnit\Framework\TestCase;

class TimeProfileHelperTest extends TestCase
{
    public function hourProfileDataProvider()
    {
        $sourceArray = range(0, 23);

        return array(
            'offset -1' => array($sourceArray, -1, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0]),
            'offset -10' => array($sourceArray, -10, [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]),
            'offset -23' => array($sourceArray, -23, [23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22]),
            'offset 1' => array($sourceArray, 1, [23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, ]),
            'offset 10' => array($sourceArray, 10, [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]),
            'offset 23' => array($sourceArray, 23, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0]),
            'offset 0' => array($sourceArray, 0, $sourceArray),
        );
    }

    /**
     * @dataProvider hourProfileDataProvider
     */
    public function testRotateHourProfile($source, $offset, $result)
    {
        $this->assertEquals($result, TimeProfileHelper::rotateHourProfile($source, $offset));
    }

    public function testRotateAnnualProfile()
    {
        $hourProfile = new HourProfile();
        $profile = $hourProfile->profile;

        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                $profile[$month][$day] = range(0, 23);
            }
        }

        $rotatedProfile = TimeProfileHelper::rotateAnnualProfile($profile, 2);

        $this->assertEquals([22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21], $rotatedProfile[1][1]);
        $this->assertEquals([22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21], $rotatedProfile[12][31]);
    }

    public function testTimezoneTransformationUtcToBusingen()
    {
        $hourProfile = new HourProfile();
        $profile = $hourProfile->profile;

        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                $profile[$month][$day] = range(0, 23);
            }
        }

        $rotatedProfile = TimeProfileHelper::adjustToTimeZone($profile, 'UTC', 'Europe/Busingen');

        $this->assertEquals([23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22], $rotatedProfile[1][1]);
        $this->assertEquals([22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21], $rotatedProfile[3][31]);
        $this->assertEquals([22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21], $rotatedProfile[4][1]);
        $this->assertEquals([22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21], $rotatedProfile[10][26]);
        $this->assertEquals([23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22], $rotatedProfile[10][27]);
        $this->assertEquals([23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22], $rotatedProfile[12][31]);
    }

    public function testTimezoneTransformationBusingenToUtc()
    {
        $hourProfile = new HourProfile();
        $profile = $hourProfile->profile;

        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                $profile[$month][$day] = range(0, 23);
            }
        }

        $rotatedProfile = TimeProfileHelper::adjustToTimeZone($profile, 'Europe/Busingen', 'UTC');

        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0], $rotatedProfile[1][1]);
        $this->assertEquals([2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1], $rotatedProfile[3][31]);
        $this->assertEquals([2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1], $rotatedProfile[4][1]);
        $this->assertEquals([2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1], $rotatedProfile[10][26]);
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0], $rotatedProfile[10][27]);
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0], $rotatedProfile[12][31]);
    }

    public function testTimezoneTransformationBusingenToronto()
    {
        $hourProfile = new HourProfile();
        $profile = $hourProfile->profile;

        for ($month = 1; $month <= 12; $month++) {
            $days = CalculationMonths::getDays($month);
            for ($day = 1; $day <= $days; $day++) {
                $profile[$month][$day] = range(0, 23);
            }
        }

        $rotatedProfile = TimeProfileHelper::adjustToTimeZone($profile, 'Europe/Busingen', 'America/Toronto');

        $this->assertEquals([6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5], $rotatedProfile[1][1]);
        $this->assertEquals([5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4], $rotatedProfile[3][10]);
        $this->assertEquals([6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5], $rotatedProfile[3][31]);
        $this->assertEquals([5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4], $rotatedProfile[10][27]);
        $this->assertEquals([6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5], $rotatedProfile[11][03]);
        $this->assertEquals([6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5], $rotatedProfile[12][31]);
    }
}
