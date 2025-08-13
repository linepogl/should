<?php

declare(strict_types=1);

namespace Should;

use PHPUnit\Util\Exporter;
use ReflectionClass;
use Should\Constraint\Assert;
use Throwable;

/**
 * @template E of Throwable
 * @param class-string<E>|E $expected
 * @return ShouldThrow<E>
 */
function shouldThrow(string|Throwable $expected = Throwable::class, string $message = ''): ShouldThrow
{
    return new ShouldThrow($expected, $message);
}

/**
 * @template E of Throwable
 */
class ShouldThrow
{
    /**
     * @param class-string<E>|E $expected
     */
    public function __construct(
        private readonly string|Throwable $expected = Throwable::class,
        private readonly string $message = '',
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeException(Throwable $exception): array
    {
        $r = [];
        foreach (new ReflectionClass($exception)->getProperties() as $property) {
            if ($property->isStatic() || in_array($property->getName(), ['file', 'line', 'trace', 'serializableTrace'])) {
                continue;
            }
            $value = $property->getValue($exception);
            if ($value instanceof Throwable) {
                $value = $this->normalizeException($value);
            }
            $r[$property->getName()] = $value;
        }
        ksort($r);
        return $r;
    }

    /**
     * @param callable():mixed $actual
     * @return E
     */
    public function __invoke(mixed $actual): Throwable
    {
        $class = is_string($this->expected) ? $this->expected : $this->expected::class;
        $assert = new Assert($this->message);
        try {
            $actual();
        } catch (Throwable $ex) {
            $assert->assertInstanceOf($class, $ex, "Failed asserting that the thrown exception is an instance of $class.\n" . Exporter::export($ex));
            if ($this->expected instanceof Throwable) {
                $exp = $this->normalizeException($this->expected);
                $act = $this->normalizeException($ex);
                shouldBe($exp, 'Failed asserting that the two exceptions are equal.')($act);
            }
            return $ex; // @phpstan-ignore return.type (narrowing type from Throwable to E)
        }
        $assert->fail("Expected $class to be thrown, but nothing was.");
    }
}
