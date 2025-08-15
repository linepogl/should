<?php

declare(strict_types=1);

namespace Tests;

use DateInterval;
use DateTime;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotBeA;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldNotBeATest extends TestCase
{
    public function test_should_not_be_a(): void
    {
        $actual = new DateTime();

        $constraint = shouldNotBeA(DateInterval::class);
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
        pipe($constraint->toString())->to(shouldBe('is not an instance of class DateInterval'));

        $constraint = shouldNotBeA(DateTime::class);
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
        pipe($constraint->toString())->to(shouldBe('is not an instance of class DateTime'));
    }
}
