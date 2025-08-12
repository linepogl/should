<?php

declare(strict_types=1);

namespace Should;

use Override;
use Should\Constraint\IteratesLike;

/**
 * @param iterable<mixed,mixed> $expected
 */
function shouldIterateLike(iterable $expected, string $message = ''): ShouldIterateLike
{
    return new ShouldIterateLike($expected, $message);
}

class ShouldIterateLike extends ShouldSatisfy
{
    /**
     * @param iterable<mixed,mixed> $expected
     */
    public function __construct(
        iterable $expected,
        string $message = '',
    ) {
        parent::__construct(new IteratesLike($expected), $message);
    }

    /**
     * @template K
     * @template V
     * @param iterable<K,V> $actual
     * @return iterable<K,V>
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
