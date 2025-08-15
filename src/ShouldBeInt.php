<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\NativeType;

function shouldBeInt(string $message = ''): ShouldBeInt
{
    return new ShouldBeInt($message);
}

class ShouldBeInt extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new IsType(NativeType::Int), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return int
     * @phpstan-assert int $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to int)
    }
}
