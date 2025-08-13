<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;

abstract class AbstractConstraint extends Constraint
{
    abstract protected function doEvaluate(mixed $actual, Assert $assert): void;

    #[Override]
    final public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        try {
            $this->doEvaluate($other, new Assert($description));
        } catch (ExpectationFailedException $ex) {
            if ($returnResult) {
                return false;
            }
            throw $ex;
        }
        return $returnResult ?: null;
    }
}
