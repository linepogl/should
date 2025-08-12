<?php

declare(strict_types=1);

namespace Tests;

use DateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldStartWith;
use function Should\shouldThrow;

class ShouldBeTest extends TestCase
{
    public function test_should_be(): void
    {
        shouldBe(1)(1);
        shouldBe(TestEnum::A)(TestEnum::A);
        shouldBe(null)(null);
        shouldThrow(new ExpectationFailedException('Failed asserting that 2 is 1.'))(fn () => shouldBe(1)(2));
        shouldThrow(new ExpectationFailedException('Problem!'))(fn () => shouldBe(1, 'Problem!')(2));
        shouldThrow(new ExpectationFailedException('Failed asserting that \'1\' is 1.'))(fn () => shouldBe(1)('1'));
        shouldThrow(ExpectationFailedException::class)(fn () => shouldBe('a')(TestEnum::A));
        shouldThrow(ExpectationFailedException::class)(fn () => shouldBe(null)(''));
    }

    public function test_describe(): void
    {
        shouldBe('is null')(shouldBe(null)->toString());
        shouldBe('is 1')(shouldBe(1)->toString());
        shouldStartWith('is equal to DateTime Object #')(shouldBe(new DateTime())->toString());
        shouldStartWith('is equal to Tests\TestEnum Enum #')(shouldBe(TestEnum::A)->toString());
    }

    public function test_evaluate(): void
    {
        shouldBe(true)(shouldBe(1)->evaluate(1, returnResult: true));
        shouldBe(false)(shouldBe(1)->evaluate(2, returnResult: true));
    }
}
