<?php

declare(strict_types=1);

namespace Should;

use Should\Constraint\IsAnything;

function shouldBeSomething(string $message = ''): ShouldBeSomething
{
    return new ShouldBeSomething($message);
}

final class ShouldBeSomething extends ShouldSatisfy
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct(new IsAnything(), $message);
    }
}
