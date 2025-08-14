<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Util\Exporter;
use SebastianBergmann\Comparator\ArrayComparator;
use SebastianBergmann\Comparator\Factory as ComparatorFactory;
use SebastianBergmann\Comparator\NumericComparator;
use SebastianBergmann\Comparator\ObjectComparator;
use SebastianBergmann\Comparator\ScalarComparator;
use SebastianBergmann\Comparator\TypeComparator;
use Should\Constraint\Util\CustomAssert;
use Should\Constraint\Util\Util;

final class Is extends AbstractConstraint
{
    public function __construct(
        private readonly mixed $expected,
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return match(true) {
            null === $this->expected,
            is_scalar($this->expected) => 'is ' . Exporter::export($this->expected),
            is_array($this->expected) => 'is equal to an array',
            default => 'is equal to some ' . get_debug_type($this->expected),
        };
    }

    #[Override]
    protected function doEvaluate(mixed $actual, CustomAssert $assert): void
    {
        if ($this->expected === $actual) {
            return; // nothing to do here, identical values are always equal
        }

        // `IsEqual` uses the ComparatorFactory to find what to do. Some default comparators are more permissive
        // than others. Let's eliminate the permissive ones.
        $comparator = ComparatorFactory::getInstance()->getComparatorFor($this->expected, $actual);

        // ObjectComparator uses the permissive `==` comparison
        if ($comparator::class === ObjectComparator::class && is_object($this->expected) && is_object($actual)) {
            if ($this->expected::class !== $actual::class) {
                $assert->fail(
                    'Failed asserting that two objects are equal. Expected class "' . $this->expected::class . '" but got "' . $actual::class . '".',
                    Util::comparisonFailure($this->expected, $actual),
                );
            }
            $expArray = new \SebastianBergmann\Exporter\Exporter()->toArray($this->expected);
            $actArray = new \SebastianBergmann\Exporter\Exporter()->toArray($actual);

            $assert->assertIteratesLike(
                $expArray,
                $actArray,
                'Failed asserting that two objects are equal.',
                Util::comparisonFailure($this->expected, $actual),
            );
        }

        // ArrayComparator uses the permissive `==` comparison
        elseif ($comparator::class === ArrayComparator::class && is_array($this->expected) && is_array($actual)) {
            $assert->assertIteratesLike(
                $this->expected,
                $actual,
                'Failed asserting that two arrays are equal.',
                Util::comparisonFailure($this->expected, $actual),
            );
        }

        // ScalarComparator and NumericComparator do strange things, so let's use the strict `IsIdentical`.
        // TypeComparator works fine, but its message could be improved. Let's `IsIdentical` as well.
        elseif ($comparator::class === ScalarComparator::class || $comparator::class === NumericComparator::class || $comparator::class === TypeComparator::class) {
            $assert->assertSame(
                $this->expected,
                $actual,
                is_scalar($actual) || is_null($actual)
                    ? 'Failed asserting that ' . Exporter::export($actual) . ' ' . $this->toString() . '.'
                    : 'Failed asserting that some ' . get_debug_type($actual) . ' ' . $this->toString() . '.',
                Util::comparisonFailure($this->expected, $actual),
            );
        }

        // The rest of the cases are ok, especially if there are custom overloaded comparators.
        else {
            $assert->assertEquals($this->expected, $actual);
        }
    }
}
