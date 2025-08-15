<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsList;

function shouldBeList(string $message = ''): ShouldBeList
{
    return new ShouldBeList($message);
}

class ShouldBeList extends ShouldSatisfy
{
    public function __construct(string $message = '')
    {
        parent::__construct(new IsList(), $message);
    }

    /**
     * @template A
     * @param A $actual
     * @return list<mixed>
     * @phpstan-assert list<mixed> $actual
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual); // @phpstan-ignore return.type (narrowing type from mixed to list)
    }
}
