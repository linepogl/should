<?php

declare(strict_types=1);

namespace Tests;

use ArrayIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeA;
use function Should\shouldBeIterable;
use function Should\shouldBeLike;
use function Should\shouldBeUndefined;
use function Should\shouldMatch;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldMatchTest extends TestCase
{
    public function test_should_match(): void
    {
        $constraint = shouldMatch('/^[0-9]$/');;
        pipe($constraint->toString())->to(shouldBe('is of type string and matches PCRE pattern "/^[0-9]$/"'));

        $actual = '1';
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = '11';
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));

        $actual = 1;
        $eval = fn () => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
