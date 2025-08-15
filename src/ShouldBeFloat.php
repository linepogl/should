<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\NativeType;

function shouldBeFloat(string $message = ''): ShouldBeFloat
{
    return new ShouldBeFloat($message);
}

class ShouldBeFloat extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new IsType(NativeType::Float), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return float
     * @phpstan-assert float $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to float)
    }
}
