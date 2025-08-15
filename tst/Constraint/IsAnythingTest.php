<?php

declare(strict_types=1);

namespace Tests\Constraint;

use PHPUnit\Framework\TestCase;
use Should\Constraint\IsAnything;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldNotThrow;

class IsAnythingTest extends TestCase
{
    public function test_is_anything(): void
    {
        shouldNotThrow()(
            static fn() => new IsAnything()->evaluate(null)
        );
    }
    public function test_to_string(): void
    {
        pipe(new IsAnything()->toString())->to(shouldBe('is anything'));
    }
}
