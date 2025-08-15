<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * @template E
 * @param E $expected
 * @return ShouldBeIdenticalTo<E>
 */

function shouldBeIdenticalTo(mixed $expected, string $message = ''): ShouldBeIdenticalTo
{
    return new ShouldBeIdenticalTo($expected, $message);
}

/**
 * @template E
 */
class ShouldBeIdenticalTo extends ShouldSatisfy
{
    /** @param E $expected */
    public function __construct(mixed $expected, string $message = '')
    {
        parent::__construct(new IsIdentical($expected), $message);
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
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from A to E)
    }
}
