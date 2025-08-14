<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeIterable;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldBeIterableTest extends TestCase
{
    public function test_should_be_like(): void
    {
        $constraint = shouldBeIterable();
        pipe($constraint->toString())->to(shouldBe('is of type iterable'));

        $actual = [];
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = 1;
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
