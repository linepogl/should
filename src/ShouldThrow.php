<?php

declare(strict_types=1);

namespace Should;

use Override;
use Should\Constraint\Throws;
use Throwable;

/**
 * @param null|class-string<Throwable>|Throwable $expected
 */
function shouldThrow(null|string|Throwable $expected = null, string $message = ''): ShouldThrow
{
    return new ShouldThrow($expected, $message);
}

class ShouldThrow extends ShouldSatisfy
{
    /**
     * @param null|class-string<Throwable>|Throwable $expected
     */
    public function __construct(
        null|string|Throwable $expected = null,
        string $message = '',
    ) {
        parent::__construct(new Throws($expected), $message);
    }
    /**
     * @template A
     * @param callable():A $actual
     * @return callable():A
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
