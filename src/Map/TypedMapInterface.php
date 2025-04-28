<?php

/**
 * This file is part of phayne-io/php-collection and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-collection for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\Collection\Map;

/**
 * Interface TypedMapInterface
 *
 * A `TypedMapInterface` represents a map of elements where key and value are
 *   typed.
 *
 * @template K of array-key
 * @template T
 * @extends MapInterface<K, T>
 * @package Phayne\Collection\Map
 */
interface TypedMapInterface extends MapInterface
{
    /**
     * Return the type used on the key.
     */
    public function keyType(): string;

    /**
     * Return the type forced on the values.
     */
    public function valueType(): string;
}
