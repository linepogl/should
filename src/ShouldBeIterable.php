<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\NativeType;

function shouldBeIterable(string $message = ''): ShouldBeIterable
{
    return new ShouldBeIterable($message);
}

class ShouldBeIterable extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new IsType(NativeType::Iterable), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return iterable<mixed>
     * @phpstan-assert iterable<mixed, mixed> $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to iterable<mixed, mixed>)
    }
}
