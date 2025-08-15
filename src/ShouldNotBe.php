<?php

declare(strict_types=1);

namespace Should;

use Should\Constraint\Is;

function shouldNotBe(mixed $expected, string $message = ''): ShouldNotBe
{
    return new ShouldNotBe($expected, $message);
}

class ShouldNotBe extends ShouldNotSatisfy
{
    public function __construct(mixed $expected, string $message = '')
    {
        parent::__construct(new Is($expected), $message);
    }
}
