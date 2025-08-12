<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use Should\Constraint\InvokableConstraint;

function shouldSatisfy(Constraint $constraint, string $message = ''): ShouldSatisfy
{
    return new ShouldSatisfy($constraint, $message);
}

class ShouldSatisfy extends InvokableConstraint
{
    public function __construct(
        public readonly Constraint $inner,
        public readonly string $message = '',
    ) {
    }

    #[Override]
    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        return $this->inner->evaluate($other, '' !== $description ? $description : $this->message, $returnResult);
    }

    #[Override]
    public function toString(): string
    {
        return $this->inner->toString();
    }

    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        Assert::assertThat($actual, $this->inner, $this->message);
        return $actual;
    }
}
