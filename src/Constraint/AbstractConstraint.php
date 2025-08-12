<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;

abstract class AbstractConstraint extends Constraint
{
    abstract protected function doEvaluate(mixed $actual): void;

    #[Override]
    final public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        try {
            $this->doEvaluate($other);
        } catch (ExpectationFailedException $ex) {
            if ($returnResult) {
                return false;
            }
            throw new ExpectationFailedException(
                '' === $description
                ? sprintf("Failed asserting that %s.", $this->failureDescription($other))
                : $description,
                $ex->getComparisonFailure(),
            );
        }
        return $returnResult ?: null;
    }
}
