<?php declare(strict_types=1);

namespace App;

interface ICalcMethod {
    public function calc(Position $position): float;
}
