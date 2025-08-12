<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsIdentical;

function shouldBeIdenticalTo(mixed $expected, string $message = ''): ShouldBeIdenticalTo
{
    return new ShouldBeIdenticalTo($expected, $message);
}

class ShouldBeIdenticalTo extends ShouldSatisfy
{
    public function __construct(mixed $expected, string $message = '')
    {
        parent::__construct(new IsIdentical($expected), $message);
    }

    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
