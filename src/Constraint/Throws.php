<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\Assert;
use Throwable;

final class Throws extends AbstractConstraint
{
    /**
     * @param null|class-string<Throwable>|Throwable $expected
     */
    public function __construct(
        private readonly null|string|Throwable $expected = null,
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'throws ' . match(true) {
            null === $this->expected => 'an exception',
            $this->expected instanceof Throwable => $this->expected::class,
            default => $this->expected,
        };
    }

    #[Override]
    protected function doEvaluate(mixed $actual): void
    {
        if (!is_callable($actual)) {
            Assert::fail('Expected a callable, got ' . get_debug_type($actual));
        }
        $class = null === $this->expected || is_string($this->expected) ? $this->expected : $this->expected::class;
        try {
            $actual();
        } catch (Throwable $ex) {
            if (null !== $class) {
                Assert::assertInstanceOf($class, $ex, 'Failed asserting that the thrown exception is an instance of ' . $class . '.');
                if ($this->expected instanceof Throwable) {
                    Assert::assertEquals($this->expected->getMessage(), $ex->getMessage(), 'Failed asserting that the two exceptions have the same message.');
                    Assert::assertEquals($this->expected->getCode(), $ex->getCode(), 'Failed asserting that the two exceptions have the same code.');
                }
            }
            return;
        }
        Assert::fail('Expected ' . ($class ?? 'an exception') . ' to be thrown, but nothing was.');
    }
}
