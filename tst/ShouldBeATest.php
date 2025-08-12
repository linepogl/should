<?php

declare(strict_types=1);

namespace Tests;

use DateInterval;
use DateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeA;
use function Should\shouldThrow;

class ShouldBeATest extends TestCase
{
    public function test_should_be_a(): void
    {
        shouldBeA(DateTime::class)(
            new DateTime()
        );
        shouldThrow(ExpectationFailedException::class) (
            fn () => shouldBeA(DateInterval::class)(new DateTime())
        );
    }

    public function test_describe(): void
    {
        shouldBe('is an instance of class DateTime')(
            shouldBeA(DateTime::class)->toString()
        );
    }

    public function test_evaluate(): void
    {
        shouldBe(true) (
            shouldBeA(DateTime::class)->evaluate(new DateTime(), returnResult: true)
        );
    }
}
