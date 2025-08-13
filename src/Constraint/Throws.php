<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Util\Exporter;
use Throwable;

/**
 * @template E of Throwable
 */
final class Throws extends AbstractConstraint
{
    /**
     * @param class-string<E>|E $expected
     */
    public function __construct(
        private readonly string|Throwable $expected = Throwable::class,
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'throws ' . match(true) {
            $this->expected instanceof Throwable => $this->expected::class,
            default => $this->expected,
        };
    }

    #[Override]
    protected function doEvaluate(mixed $actual, Assert $assert): void
    {
        if (!is_callable($actual)) {
            $assert->fail('Expected a callable, got ' . get_debug_type($actual));
        }
        $class = is_string($this->expected) ? $this->expected : $this->expected::class;
        try {
            $actual();
        } catch (Throwable $ex) {
            $assert->assertInstanceOf($class, $ex, 'Failed asserting that the thrown exception is an instance of ' . $class . ":\n" . Exporter::export($ex));
            if ($this->expected instanceof Throwable) {
                $assert->assertEquals($this->expected->getMessage(), $ex->getMessage(), 'Failed asserting that the two exceptions have the same message.');
                $assert->assertEquals($this->expected->getCode(), $ex->getCode(), 'Failed asserting that the two exceptions have the same code.');
            }
            return;
        }
        $assert->fail('Expected ' . $class . ' to be thrown, but nothing was.');
    }
}
