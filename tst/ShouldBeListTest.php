<?php

declare(strict_types=1);

namespace Tests;

use DateInterval;
use DateTime;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeA;
use function Should\shouldBeList;
use function Should\shouldBeInt;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class ShouldBeListTest extends TestCase
{
    public function test_should_be_list(): void
    {
        $constraint = shouldBeList();
        pipe($constraint->toString())->to(shouldBe('is a list'));

        $actual = [1, 2, 3];
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldNotThrow());
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));

        $actual = [1, 2, 3, -1 => 4];
        $eval = static fn() => pipe($actual)->to($constraint);
        pipe($eval)->to(shouldThrow(ExpectationFailedException::class));
        pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
    }
}
