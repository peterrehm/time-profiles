<?php

namespace peterrehm\TimeProfiles\Profiles;

interface TimeProfile
{
    public function getTotal() : float;

    public function multiply(float $factor) : void;
}
