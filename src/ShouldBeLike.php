<?php

declare(strict_types=1);

namespace Should;

use Override;
use Should\Constraint\IsLike;

function shouldBeLike(mixed $pattern, string $message = ''): ShouldBeLike
{
    return new ShouldBeLike($pattern, $message);
}

class ShouldBeLike extends ShouldSatisfy
{
    public function __construct(mixed $pattern, string $message = '')
    {
        parent::__construct(new IsLike($pattern), $message);
    }

    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
