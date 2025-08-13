<?php

declare(strict_types=1);

namespace Should\Constraint;

use Override;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Util\Exporter;
use Should\ShouldBeUndefined;

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

    private function prependMessage(string $message): string
    {
        return ('' === $this->path ? '' : "$this->path:\n") . $message;
    }

    #[Override]
    protected function doEvaluate(mixed $actual, Assert $assert): void
    {
        if ($this->pattern instanceof Constraint) {
            $assert->assertThat($actual, $this->pattern, $this->prependMessage(...));
        } elseif (is_array($this->pattern)) {
            if (array_is_list($this->pattern)) {
                $this->doEvaluateIsLikeList($this->pattern, $actual, $assert);
            } else {
                $this->doEvaluateIsLikeArray($this->pattern, $actual, $assert);
            }
        } else {
            $assert->assertIs($actual, $this->pattern, $this->prependMessage(...));
        }
    }

    /**
     * @param list<mixed> $pattern
     */
    private function doEvaluateIsLikeList(array $pattern, mixed $actual, Assert $assert): void
    {
        $assert->assertIsIterable($actual, $this->prependMessage(...));
        $actualArray = [];
        foreach ($actual as $key => $value) {
            $assert->assertArrayNotHasKey($key, $actualArray, $this->message('Expected unique keys, but ' . Exporter::export($key) . ' was duplicated'));
            $actualArray[$key] = $value;
        }
        $realCount = 0;
        /** @var int<0,max> $index */
        foreach ($pattern as $index => $value) {
            if ($value instanceof ShouldBeUndefined) {
                $assert->assertArrayNotHasKey($index, $actualArray, $this->message('Expected that the key ' . Exporter::export($index) . ' should not be present, but it was.'));
            } else {
                $realCount++;
                $assert->assertArrayHasKey($index, $actualArray, $this->message('Expected that the key ' . Exporter::export($index) . ' should be present, but it was not.'));
                $assert->assertThat($actualArray[$index], new IsLike($value, path: $this->path . ' → ' . $index));
            }
        }
        $assert->assertCount($realCount, $actualArray, $this->message('Expected ' . $realCount . ' elements, but got ' . count($actualArray)));
    }

    /**
     * @param array<mixed> $pattern
     */
    private function doEvaluateIsLikeArray(array $pattern, mixed $actual, Assert $assert): void
    {
        $assert->assertIsIterable($actual, $this->path);
        $actualArray = [];
        foreach ($actual as $key => $value) {
            $assert->assertArrayNotHasKey($key, $actualArray, $this->path);
            $actualArray[$key] = $value;
        }
        foreach ($pattern as $key => $value) {
            if ($value instanceof ShouldBeUndefined) {
                $assert->assertArrayNotHasKey($key, $actualArray, $this->path);
            } else {
                $assert->assertArrayHasKey($key, $actualArray, $this->path);
                $assert->assertThat($actualArray[$key], new IsLike($value, path: $this->path . ' → ' . $key));
            }
        }
    }

    private function message(string $message): string
    {
        return $this->path . ":\n" . $message;
    }
}
