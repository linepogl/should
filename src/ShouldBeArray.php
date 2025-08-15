<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\NativeType;

function shouldBeArray(string $message = ''): ShouldBeArray
{
    return new ShouldBeArray($message);
}

class ShouldBeArray extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new IsType(NativeType::Array), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return array<mixed, mixed>
     * @phpstan-assert array<mixed, mixed> $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to array)
    }
}
