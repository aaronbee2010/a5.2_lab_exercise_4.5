<?php declare(strict_types=1);

namespace App;

class IncomeCalculator {
    private Position $position = Position::NONE;
    private ICalcMethod $calcMethod;

    public function setPosition(Position $position): void {
        $this->position = $position;
    }

    public function setCalcMethod(ICalcMethod $calcMethod): void {
        $this->calcMethod = $calcMethod;
    }

    public function calc(): float {
        if (!isset($this->calcMethod))
            throw new \Exception("calcMethod is not yet maintained");
        if ($this->position === Position::NONE)
            throw new \Exception("position is not yet maintained");

        return $this->calcMethod->calc($this->position);
    }
}
