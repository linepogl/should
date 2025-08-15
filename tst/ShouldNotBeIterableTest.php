<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotBeIterable;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldNotBeIterableTest extends TestCase
{
    public function test_should_not_be_iterable(): void
    {
        $constraint = shouldNotBeIterable();
        pipe($constraint->toString())->to(shouldBe('is not of type iterable'));

        $actual = 1;
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = [];
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
