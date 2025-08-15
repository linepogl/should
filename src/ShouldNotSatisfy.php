<?php

declare(strict_types=1);

namespace Should;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;

function shouldNotSatisfy(Constraint $constraint, string $message = ''): ShouldNotSatisfy
{
    return new ShouldNotSatisfy($constraint, $message);
}

class ShouldNotSatisfy extends ShouldSatisfy
{
    public function __construct(
        Constraint $constraint,
        string $message = '',
    ) {
        parent::__construct(new LogicalNot($constraint), $message);
    }
}
