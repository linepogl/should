<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use Should\Constraint\Util\CustomAssert;

final class IsAnything extends AbstractConstraint
{
    public function __construct(
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'is anything';
    }

    #[Override]
    protected function doEvaluate(mixed $actual, CustomAssert $assert): void
    {
    }
}
