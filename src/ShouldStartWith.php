<?php

declare(strict_types=1);

namespace Should;

use Override;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\StringStartsWith;
use PHPUnit\Framework\NativeType;

function shouldStartWith(string $expected, string $message = ''): ShouldStartWith
{
    return new ShouldStartWith($expected, $message);
}

class ShouldStartWith extends ShouldSatisfy
{
    public function __construct(string $expected, string $message = '')
    {
        parent::__construct(
            LogicalAnd::fromConstraints(
                new IsType(NativeType::String),
                new StringStartsWith($expected),
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
