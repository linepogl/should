<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\StringStartsWith;
use PHPUnit\Framework\NativeType;

function shouldNotStartWith(string $expected, string $message = ''): ShouldNotStartWith
{
    return new ShouldNotStartWith($expected, $message);
}

class ShouldNotStartWith extends ShouldSatisfy
{
    public function __construct(string $expected, string $message = '')
    {
        parent::__construct(
            LogicalAnd::fromConstraints(
                new IsType(NativeType::String),
                new LogicalNot(new StringStartsWith($expected)),
            ),
            $message,
        );
    }

    #[Override]
    public function __invoke(mixed $actual): mixed
    {
        return parent::__invoke($actual);
    }
}
