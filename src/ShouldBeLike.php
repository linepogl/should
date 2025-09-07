<?php

declare(strict_types=1);

namespace Should;

use PHPUnitMetaConstraints\IsLike;

function shouldBeLike(mixed $expected, string $message = ''): ShouldBeLike
{
    return new ShouldBeLike($expected, $message);
}

class ShouldBeLike extends ShouldSatisfy
{
    public function __construct(mixed $expected, string $message = '')
    {
        parent::__construct(new IsLike($expected), $message);
    }
}
