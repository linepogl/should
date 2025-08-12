<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Util\Exporter;
use Should\ShouldBeUndefined;
use function Should\shouldBe;

final class IsLike extends AbstractConstraint
{
    public function __construct(
        private readonly mixed $pattern,
        private readonly string $path = '',
    ) {
    }

    #[Override]
    public function toString(): string
    {
        return 'is like ' . Exporter::export($this->pattern);
    }

    #[Override]
    protected function doEvaluate(mixed $actual): void
    {
        if ($this->pattern instanceof Constraint) {
            Assert::assertThat($actual, $this->pattern, $this->path);
        } elseif (is_array($this->pattern)) {
            if (array_is_list($this->pattern)) {
                $this->doEvaluateIsLikeList($this->pattern, $actual);
            } else {
                $this->doEvaluateIsLikeArray($this->pattern, $actual);
            }
        } else {
            Assert::assertThat($actual, shouldBe($this->pattern), $this->path);
        }
    }

    /**
     * @param list<mixed> $pattern
     */
    private function doEvaluateIsLikeList(array $pattern, mixed $actual): void
    {
        Assert::assertIsIterable($actual, $this->message('Expected iterable, got ' . get_debug_type($actual)));
        $actualArray = [];
        foreach ($actual as $key => $value) {
            Assert::assertArrayNotHasKey($key, $actualArray, $this->message('Expected unique keys, but ' . Exporter::export($key) . ' was duplicated'));
            $actualArray[$key] = $value;
        }
        $realCount = 0;
        /** @var int<0,max> $index */
        foreach ($pattern as $index => $value) {
            if ($value instanceof ShouldBeUndefined) {
                Assert::assertArrayNotHasKey($index, $actualArray, $this->message('Expected that the key ' . Exporter::export($index) . ' should not be present, but it was.'));
            } else {
                $realCount++;
                Assert::assertArrayHasKey($index, $actualArray, $this->message('Expected that the key ' . Exporter::export($index) . ' should be present, but it was not.'));
                Assert::assertThat($actualArray[$index], new IsLike($value, path: $this->path . ' → ' . $index));
            }
        }
        Assert::assertCount($realCount, $actualArray, $this->message('Expected ' . $realCount . ' elements, but got ' . count($actualArray)));
    }

    /**
     * @param array<mixed> $pattern
     */
    private function doEvaluateIsLikeArray(array $pattern, mixed $actual): void
    {
        Assert::assertIsIterable($actual, $this->path);
        $actualArray = [];
        foreach ($actual as $key => $value) {
            Assert::assertArrayNotHasKey($key, $actualArray, $this->path);
            $actualArray[$key] = $value;
        }
        foreach ($pattern as $key => $value) {
            if ($value instanceof ShouldBeUndefined) {
                Assert::assertArrayNotHasKey($key, $actualArray, $this->path);
            } else {
                Assert::assertArrayHasKey($key, $actualArray, $this->path);
                Assert::assertThat($actualArray[$key], new IsLike($value, path: $this->path . ' → ' . $key));
            }
        }
    }

    private function message(string $message): string
    {
        return $this->path . ":\n" . $message;
    }
}
