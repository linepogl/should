<?php

declare(strict_types=1);

namespace Tests;

use DateInterval;
use DateTime;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeA;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldBeATest extends TestCase
{
    public function test_should_be_a(): void
    {
        $actual = new DateTime();

        $constraint = shouldBeA(DateTime::class);
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
        pipe($constraint->toString())->to(shouldBe('is an instance of class DateTime'));

        $constraint = shouldBeA(DateInterval::class);
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
        pipe($constraint->toString())->to(shouldBe('is an instance of class DateInterval'));
    }
}
