<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\NativeType;

function shouldNotBeIterable(string $message = ''): ShouldNotBeIterable
{
    return new ShouldNotBeIterable($message);
}

class ShouldNotBeIterable extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new LogicalNot(new IsType(NativeType::Iterable)), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return A
     * @phpstan-assert !iterable<mixed> $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
