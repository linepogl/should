<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\NativeType;

function shouldNotMatch(string $regexp, string $message = ''): ShouldNotMatch
{
    return new ShouldNotMatch($regexp, $message);
}

class ShouldNotMatch extends ShouldSatisfy
{
    public function __construct(string $regexp, string $message = '')
    {
        parent::__construct(
            LogicalAnd::fromConstraints(
                new IsType(NativeType::String),
                new LogicalNot(new RegularExpression($regexp)),
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
