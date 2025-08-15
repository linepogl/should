<?php

declare(strict_types=1);

namespace Tests;

use ArrayIterator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotIterateLike;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldNotIterateLikeTest extends TestCase
{
    public function test_should_not_iterate_like(): void
    {
        $constraint = shouldNotIterateLike([1, 2, 3]);
        pipe($constraint->toString())->to(shouldBe('does not iterate like an array'));

        $actual = new ArrayIterator([1, 2, 3, 4]);
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = [1,2,3];
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));

        $actual = '123';
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
    }
}
