<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeBool;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldBeBoolTest extends TestCase
{
    public function test_should_be_bool(): void
    {
        $constraint = shouldBeBool();
        pipe($constraint->toString())->to(shouldBe('is of type bool'));

        $actual = 1 === rand(0, 1);
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = 1;
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
