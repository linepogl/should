<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\RegularExpression;

function shouldMatch(string $regexp, string $message = ''): ShouldMatch
{
    return new ShouldMatch($regexp, $message);
}

class ShouldMatch extends ShouldSatisfy
{
    public function __construct(string $regexp, string $message = '')
    {
        parent::__construct(new RegularExpression($regexp), $message);
    }

    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
