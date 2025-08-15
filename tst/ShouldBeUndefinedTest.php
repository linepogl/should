<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeUndefined;
use function Should\shouldThrow;

class ShouldBeUndefinedTest extends TestCase
{
    public function test_should_be_undefined(): void
    {
        $actual = 1;

        $constraint = shouldBeUndefined();
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
        pipe($constraint->toString())->to(shouldBe('is undefined'));
    }
}
