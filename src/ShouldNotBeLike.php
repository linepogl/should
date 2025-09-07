<?php

declare(strict_types=1);

namespace Should;

use PHPUnitMetaConstraints\IsLike;

function shouldNotBeLike(mixed $expected, string $message = ''): ShouldNotBeLike
{
    return new ShouldNotBeLike($expected, $message);
}

class ShouldNotBeLike extends ShouldNotSatisfy
{
    public function __construct(mixed $expected, string $message = '')
    {
        parent::__construct(new IsLike($expected), $message);
    }
}
