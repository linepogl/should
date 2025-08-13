<?php

declare(strict_types=1);

namespace Should\Constraint;

use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\NativeType;
use PHPUnit\Util\Exporter;
use SebastianBergmann\Comparator\ComparisonFailure;

final class Assert
{
    public function __construct(
        public readonly string $customMessage = '',
    ) {
    }

    public function comparisonFailure(string $message, mixed $expected, mixed $actual, ?string $expectedAsString = null, ?string $actualAsString = null): ExpectationFailedException
    {
        return new ExpectationFailedException(
            '' === $this->customMessage ? $message : $this->customMessage,
            new ComparisonFailure(
                $expected,
                $actual,
                $expectedAsString ?? Exporter::export($expected),
                $actualAsString ?? Exporter::export($actual),
            ),
        );
    }

    /**
     * @param string|callable(string):string|ExpectationFailedException $message
     */
    public function assertThat(mixed $actual, Constraint $constraint, string|callable|ExpectationFailedException $message = ''): void
    {
        try {
            \PHPUnit\Framework\Assert::assertThat($actual, $constraint);
        } catch (ExpectationFailedException $ex) {
            if ($message instanceof ExpectationFailedException) {
                throw $message;
            }
            throw new ExpectationFailedException($this->processMessage($message, $ex->getMessage()), $ex->getComparisonFailure());
        }
    }

    /**
     * @param string|callable(string):string $message
     */
    private function processMessage(string|callable $message, string $errorMessage): string
    {
        if (is_string($message) && '' !== $message) {
            return $message;
        }

        $msg = '' === $this->customMessage ? $errorMessage : $this->customMessage;
        if (is_callable($message)) {
            $msg = $message($msg);
        }

        return $msg;
    }

    public function success(): void
    {
        $this->assertThat(true, new IsTrue());
    }

    public function fail(string $message = ''): never
    {
        throw new ExpectationFailedException($this->processMessage($message, $message));
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertIs(mixed $expected, mixed $actual, string|callable $message = ''): void
    {
        $this->assertThat($actual, new Is($expected), $message);
    }

    /**
     * @param class-string $expected
     * @param string|callable(string):string $message
     */
    public function assertInstanceOf(string $expected, mixed $actual, string|callable $message = ''): void
    {
        $this->assertThat($actual, new IsInstanceOf($expected), $message);
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertEquals(mixed $expected, mixed $actual, string|callable $message = ''): void
    {
        $this->assertThat($actual, new IsEqual($expected), $message);
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertSame(mixed $expected, mixed $actual, string|callable $message = ''): void
    {
        $this->assertThat($actual, new IsIdentical($expected), $message);
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertNull(mixed $actual, string|callable $message = ''): void
    {
        $this->assertThat($actual, new IsNull(), $message);
    }

    /**
     * @param string|callable(string):string $message
     */
    public function assertCount(int $expected, mixed $actual, string|callable $message = ''): void
    {
        $this->assertThat($actual, new Count($expected), $message);
    }

    /**
     * @param string|callable(string):string $message
     * @phpstan-assert array<mixed, mixed> $actual
     */
    public function assertIsArray(mixed $actual, string|callable $message = ''): void
    {
        $this->assertThat($actual, new IsType(NativeType::Array), $message);
    }

    /**
     * @param string|callable(string):string $message
     * @phpstan-assert iterable<mixed, mixed> $actual
     */
    public function assertIsIterable(mixed $actual, string|callable $message = ''): void
    {
        $this->assertThat($actual, new IsType(NativeType::Iterable), $message);
    }

    /**
     * @param array<mixed, mixed> $array
     * @param string|callable(string):string $message
     */
    public function assertArrayHasKey(mixed $key, array $array, string|callable $message = ''): void
    {
        $this->assertThat($array, new ArrayHasKey($key), $message);
    }

    /**
     * @param array<mixed, mixed> $array
     * @param string|callable(string):string $message
     */
    public function assertArrayNotHasKey(mixed $key, array $array, string|callable $message = ''): void
    {
        $this->assertThat($array, new LogicalNot(new ArrayHasKey($key)), $message);
    }
}
