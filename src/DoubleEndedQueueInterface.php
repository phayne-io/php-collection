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

/**
 * Interface DoubleEndedQueueInterface
 *
 * While `DoubleEndedQueueInterface` implementations are not strictly required
 *   to prohibit the insertion of `null` elements, they are strongly encouraged to
 *   do so. Users of any `DoubleEndedQueueInterface` implementations that do allow
 *   `null` elements are strongly encouraged *not* to take advantage of the
 *   ability to insert nulls. This is so because `null` is used as a special
 *   return value by various methods to indicated that the double-ended queue is
 *   empty.
 *
 * @template T
 * @extends QueueInterface<T>
 * @package Phayne\Collection
 */
interface DoubleEndedQueueInterface extends QueueInterface
{
    /**
     * Inserts the specified element at the front of this queue if it is
     * possible to do so immediately without violating capacity restrictions.
     *
     * When using a capacity-restricted double-ended queue, it is generally
     * preferable to use the `offerFirst()` method.
     *
     * @param T $element The element to add to the front of this queue.
     *
     * @return bool `true` if this queue changed as a result of the call.
     *
     * @throws InvalidArgumentException if a queue refuses to add a particular element
     *     for any reason other than that it already contains the element.
     *     Implementations should use a more-specific exception that extends
     *     `\InvalidArgumentException`.
     */
    public function addFirst(mixed $element): bool;

    /**
     * Inserts the specified element at the end of this queue if it is possible
     * to do so immediately without violating capacity restrictions.
     *
     * When using a capacity-restricted double-ended queue, it is generally
     * preferable to use the `offerLast()` method.
     *
     * This method is equivalent to `add()`.
     *
     * @param T $element The element to add to the end of this queue.
     *
     * @return bool `true` if this queue changed as a result of the call.
     *
     * @throws InvalidArgumentException if a queue refuses to add a particular element
     *     for any reason other than that it already contains the element.
     *     Implementations should use a more-specific exception that extends
     *     `\InvalidArgumentException`.
     */
    public function addLast(mixed $element): bool;

    /**
     * Inserts the specified element at the front of this queue if it is
     * possible to do so immediately without violating capacity restrictions.
     *
     * When using a capacity-restricted queue, this method is generally
     * preferable to `addFirst()`, which can fail to insert an element only by
     * throwing an exception.
     *
     * @param T $element The element to add to the front of this queue.
     *
     * @return bool `true` if the element was added to this queue, else `false`.
     */
    public function offerFirst(mixed $element): bool;

    /**
     * Inserts the specified element at the end of this queue if it is possible
     * to do so immediately without violating capacity restrictions.
     *
     * When using a capacity-restricted queue, this method is generally
     * preferable to `addLast()` which can fail to insert an element only by
     * throwing an exception.
     *
     * @param T $element The element to add to the end of this queue.
     *
     * @return bool `true` if the element was added to this queue, else `false`.
     */
    public function offerLast(mixed $element): bool;

    /**
     * Retrieves and removes the head of this queue.
     *
     * This method differs from `pollFirst()` only in that it throws an
     * exception if this queue is empty.
     *
     * @return T the first element in this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    public function removeFirst(): mixed;

    /**
     * Retrieves and removes the tail of this queue.
     *
     * This method differs from `pollLast()` only in that it throws an exception
     * if this queue is empty.
     *
     * @return T the last element in this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    public function removeLast(): mixed;

    /**
     * Retrieves and removes the head of this queue, or returns `null` if this
     * queue is empty.
     *
     * @return T | null the head of this queue, or `null` if this queue is empty.
     */
    public function pollFirst(): mixed;

    /**
     * Retrieves and removes the tail of this queue, or returns `null` if this
     * queue is empty.
     *
     * @return T | null the tail of this queue, or `null` if this queue is empty.
     */
    public function pollLast(): mixed;

    /**
     * Retrieves, but does not remove, the head of this queue.
     *
     * This method differs from `peekFirst()` only in that it throws an
     * exception if this queue is empty.
     *
     * @return T the head of this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    public function firstElement(): mixed;

    /**
     * Retrieves, but does not remove, the tail of this queue.
     *
     * This method differs from `peekLast()` only in that it throws an exception
     * if this queue is empty.
     *
     * @return T the tail of this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    public function lastElement(): mixed;

    /**
     * Retrieves, but does not remove, the head of this queue, or returns `null`
     * if this queue is empty.
     *
     * @return T | null the head of this queue, or `null` if this queue is empty.
     */
    public function peekFirst(): mixed;

    /**
     * Retrieves, but does not remove, the tail of this queue, or returns `null`
     * if this queue is empty.
     *
     * @return T | null the tail of this queue, or `null` if this queue is empty.
     */
    public function peekLast(): mixed;
}
