<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsInstanceOf;

/**
 * @template E of object
 * @param class-string<E> $expected
 * @return ShouldBeA<E>
 */
function shouldBeA(string $expected, string $message = ''): ShouldBeA
{
    return new ShouldBeA($expected, $message);
}

/**
 * @template E of object
 */
class ShouldBeA extends ShouldSatisfy
{
    /**
     * @param class-string<E> $expected
     */
    public function __construct(string $expected, string $message = '')
    {
        parent::__construct(new IsInstanceOf($expected), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return E
     * @phpstan-assert E $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to E)
    }
}
