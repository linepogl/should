<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\NativeType;

function shouldMatch(string $regexp, string $message = ''): ShouldMatch
{
    return new ShouldMatch($regexp, $message);
}

class ShouldMatch extends ShouldSatisfy
{
    public function __construct(string $regexp, string $message = '')
    {
        parent::__construct(
            LogicalAnd::fromConstraints(
                new IsType(NativeType::String),
                new RegularExpression($regexp),
            ),
            $message,
        );
    }

    /**
     * @template A
     * @param A $actual
     * @return string
     * @return mixed
     */
    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
