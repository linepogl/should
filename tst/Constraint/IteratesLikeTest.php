<?php

declare(strict_types=1);

namespace Tests\Constraint;

use ArrayIterator;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Should\Constraint\IteratesLike;
use Should\Constraint\Util;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldIterateLike;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class IteratesLikeTest extends TestCase
{
    /**
     * @return iterable<string, array{iterable<mixed, mixed>, mixed}|array{iterable<mixed, mixed>, mixed, ?string}>
     */
    public static function cases(): iterable
    {
        yield 'array 1' => [[], []];
        yield 'array 2' => [[], [1], 'Failed asserting that two iterables iterate the same way.'];

        yield 'array 3' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2]];
        yield 'array 4' => [['a' => 1, 'b' => 2], ['b' => 2, 'a' => 1], 'Failed asserting that two iterables iterate the same way.'];
        yield 'array 5' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => '2'], 'Failed asserting that two iterables iterate the same way.'];
        yield 'array 6' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2, 'c' => 3], 'Failed asserting that two iterables iterate the same way.'];
        yield 'array 7' => [['a' => 1, 'b' => 2], ['a' => 1], 'Failed asserting that two iterables iterate the same way.'];
    }

    /**
     * @param iterable<mixed, mixed> $expected
     */
    #[DataProvider('cases')]
    public function test_iterates_like(iterable $expected, mixed $actual, ?string $error = null): void
    {
        $constraint = new IteratesLike($expected);
        if (null === $error) {
            pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
            shouldNotThrow()(fn () => $constraint->evaluate($actual));
        } else {
            pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
            shouldThrow(Util::comparisonFailure($error, $expected, $actual))(
                fn () => $constraint->evaluate($actual)
            );
            shouldThrow(Util::comparisonFailure('Custom message', $expected, $actual))(
                fn () => $constraint->evaluate($actual, 'Custom message')
            );
        }
    }

    public function test_iterates_like_repeatedly(): void
    {
        shouldNotThrow()(
            fn () =>
            pipe([1, 2])
            ->to(shouldIterateLike([1, 2], repeatedly: true))
        );

        shouldNotThrow()(
            fn () =>
            pipe(new ArrayIterator([1, 2]))
            ->to(shouldIterateLike([1, 2], repeatedly: true))
        );

        shouldThrow(new Exception('Cannot traverse an already closed generator'))(
            fn () =>
            pipe((function () {
                yield 1;
                yield 2;
            })())
            ->to(shouldIterateLike([1, 2], repeatedly: true))
        );
    }
}
