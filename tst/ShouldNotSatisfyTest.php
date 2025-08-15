<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotSatisfy;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldNotSatisfyTest extends TestCase
{
    public function test_should_not_satisfy(): void
    {
        $constraint = shouldNotSatisfy(new IsNull());
        pipe($constraint->toString())->to(shouldBe('is not null'));

        $actual = 1;
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = null;
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
