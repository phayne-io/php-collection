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

use function array_key_last;
use function array_pop;
use function array_unshift;

/**
 * Class DoubleEndedQueue
 *
 * This class provides a basic implementation of `DoubleEndedQueueInterface`, to
 *   minimize the effort required to implement this interface.
 *
 * @template T
 * @extends Queue<T>
 * @implements DoubleEndedQueueInterface<T>
 * @package Phayne\Collection
 */
class DoubleEndedQueue extends Queue implements DoubleEndedQueueInterface
{
    /**
     * Constructs a double-ended queue (dequeue) object of the specified type,
     * optionally with the specified data.
     *
     * @param string $queueType The type or class name associated with this dequeue.
     * @param array<array-key, T> $data The initial items to store in the dequeue.
     */
    public function __construct(private readonly string $queueType, array $data = [])
    {
        parent::__construct($this->queueType, $data);
    }

    /**
     * @throws InvalidArgumentException if $element is of the wrong type
     */
    #[Override]
    public function addFirst(mixed $element): bool
    {
        if ($this->checkType($this->type(), $element) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->type() . '; value is '
                . $this->toolValueToString($element),
            );
        }

        array_unshift($this->data, $element);

        return true;
    }

    /**
     * @throws InvalidArgumentException if $element is of the wrong type
     */
    #[Override]
    public function addLast(mixed $element): bool
    {
        return $this->add($element);
    }

    #[Override]
    public function offerFirst(mixed $element): bool
    {
        try {
            return $this->addFirst($element);
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    #[Override]
    public function offerLast(mixed $element): bool
    {
        return $this->offer($element);
    }

    /**
     * @return T the first element in this queue.
     *
     * @throws Exception\NoSuchElementException if the queue is empty
     */
    #[Override]
    public function removeFirst(): mixed
    {
        return $this->remove();
    }

    /**
     * @return T the last element in this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    #[Override]
    public function removeLast(): mixed
    {
        return $this->pollLast() ?? throw new Exception\NoSuchElementException(
            'Can\'t return element from Queue. Queue is empty.',
        );
    }

    /**
     * @return T | null the head of this queue, or `null` if this queue is empty.
     */
    #[Override]
    public function pollFirst(): mixed
    {
        return $this->poll();
    }

    /**
     * @return T | null the tail of this queue, or `null` if this queue is empty.
     */
    #[Override]
    public function pollLast(): mixed
    {
        return array_pop($this->data);
    }

    /**
     * @return T the head of this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    #[Override]
    public function firstElement(): mixed
    {
        return $this->element();
    }

    /**
     * @return T the tail of this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    #[Override]
    public function lastElement(): mixed
    {
        return $this->peekLast() ?? throw new Exception\NoSuchElementException(
            'Can\'t return element from Queue. Queue is empty.',
        );
    }

    /**
     * @return T | null the head of this queue, or `null` if this queue is empty.
     */
    #[Override]
    public function peekFirst(): mixed
    {
        return $this->peek();
    }

    /**
     * @return T | null the tail of this queue, or `null` if this queue is empty.
     */
    #[Override]
    public function peekLast(): mixed
    {
        $lastIndex = array_key_last($this->data);

        if ($lastIndex === null) {
            return null;
        }

        return $this->data[$lastIndex];
    }
}
