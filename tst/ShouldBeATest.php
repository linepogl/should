<?php

declare(strict_types=1);

namespace Tests;

use DateInterval;
use DateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function Should\shouldBe;
use function Should\shouldBeA;
use function Should\shouldThrow;

class ShouldBeATest extends TestCase
{
    public function test_should_be_a(): void
    {
        pipe(new DateTime())->to(shouldBeA(DateTime::class));

        pipe(fn () => pipe(new DateTime())->to(shouldBeA(DateInterval::class)))
        ->to(shouldThrow(ExpectationFailedException::class));
    }

    public function test_describe(): void
    {
        pipe(shouldBeA(DateTime::class)->toString())->to(shouldBe('is an instance of class DateTime'));
    }

    public function test_evaluate(): void
    {
        Assert::assertThat(new DateTime(), shouldBeA(DateTime::class));
    }
}
