<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnitMetaConstraints\IteratesLike;

/**
 * @param iterable<mixed,mixed> $expected
 */
function shouldNotIterateLike(iterable $expected, bool $rewind = false, string $message = ''): ShouldNotIterateLike
{
    return new ShouldNotIterateLike($expected, $rewind, $message);
}

class ShouldNotIterateLike extends ShouldNotSatisfy
{
    /**
     * @param iterable<mixed,mixed> $expected
     */
    public function __construct(
        private readonly iterable $expected,
        bool $rewind = false,
        string $message = '',
    ) {
        parent::__construct(new IteratesLike($expected, $rewind), $message);
    }

    #[Override]
    public function toString(): string
    {
        return 'does not iterate like ' . (is_array($this->expected) ? 'an array' : 'some ' . get_debug_type($this->expected));
    }
}
