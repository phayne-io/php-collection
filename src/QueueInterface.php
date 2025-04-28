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
 * Interface QueueInterface
 *
 * @template T
 * @extends ArrayInterface<T>
 * @package Phayne\Collection
 */
interface QueueInterface extends ArrayInterface
{
    /**
     * Ensures that this queue contains the specified element (optional
     * operation).
     *
     * Returns `true` if this queue changed as a result of the call. (Returns
     * `false` if this queue does not permit duplicates and already contains the
     * specified element.)
     *
     * Queues that support this operation may place limitations on what elements
     * may be added to this queue. In particular, some queues will refuse to add
     * `null` elements, and others will impose restrictions on the type of
     * elements that may be added. Queue classes should clearly specify in their
     * documentation any restrictions on what elements may be added.
     *
     * If a queue refuses to add a particular element for any reason other than
     * that it already contains the element, it must throw an exception (rather
     * than returning `false`). This preserves the invariant that a queue always
     * contains the specified element after this call returns.
     *
     * @see self::offer()
     *
     * @param T $element The element to add to this queue.
     *
     * @return bool `true` if this queue changed as a result of the call.
     *
     * @throws InvalidArgumentException if a queue refuses to add a particular element
     *     for any reason other than that it already contains the element.
     *     Implementations should use a more-specific exception that extends
     *     `\InvalidArgumentException`.
     */
    public function add(mixed $element): bool;

    /**
     * Retrieves, but does not remove, the head of this queue.
     *
     * This method differs from `peek()` only in that it throws an exception if
     * this queue is empty.
     *
     * @see self::peek()
     *
     * @return T the head of this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    public function element(): mixed;

    /**
     * Inserts the specified element into this queue if it is possible to do so
     * immediately without violating capacity restrictions.
     *
     * When using a capacity-restricted queue, this method is generally
     * preferable to `add()`, which can fail to insert an element only by
     * throwing an exception.
     *
     * @see self::add()
     *
     * @param T $element The element to add to this queue.
     *
     * @return bool `true` if the element was added to this queue, else `false`.
     */
    public function offer(mixed $element): bool;

    /**
     * Retrieves, but does not remove, the head of this queue, or returns `null`
     * if this queue is empty.
     *
     * @see self::element()
     *
     * @return T | null the head of this queue, or `null` if this queue is empty.
     */
    public function peek(): mixed;

    /**
     * Retrieves and removes the head of this queue, or returns `null`
     * if this queue is empty.
     *
     * @see self::remove()
     *
     * @return T | null the head of this queue, or `null` if this queue is empty.
     */
    public function poll(): mixed;

    /**
     * Retrieves and removes the head of this queue.
     *
     * This method differs from `poll()` only in that it throws an exception if
     * this queue is empty.
     *
     * @see self::poll()
     *
     * @return T the head of this queue.
     *
     * @throws Exception\NoSuchElementException if this queue is empty.
     */
    public function remove(): mixed;

    /**
     * Returns the type associated with this queue.
     */
    public function type(): string;
}
