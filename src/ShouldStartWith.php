<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\StringStartsWith;

function shouldStartWith(string $expected, string $message = ''): ShouldStartWith
{
    return new ShouldStartWith($expected, $message);
}

class ShouldStartWith extends ShouldSatisfy
{
    public function __construct(string $expected, string $message = '')
    {
        parent::__construct(new StringStartsWith($expected), $message);
    }

    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
