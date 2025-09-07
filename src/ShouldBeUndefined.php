<?php

declare(strict_types=1);

namespace Should;

use LogicException;
use PHPUnit\Framework\Assert;
use PHPUnitMetaConstraints\IsUndefined;

function shouldBeUndefined(string $message = ''): ShouldBeUndefined
{
    return new ShouldBeUndefined($message);
}

final class ShouldBeUndefined extends IsUndefined
{
    public function __construct(
        private readonly string $message = '',
    ) {
        parent::__construct();
    }

    /**
     * @template A
     * @param A $actual
     */
    public function __invoke(mixed $actual): void
    {
        Assert::assertThat($actual, $this, $this->message);
        /** @codeCoverageIgnore -- This line is never reached. */
        throw new LogicException('This line is never reached.');
    }
}
