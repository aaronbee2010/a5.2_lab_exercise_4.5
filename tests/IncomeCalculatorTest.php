<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use App\Position;
use App\ICalcMethod;
use App\IncomeCalculator;

#[CoversClass(IncomeCalculator::class)]
class IncomeCalculatorTest extends TestCase {
    private ICalcMethod $calcMethod;
    private IncomeCalculator $calc;

    #[Before]
    public function setUp(): void {
        // Make a fake implementation of ICalcMethod which returns default values
        $this->calcMethod = $this->createMock(ICalcMethod::class);
        // Make an instance of the real class under test
        $this->calc = new IncomeCalculator();
    }

    #[Test]
    public function testCalc1(): void {
        // Configure the ICalcMethod to behave how we want
        $this->calcMethod->method("calc")
                         ->with($this->identicalTo(Position::BOSS))
                         ->willReturn(70000.00);

        // Set the calc method
        $this->calc->setCalcMethod($this->calcMethod);

        // Set the position
        $this->calc->setPosition(Position::BOSS);

        $income1 = $this->calc->calc();

        $this->assertSame(70000.00, $income1);
    }

    #[Test]
    public function testNoCalc(): void {
        // Set the position
        $this->calc->setPosition(Position::SURFER);

        // Calling Calc before setting the CalcMethod should throw exception
        $this->expectExceptionMessage("calcMethod is not yet maintained");
        $this->calc->calc();
    }

    #[Test]
    public function testNoPosition(): void {
        // Set the calc method
        $this->calc->setCalcMethod($this->calcMethod);

        // Calling Calc before setting the position should throw exceptio
        $this->expectExceptionMessage("position is not yet maintained");
        $this->calc->calc();
    }

    #[Test]
    public function testCalc2(): void {
        // Set up a fake ICalcMethod which throws an exception // when asked for the income of a SURFER
        $this->calcMethod->method("calc")
                         ->with($this->identicalTo(Position::SURFER))
                         ->willThrowException(new Exception("Don't know this guy!"));
        
        // Set up the calc method and position
        $this->calc->setPosition(Position::SURFER);
        $this->calc->setCalcMethod($this->calcMethod);

        // If the ICalcMethod::calc() method throws an exception, then it should
        // continue to bubble up out fo the IncomeCalculator::calc() method
        $this->expectExceptionMessage("Don't know this guy!");
        $this->calc->calc();
    }
}
