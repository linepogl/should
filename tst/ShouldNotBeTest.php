<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotBe;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldNotBeTest extends TestCase
{
    public function test_should_not_be(): void
    {
        $actual = 1;

        $constraint = shouldNotBe(2);
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
        pipe($constraint->toString())->to(shouldBe('is not 2'));

        $constraint = shouldNotBe(1);
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
        pipe($constraint->toString())->to(shouldBe('is not 1'));
    }
}
