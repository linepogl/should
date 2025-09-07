<?php

declare(strict_types=1);

namespace Should;

use PHPUnit\Framework\Constraint\IsAnything;

function shouldBeAnything(string $message = ''): ShouldBeAnything
{
    return new ShouldBeAnything($message);
}

final class ShouldBeAnything extends ShouldSatisfy
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct(new IsAnything(), $message);
    }
}
