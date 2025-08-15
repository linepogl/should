<?php

declare(strict_types=1);

namespace Tests\Constraint;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Should\Constraint\IsUndefined;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldThrow;

class IsUndefinedTest extends TestCase
{
    public function test_is_undefined(): void
    {
        shouldThrow(RuntimeException::class)(
            static fn() => new IsUndefined()->evaluate(null)
        );
    }
    public function test_to_string(): void
    {
        pipe(new IsUndefined()->toString())->to(shouldBe('is undefined'));
    }
}
