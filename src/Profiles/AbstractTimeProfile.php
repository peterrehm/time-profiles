<?php

namespace peterrehm\TimeProfiles\Profiles;

class AbstractTimeProfile implements TimeProfile
{
    public $profile = [];

    public function getTotal() : float
    {
        $total = 0.00;

        // recursive sum of the array
        array_walk_recursive(
            $this->profile,
            function ($value) use (&$total) {
                $total += $value;
            }
        );

        return $total;
    }

    public function multiply(float $factor) : void
    {
        // recursive multiplication of each element
        array_walk_recursive(
            $this->profile,
            function (&$value) use ($factor) {
                $value *= $factor;
            }
        );
    }
}
