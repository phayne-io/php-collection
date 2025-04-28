<?php

/**
 * This file is part of phayne-io/php-collection and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-collection for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\Collection;

use InvalidArgumentException;
use Override;
use Phayne\Collection\Utils\TypeTrait;
use Phayne\Collection\Utils\ValueToStringTrait;

/**
 * Class Queue
 *
 * This class provides a basic implementation of `QueueInterface`, to minimize
 *   the effort required to implement this interface.
 *
 * @template T
 * @extends AbstractArray<T>
 * @implements QueueInterface<T>
 * @package Phayne\Collection
 */
class Queue extends AbstractArray implements QueueInterface
{
    use TypeTrait;
    use ValueToStringTrait;

    /**
     * Constructs a queue object of the specified type, optionally with the
     * specified data.
     *
     * @param string $queueType The type or class name associated with this queue.
     * @param array<array-key, T> $data The initial items to store in the queue.
     */
    public function __construct(private readonly string $queueType, array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * {@inheritDoc}
     *
     * Since arbitrary offsets may not be manipulated in a queue, this method
     * serves only to fulfill the `ArrayAccess` interface requirements. It is
     * invoked by other operations when adding values to the queue.
     *
     * @throws InvalidArgumentException if $value is of the wrong type.
     */
    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->checkType($this->type(), $value) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->type() . '; value is '
                . $this->toolValueToString($value),
            );
        }

        $this->data[] = $value;
    }

    /**
     * @throws InvalidArgumentException if $value is of the wrong type.
     */
    #[Override]
    public function add(mixed $element): bool
    {
        $this[] = $element;

        return true;
    }

    /**
     * @return T
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    #[Override]
    public function element(): mixed
    {
        return $this->peek() ?? throw new Exception\NoSuchElementException(
            'Can\'t return element from Queue. Queue is empty.',
        );
    }

    #[Override]
    public function offer(mixed $element): bool
    {
        try {
            return $this->add($element);
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * @return T | null
     */
    #[Override]
    public function peek(): mixed
    {
        $index = array_key_first($this->data);

        if ($index === null) {
            return null;
        }

        return $this[$index];
    }

    /**
     * @return T | null
     */
    #[Override]
    public function poll(): mixed
    {
        $index = array_key_first($this->data);

        if ($index === null) {
            return null;
        }

        $head = $this[$index];
        unset($this[$index]);

        return $head;
    }

    /**
     * @return T
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    #[Override]
    public function remove(): mixed
    {
        return $this->poll() ?? throw new Exception\NoSuchElementException(
            'Can\'t return element from Queue. Queue is empty.',
        );
    }

    #[Override]
    public function type(): string
    {
        return $this->queueType;
    }
}
