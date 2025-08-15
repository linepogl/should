<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsInstanceOf;

/**
 * @template E of object
 * @param class-string<E> $expected
 * @return ShouldNotBeA<E>
 */
function shouldNotBeA(string $expected, string $message = ''): ShouldNotBeA
{
    return new ShouldNotBeA($expected, $message);
}

/**
 * @template E of object
 */
class ShouldNotBeA extends ShouldNotSatisfy
{
    /**
     * @param class-string<E> $expected
     */
    public function __construct(string $expected, string $message = '')
    {
        parent::__construct(new IsInstanceOf($expected), $message);
    }

    /**
     * @phpstan-assert !E $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
