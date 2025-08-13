<?php

declare(strict_types=1);

namespace Should;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Util\Exporter;
use Should\Constraint\Assert;
use Throwable;

/**
 * @template E of Throwable
 * @param class-string<E>|E $expected
 * @return ShouldNotThrow<E>
 */
function shouldNotThrow(string|Throwable $expected = Throwable::class, string $message = ''): ShouldNotThrow
{
    return new ShouldNotThrow($expected, $message);
}

/**
 * @template E of Throwable
 */
class ShouldNotThrow
{
    /**
     * @param class-string<E>|E $expected
     */
    public function __construct(
        private readonly string|Throwable $expected = Throwable::class,
        private readonly string $message = '',
    ) {
    }

    /**
     * @param callable():mixed $actual
     */
    public function __invoke(mixed $actual): void
    {
        $class = is_string($this->expected) ? $this->expected : $this->expected::class;
        $assert = new Assert($this->message);

        try {
            $ex = shouldThrow($this->expected)($actual);
        } catch (ExpectationFailedException) {
            $assert->success();
            return;
        }
        $assert->fail("Unexpected " . $ex::class . " was thrown: " . Exporter::export($ex));
    }
}
