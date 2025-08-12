<?php

declare(strict_types=1);

namespace Tests;

/**
 * @template T
 * @param T $value
 * @return Pipe<T>
 */
function pipe(mixed $value): Pipe
{
    return new Pipe($value);
}

/**
 * @internal
 * @template T
 */
class Pipe
{
    /** @param T $value */
    public function __construct(public readonly mixed $value)
    {
    }

    /**
     * @template T2
     * @param ?callable(T):T2 $callable
     * @return ($callable is null ? T : self<T2>)
     */
    public function __invoke(?callable $callable = null): mixed
    {
        return null === $callable
            ? $this->value
            : new self($callable($this->value));
    }

    /**
     * @template T2
     * @param callable(T):T2 $callable
     * @return self<T2>
     */
    public function to(callable $callable): self
    {
        return new self($callable($this->value));
    }
}
