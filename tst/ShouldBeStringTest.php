<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeString;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldBeStringTest extends TestCase
{
    public function test_should_be_string(): void
    {
        $constraint = shouldBeString();
        pipe($constraint->toString())->to(shouldBe('is of type string'));

        $actual = '123';
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = 123;
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
