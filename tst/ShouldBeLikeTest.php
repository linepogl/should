<?php

declare(strict_types=1);

namespace Tests;

use ArrayIterator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

use function ImpartialPipes\pipe;
use function Should\shouldBeLike;
use function Should\shouldBeUndefined;
use function Should\shouldMatch;
use function Should\shouldThrow;

class ShouldBeLikeTest extends TestCase
{
    public function test_should_be_like_basic(): void
    {
        pipe(1)->to(shouldBeLike(1));
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe(2)->to(shouldBeLike(1))
        );
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe('1')->to(shouldBeLike(1))
        );

        pipe('test')->to(shouldBeLike('test'));
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe('test2')->to(shouldBeLike('test'))
        );
    }

    public function test_should_be_like_constraint(): void
    {
        pipe('test')->to(shouldBeLike(shouldMatch('/^t.+t$/')));
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe('post')->to(shouldBeLike(shouldMatch('/^t.+t$/')))
        );
    }

    public function test_should_be_like_array_list(): void
    {
        pipe([])->to(shouldBeLike([]));
        pipe([])->to(shouldBeLike([shouldBeUndefined()]));
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe([1])->to(shouldBeLike([]))
        );

        pipe([1, 2, 3])->to(shouldBeLike([1, 2, 3]));
        pipe([1, 2, 3])->to(shouldBeLike([1, 2, 3, shouldBeUndefined()]));
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe([1, 2, 3])->to(shouldBeLike([1, 2]))
        );

        pipe(new ArrayIterator([1, 2, 3]))->to(shouldBeLike([1, 2, 3]));
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe(new ArrayIterator([1, 2, 3]))->to(shouldBeLike([1, 2]))
        );
    }

    public function test_should_be_like_associative_array(): void
    {
        pipe(['a' => 1])->to(shouldBeLike(['a' => 1]));
        pipe(['a' => 1, 'b' => 2])->to(shouldBeLike(['a' => 1]));
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe([])->to(shouldBeLike(['a' => 1]))
        );
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe(['a' => 0])->to(shouldBeLike(['a' => 1]))
        );
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe(['a' => '1'])->to(shouldBeLike(['a' => 1]))
        );
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe(['A' => 1])->to(shouldBeLike(['a' => 1]))
        );
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe(['a' => 0, 'b' => 2])->to(shouldBeLike(['a' => 1]))
        );
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe(['b' => 2])->to(shouldBeLike(['a' => 1]))
        );

        pipe(new ArrayIterator([1, 2, 3]))->to(shouldBeLike([1, 2, 3]));
        shouldThrow(ExpectationFailedException::class)(
            fn () => pipe(new ArrayIterator([1, 2, 3]))->to(shouldBeLike([1, 2]))
        );
    }

    public function test_should_be_like_nested_array(): void
    {
        pipe([['a' => ['b' => 2]]])->to(shouldBeLike([['a' => ['b' => 2]]]));

        shouldBeLike(['a' => shouldBeUndefined()])([]);
        shouldThrow(ExpectationFailedException::class)(fn () => shouldBeLike(['a' => shouldBeUndefined()])(['a' => null]));
    }
}
