<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Util\Exporter;
use Should\Constraint\Util\CustomAssert;
use Should\Constraint\Util\Util;

use function Should\shouldBe;

final class IteratesLike extends AbstractConstraint
{
    /**
     * @param iterable<mixed,mixed> $expected
     */
    public function __construct(
        private readonly iterable $expected,
        private readonly bool $rewind = false,
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'iterates like ' . Util::anyToString($this->expected) . ($this->rewind ? ' and rewinds' : '');
    }

    #[Override]
    protected function doEvaluate(mixed $actual, CustomAssert $assert): void
    {
        $assert->assertIsIterable($actual, '', Util::comparisonFailure($this->expected, $actual));

        $expectedArray = [];
        foreach ($this->expected as $key => $value) {
            $expectedArray[] = ['key' => $key, 'value' => $value];
        }

        $actualArray = [];
        foreach ($actual as $key => $value) {
            $actualArray[] = ['key' => $key, 'value' => $value];
        }

        try {
            shouldBe(count($expectedArray))(count($actualArray));
            foreach ($expectedArray as $i => $expectedTuple) {
                shouldBe($expectedTuple['key'])($actualArray[$i]['key']);
                shouldBe($expectedTuple['value'])($actualArray[$i]['value']);
            }
        } catch (ExpectationFailedException) {
            throw $assert->expectationFailure(
                'Failed asserting that two iterables iterate the same way.',
                $this->expected,
                $actual,
                $this->tupleArrayToString($expectedArray),
                $this->tupleArrayToString($actualArray),
            );
        }

        if ($this->rewind) {
            iterator_to_array($actual); // iterate once more to see if it is rewindable
        }
    }

    /**
     * @param list<array{key:mixed,value:mixed}> $tupleArray
     */
    private function tupleArrayToString(array $tupleArray): string
    {
        return [] === $tupleArray
            ? 'Array &0 []'
            : "Array &0 [\n" . implode(array_map(static fn(array $tuple) => '    ' . Exporter::export($tuple['key']) . ' => ' . Exporter::export($tuple['value']) . ",\n", $tupleArray)) . ']';
    }
}
