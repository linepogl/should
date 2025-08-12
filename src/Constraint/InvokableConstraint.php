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
     */
    abstract public function __invoke(mixed $actual): mixed;
}
