<?php

declare(strict_types=1);

namespace Should;

use Should\Constraint\IsUndefined;

function shouldBeUndefined(string $message = ''): ShouldBeUndefined
{
    return new ShouldBeUndefined($message);
}

final class ShouldBeUndefined extends ShouldSatisfy
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct(new IsUndefined(), $message);
    }
}
