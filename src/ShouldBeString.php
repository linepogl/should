<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\NativeType;

function shouldBeString(string $message = ''): ShouldBeString
{
    return new ShouldBeString($message);
}

class ShouldBeString extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new IsType(NativeType::String), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return string
     * @phpstan-assert string $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to string)
    }
}
