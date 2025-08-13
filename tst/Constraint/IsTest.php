<?php

declare(strict_types=1);

namespace Tests\Constraint;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Should\Constraint\Is;
use Should\Constraint\Util;

use function ImpartialPipes\pipe;
use function Should\shouldBe;
use function Should\shouldThrow;

class IsTest extends TestCase
{
    /**
     * @return iterable<string, array{mixed, mixed}|array{mixed, mixed, ?string}>
     */
    public static function cases(): iterable
    {
        $a = ['a' => 1];
        $o = (object)$a;

        yield 'int === int' => [3, 3];
        yield 'int !== int' => [3, 4, 'Failed asserting that 4 is 3.'];
        yield 'int !== string' => [3, '3', 'Failed asserting that \'3\' is 3.'];
        yield 'int !== float' => [3, 3.0, 'Failed asserting that 3.0 is 3.'];
        yield 'int !== true' => [3, true, 'Failed asserting that true is 3.'];
        yield 'int !== false' => [3, false, 'Failed asserting that false is 3.'];
        yield 'int !== null' => [3, null, 'Failed asserting that null is 3.'];
        yield 'int !== array' => [3, $a, 'Failed asserting that some array is 3.'];
        yield 'int !== object' => [3, $o, 'Failed asserting that some stdClass is 3.'];

        yield 'string === string' => ['4', '4'];
        yield 'string !== string' => ['4', '3', 'Failed asserting that \'3\' is \'4\'.'];
        yield 'string !== int' => ['4', 4, 'Failed asserting that 4 is \'4\'.'];
        yield 'string !== true' => ['4', true, 'Failed asserting that true is \'4\'.'];
        yield 'string !== false' => ['4', false, 'Failed asserting that false is \'4\'.'];
        yield 'string !== null' => ['4', null, 'Failed asserting that null is \'4\'.'];
        yield 'string !== array' => ['4', $a, 'Failed asserting that some array is \'4\'.'];
        yield 'string !== object' => ['4', $o, 'Failed asserting that some stdClass is \'4\'.'];

        yield 'true === true' => [true, true];
        yield 'true !== false' => [true, false, 'Failed asserting that false is true.'];
        yield 'true !== int' => [true, 1, 'Failed asserting that 1 is true.'];
        yield 'true !== string' => [true, '1', 'Failed asserting that \'1\' is true.'];
        yield 'true !== null' => [true, null, 'Failed asserting that null is true.'];
        yield 'true !== array' => [true, $a, 'Failed asserting that some array is true.'];
        yield 'true !== object' => [true, $o, 'Failed asserting that some stdClass is true.'];

        yield 'false === false' => [false, false];
        yield 'false !== true' => [false, true, 'Failed asserting that true is false.'];
        yield 'false !== int' => [false, 0, 'Failed asserting that 0 is false.'];
        yield 'false !== string' => [false, '0', 'Failed asserting that \'0\' is false.'];
        yield 'false !== null' => [false, null, 'Failed asserting that null is false.'];
        yield 'false !== array' => [false, $a, 'Failed asserting that some array is false.'];
        yield 'false !== object' => [false, $o, 'Failed asserting that some stdClass is false.'];

        yield 'null === null' => [null, null];
        yield 'null !== int' => [null, 0, 'Failed asserting that 0 is null.'];
        yield 'null !== string' => [null, '0', 'Failed asserting that \'0\' is null.'];
        yield 'null !== true' => [null, true, 'Failed asserting that true is null.'];
        yield 'null !== false' => [null, false, 'Failed asserting that false is null.'];
        yield 'null !== array' => [null, $a, 'Failed asserting that some array is null.'];
        yield 'null !== object' => [null, $o, 'Failed asserting that some stdClass is null.'];

        yield 'array == array' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2]];
        yield 'array != array (order)' => [['a' => 1, 'b' => 2], ['b' => 2, 'a' => 1], 'Failed asserting that two arrays are equal.'];
        yield 'array != array (content)' => [['a' => 1, 'b' => 2], ['a' => 1, 'b' => '2'], 'Failed asserting that two arrays are equal.'];
        yield 'array == array (identical objects)' => [['a' => $o], ['a' => $o]];
        yield 'array != array (equal objects)' => [['a' => $o], ['a' => (object)['a' => 1]]];

        yield 'object == object (identical objects)' => [$o, $o];
        yield 'object == object (equal objects)' => [$o, (object)['a' => 1]];
        // todo: yield 'object != object (content)' => [$o, (object)['a'=>'1'], 'Failed asserting that two objects are equal.'];
        yield 'object != object (content2)' => [$o, (object)['a' => '4'], 'Failed asserting that two objects are equal.'];
    }

    #[DataProvider('cases')]
    public function test_is(mixed $expected, mixed $actual, ?string $error = null): void
    {
        $constraint = new Is($expected);
        if (null === $error) {
            pipe($constraint->evaluate($actual, '', true))->to(shouldBe(true));
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
}
