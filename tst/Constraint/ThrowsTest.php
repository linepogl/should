<?php

declare(strict_types=1);

namespace Tests\Constraint;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Should\Constraint\Throws;

use function ImpartialPipes\pipe;
use function Should\shouldBe;

class ThrowsTest extends TestCase
{
    public function test_throws(): void
    {
        $f1 = static fn() => throw new RuntimeException('Test');
        $f2 = static fn() => null;
        pipe(new Throws(RuntimeException::class)->evaluate($f1, '', true))->to(shouldBe(true));
        pipe(new Throws(new RuntimeException('Test'))->evaluate($f1, '', true))->to(shouldBe(true));
        pipe(new Throws(new RuntimeException('Test1'))->evaluate($f1, '', true))->to(shouldBe(false));
        pipe(new Throws(InvalidArgumentException::class)->evaluate($f1, '', true))->to(shouldBe(false));
        pipe(new Throws(new InvalidArgumentException('Test'))->evaluate($f1, '', true))->to(shouldBe(false));
        pipe(new Throws(RuntimeException::class)->evaluate($f2, '', true))->to(shouldBe(false));
        pipe(new Throws(new RuntimeException('Test'))->evaluate($f2, '', true))->to(shouldBe(false));
    }

    public function test_to_string(): void
    {
        pipe(new Throws(RuntimeException::class)->toString())->to(shouldBe('throws RuntimeException'));
        pipe(new Throws(new RuntimeException())->toString())->to(shouldBe('throws RuntimeException'));
    }
}
