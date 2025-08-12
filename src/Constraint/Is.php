<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Util\Exporter;
use function Should\shouldBe;

final class Is extends AbstractConstraint
{
    public function __construct(
        private readonly mixed $pattern,
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return (is_scalar($this->pattern) || is_null($this->pattern) ? 'is ' : 'is equal to ') . Exporter::export($this->pattern);
    }

    #[Override]
    protected function doEvaluate(mixed $actual): void
    {
        if (is_null($this->pattern)) {
            Assert::assertNull($actual);
        } elseif (is_scalar($this->pattern)) {
            Assert::assertSame($actual, $this->pattern);
        } else {
            Assert::assertEquals($actual, $this->pattern);
        }
    }
}
