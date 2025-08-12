<?php

declare(strict_types=1);

namespace Should;

use Override;
use Should\Constraint\Is;

function shouldBe(mixed $expected, string $message = ''): ShouldBe
{
    return new ShouldBe($expected, $message);
}

class ShouldBe extends ShouldSatisfy
{
    public function __construct(mixed $expected, string $message = '')
    {
        parent::__construct(new Is($expected), $message);
    }

    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
