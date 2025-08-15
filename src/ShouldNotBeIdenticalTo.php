<?php

declare(strict_types=1);

namespace Should;

use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalNot;

/**
 * @template E
 * @param E $expected
 * @return ShouldNotBeIdenticalTo<E>
 */

function shouldNotBeIdenticalTo(mixed $expected, string $message = ''): ShouldNotBeIdenticalTo
{
    return new ShouldNotBeIdenticalTo($expected, $message);
}

/**
 * @template E
 */
class ShouldNotBeIdenticalTo extends ShouldSatisfy
{
    /** @param E $expected */
    public function __construct(mixed $expected, string $message = '')
    {
        parent::__construct(new LogicalNot(new IsIdentical($expected)), $message);
    }
}
