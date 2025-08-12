<?php

declare(strict_types=1);

namespace Should\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

abstract class InvokableConstraint extends Constraint
{
    /**
     * @template A
     * @param A $actual
     * @return A
     * @codeCoverageIgnore -- how do we test an abstract method?
     */
    abstract public function __invoke(mixed $actual): mixed;
}
