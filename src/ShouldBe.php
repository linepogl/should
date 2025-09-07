<?php

declare(strict_types=1);

namespace Should;

use PHPUnitMetaConstraints\Is;

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
}
