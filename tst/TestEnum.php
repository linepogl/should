<?php

declare(strict_types=1);

namespace Tests;

use ArrayIterator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBeLike;
use function Should\shouldMatch;
use function Should\shouldThrow;

enum TestEnum: string {
    case A = 'a';
    case B = 'b';
}
