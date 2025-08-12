<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\Assert;
use PHPUnit\Util\Exporter;

final class IteratesLike extends AbstractConstraint
{
    /**
     * @param iterable<mixed,mixed> $expected
     */
    public function __construct(
        private readonly iterable $expected,
        private readonly bool $repeatedly = false,
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return ($this->repeatedly ? 'repeatedly ' : '') . 'iterates like ' . Exporter::export($this->expected);
    }

    #[Override]
    protected function doEvaluate(mixed $actual): void
    {
        if (!is_iterable($actual)) {
            Assert::fail('Expected an iterable, got ' . get_debug_type($actual));
        }

        $expectedArray = [];
        foreach ($this->expected as $key => $value) {
            $expectedArray[] = [$key, $value];
        }

        $actualArray = [];
        foreach ($actual as $key => $value) {
            $actualArray[] = [$key, $value];
        }
        Assert::assertEquals($expectedArray, $actualArray);

        if ($this->repeatedly) {
            iterator_to_array($actual); // iterate once more to see if it is rewindable
        }
    }
}
