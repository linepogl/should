<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\NativeType;

function shouldBeBool(string $message = ''): ShouldBeBool
{
    return new ShouldBeBool($message);
}

class ShouldBeBool extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new IsType(NativeType::Bool), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return bool
     * @phpstan-assert bool $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to bool)
    }
}
