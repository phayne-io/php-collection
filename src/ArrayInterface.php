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

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface ArrayInterface
 *
 * `ArrayInterface` provides traversable array functionality to data types.
 *
 * @template T
 * @extends ArrayAccess<array-key, T>
 * @extends IteratorAggregate<array-key, T>
 * @package Phayne\Collection
 */
interface ArrayInterface extends Countable, IteratorAggregate, ArrayAccess
{
    /**
     * Removes all items from this array.
     */
    public function clear(): void;

    /**
     * Returns a native PHP array representation of this array object.
     *
     * @return array<array-key, T>
     */
    public function toArray(): array;

    /**
     * Returns `true` if this array is empty.
     */
    public function isEmpty(): bool;
}
