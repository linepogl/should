<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\NativeType;

function shouldBeCallable(string $message = ''): ShouldBeCallable
{
    return new ShouldBeCallable($message);
}

class ShouldBeCallable extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new IsType(NativeType::Callable), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return callable
     * @phpstan-assert callable $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to callable)
    }
}
