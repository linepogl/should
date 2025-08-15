<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotMatch;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldNotMatchTest extends TestCase
{
    public function test_should_not_match(): void
    {
        $constraint = shouldNotMatch('/^[0-9]$/');
        pipe($constraint->toString())->to(shouldBe('is of type string and does not match PCRE pattern "/^[0-9]$/"'));

        $actual = '1';
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));

        $actual = '11';
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = 1;
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
