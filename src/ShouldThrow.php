<?php

declare(strict_types=1);

namespace Should;

use Override;
use Should\Constraint\Throws;
use Throwable;

/**
 * @template E of Throwable
 * @param class-string<E>|E $expected
 * @return ShouldThrow<E>
 */
function shouldThrow(string|Throwable $expected = Throwable::class, string $message = ''): ShouldThrow
{
    return new ShouldThrow($expected, $message);
}

/**
 * @template E of Throwable
 */
class ShouldThrow extends ShouldSatisfy
{
    /**
     * @param class-string<E>|E $expected
     */
    public function __construct(
        string|Throwable $expected = Throwable::class,
        string $message = '',
    ) {
        parent::__construct(new Throws($expected), $message);
    }

    /**
     * @param callable():mixed $actual
     * @return callable():mixed
     */
    #[Override]
    public function __invoke(mixed $actual): callable
    {
        return parent::__invoke($actual);
    }
}
