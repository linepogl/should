<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function Should\shouldBe;
use function Should\shouldThrow;

class ShouldBeTest extends TestCase
{
    public function test_should_be(): void
    {
        pipe(1)->to(shouldBe(1));
        shouldThrow(ExpectationFailedException::class)(fn () => pipe(2)->to(shouldBe(1)));
    }

    public function test_describe(): void
    {
        pipe(shouldBe(1)->toString())->to(shouldBe('is equal to 1'));
    }

    public function test_evaluate(): void
    {
        Assert::assertThat(1, shouldBe(1));
    }
}
