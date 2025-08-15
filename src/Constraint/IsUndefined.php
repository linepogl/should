<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use Should\Constraint\Util\CustomAssert;

final class IsUndefined extends AbstractConstraint
{
    public function __construct(
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'is undefined';
    }

    #[Override]
    protected function doEvaluate(mixed $actual, CustomAssert $assert): void
    {
        $assert->fail('This should never be called directly, use IsLike instead.');
    }
}
