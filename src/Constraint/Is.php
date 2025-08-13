<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Util\Exporter;

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
    protected function doEvaluate(mixed $actual, Assert $assert): void
    {
        if (null === $this->expected || is_scalar($this->expected)) {
            $assert->assertThat($actual, new IsIdentical($this->expected), $assert->comparisonFailure(
                is_scalar($actual) || is_null($actual)
                    ? 'Failed asserting that ' . Exporter::export($actual) . ' ' . $this->toString() . '.'
                    : 'Failed asserting that some ' . get_debug_type($actual) . ' ' . $this->toString() . '.',
                $this->expected,
                $actual,
            ));
        } elseif (is_array($this->expected)) {
            if (is_array($actual)) {
                $assert->assertThat($actual, new IteratesLike($this->expected), $assert->comparisonFailure(
                    'Failed asserting that two arrays are equal.',
                    $this->expected,
                    $actual,
                ));
            } else {
                throw $assert->comparisonFailure(
                    is_scalar($actual) || is_null($actual)
                        ? 'Failed asserting that ' . Exporter::export($actual) . ' '.$this->toString().'.'
                        : 'Failed asserting that some ' . get_debug_type($actual) . ' '.$this->toString().'.',
                    $this->expected,
                    $actual,
                );
            }
        } else {
            $assert->assertThat($actual, new IsEqual($this->expected), $assert->comparisonFailure(
                match (true) {
                    is_object($actual) => 'Failed asserting that two objects are equal.',
                    is_scalar($actual), is_null($actual) => 'Failed asserting that ' . Exporter::export($actual) . ' ' . $this->toString() . '.',
                    default => 'Failed asserting that some ' . get_debug_type($actual) . ' ' . $this->toString() . '.',
                },
                $this->expected,
                $actual,
            ));
        }
    }
}
