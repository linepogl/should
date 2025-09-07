<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnitMetaConstraints\IteratesLike;

/**
 * @param iterable<mixed,mixed> $expected
 */
function shouldIterateLike(iterable $expected, bool $rewind = false, string $message = ''): ShouldIterateLike
{
    return new ShouldIterateLike($expected, $rewind, $message);
}

class ShouldIterateLike extends ShouldSatisfy
{
    /**
     * @param iterable<mixed,mixed> $expected
     */
    public function __construct(
        iterable $expected,
        bool $rewind = false,
        string $message = '',
    ) {
        parent::__construct(new IteratesLike($expected, $rewind), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return iterable<mixed, mixed>
     * @phpstan-assert iterable<mixed, mixed> $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from A to iterable<mixed, mixed>)
    }
}
