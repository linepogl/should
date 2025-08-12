<?php

declare(strict_types=1);

namespace Should;

use Override;
use Should\Constraint\Is;

function shouldBeUndefined(string $message = ''): ShouldBeUndefined
{
    return new ShouldBeUndefined($message);
}

final class ShouldBeUndefined
{
    public function __construct(
        public readonly string $message = '',
    ) {
    }
}
