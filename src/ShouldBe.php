<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsEqual;

function shouldBe(mixed $expected, string $message = ''): ShouldBe
{
    return new ShouldBe($expected, $message);
}

class ShouldBe extends ShouldSatisfy
{
    public function __construct(mixed $expected, string $message = '')
    {
        parent::__construct(new IsEqual($expected), $message);
    }

    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
