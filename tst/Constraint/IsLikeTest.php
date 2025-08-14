<?php

declare(strict_types=1);

namespace Tests\Constraint;

use ArrayIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Should\Constraint\Is;
use Should\Constraint\IsLike;
use Should\Constraint\Util\Util;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldBeUndefined;
use function Should\shouldMatch;
use function Should\shouldNotThrow;
use function Should\shouldThrow;

class IsLikeTest extends TestCase
{
    /**
     * @return iterable<string, array{mixed, mixed}|array{mixed, mixed, string}|array{mixed, mixed, string, string}>
     */
    public static function cases(): iterable
    {
        yield 'int is like int' => [1, 1];
        yield 'int is not like int' => [1, 2, "Failed asserting that 2 is like 1.\nFailed asserting that 2 is 1.", 'Failed asserting that 2 is 1.'];
        yield 'int is like Constraint' => [new Is(1), 1];
        yield 'int is not like Constraint' => [new Is(1), 2, "Failed asserting that 2 is 1."];
        yield 'string is like RegExp' => [shouldMatch('/^[0-9]$/'), '1'];
        yield 'string is not like RegExp' => [shouldMatch('/^[0-9]$/'), 'X', "Failed asserting that 'X' is of type string and matches PCRE pattern \"/^[0-9]$/\"."];
        yield 'int is not like RegExp' => [shouldMatch('/^[0-9]$/'), 1, "Failed asserting that 1 is of type string and matches PCRE pattern \"/^[0-9]$/\"."];

        yield 'list is like list' => [[1,2,3], [1,2,3]];
        yield 'list is not like list (count)' => [[1,2,3], [1,2,3,4], "Failed asserting that an array is like an array.\nFailed asserting that actual size 4 matches expected size 3.", "Failed asserting that actual size 4 matches expected size 3."];
        yield 'list is not like list (undefined)' => [[1,2,3,shouldBeUndefined()], [1,2,3,4], "Failed asserting that an array is like an array.\nFailed asserting that an array does not have the key 3.", "Failed asserting that an array does not have the key 3."];

        yield 'iterable is like list' => [[1,2,3], new ArrayIterator([1,2,3])];
        yield 'not iterable is like list' => [[1,2,3], 1, "Failed asserting that 1 is like an array.\nFailed asserting that 1 is of type iterable.", "Failed asserting that 1 is of type iterable."];
        yield 'iterable is not like list (nested is)' => [[1,2,3], new ArrayIterator([1,22,3]), "Failed asserting that some ArrayIterator is like an array.\n→1: Failed asserting that 22 is 2.", "Failed asserting that 22 is 2."];
        yield 'iterable is not like list (nested constraint)' => [[1,new Is(2),3], new ArrayIterator([1,22,3]), "Failed asserting that some ArrayIterator is like an array.\n→1: Failed asserting that 22 is 2.", "Failed asserting that 22 is 2."];
        yield 'iterable is not like list (count)' => [[1,2,3], new ArrayIterator([1,2,3,4]), "Failed asserting that some ArrayIterator is like an array.\nFailed asserting that actual size 4 matches expected size 3.", "Failed asserting that actual size 4 matches expected size 3."];

        yield 'array is like array' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2]];
        yield 'array with more keys is like array' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2, 'c' => 3]];
        yield 'array is not like array (missing keys)' => [['a' => 1, 'b' => 2], ['a' => 1], "Failed asserting that an array is like an array.\nFailed asserting that an array has the key 'b'.", "Failed asserting that an array has the key 'b'."];
        yield 'array is like array (undefined keys)' => [['a' => 1, 'b' => shouldBeUndefined()], ['a' => 1]];
        yield 'array is not like array (undefined keys)' => [['a' => 1, 'b' => shouldBeUndefined()], ['a' => 1, 'b' => 2], "Failed asserting that an array is like an array.\nFailed asserting that an array does not have the key 'b'.", "Failed asserting that an array does not have the key 'b'."];
    }

    #[DataProvider('cases')]
    public function test_is_like(mixed $expected, mixed $actual, ?string $error = null, ?string $comparisonError = null): void
    {
        $constraint = new IsLike($expected);
        if (null === $error) {
            shouldNotThrow()(fn () => $constraint->evaluate($actual));
            pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
        } else {
            shouldThrow(Util::expectationFailure($error, $expected, $actual, null, null, $comparisonError))(
                fn () => $constraint->evaluate($actual)
            );
            shouldThrow(Util::expectationFailure('Custom message', $expected, $actual, null, null, $comparisonError ?? $error))(
                fn () => $constraint->evaluate($actual, 'Custom message')
            );
            pipe($constraint->evaluate($actual, '', true))->to(shouldBe(false));
        }
    }

    public function test_to_string(): void
    {
        pipe(new IsLike(1)->toString())->to(shouldBe('is like 1'));
        pipe(new IsLike(new Is(10))->toString())->to(shouldBe('is 10'));
    }
}
