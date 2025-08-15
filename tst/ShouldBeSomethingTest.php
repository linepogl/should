<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeSomething;
use function Should\shouldNotThrow;

class ShouldBeSomethingTest extends TestCase
{
    public function test_should_be_a(): void
    {
        $actual = 1;

        $constraint = shouldBeSomething();
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
        pipe($constraint->toString())->to(shouldBe('is anything'));
    }
}
