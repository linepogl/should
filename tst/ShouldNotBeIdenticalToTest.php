<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotBeIdenticalTo;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldNotBeIdenticalToTest extends TestCase
{
    public function test_should_not_be_identical_to(): void
    {
        $actual = 1;

        $constraint = shouldNotBeIdenticalTo(2);
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
        pipe($constraint->toString())->to(shouldBe('is not identical to 2'));

        $constraint = shouldNotBeIdenticalTo(1);
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
        pipe($constraint->toString())->to(shouldBe('is not identical to 1'));
    }
}
