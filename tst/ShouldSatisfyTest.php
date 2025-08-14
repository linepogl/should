<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotThrow;
use function Should\shouldSatisfy;
use function Should\shouldThrow;

class ShouldSatisfyTest extends TestCase
{
    public function test_should_satisfy(): void
    {
        $constraint = shouldSatisfy(new IsNull());
        pipe($constraint->toString())->to(shouldBe('is null'));

        $actual = null;
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = 1;
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
