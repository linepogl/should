<?php

declare(strict_types=1);

namespace Should;

use Override;
use Should\Constraint\IteratesLike;

/**
 * @param iterable<mixed,mixed> $expected
 */
function shouldIterateLike(iterable $expected, bool $repeatedly = false, string $message = ''): ShouldIterateLike
{
    return new ShouldIterateLike($expected, $repeatedly, $message);
}

class ShouldIterateLike extends ShouldSatisfy
{
    /**
     * @param iterable<mixed,mixed> $expected
     */
    public function __construct(
        iterable $expected,
        bool $repeatedly = false,
        string $message = '',
    ) {
        parent::__construct(new IteratesLike($expected, $repeatedly), $message);
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
