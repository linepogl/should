<?php

declare(strict_types=1);

namespace Should\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Util\Exporter;
use SebastianBergmann\Comparator\ComparisonFailure;

final class Util
{
    public static function comparisonFailure(string $message, mixed $expected, mixed $actual): ExpectationFailedException
    {
        return new ExpectationFailedException(
            $message,
            new ComparisonFailure($expected, $actual, Exporter::export($expected), Exporter::export($actual)),
        );
    }

    public static function eval(Constraint $constraint, mixed $value): bool
    {
        return $constraint->evaluate($value, '', true) ?? true;
    }
}
