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

use Override;

/**
 * Class TypedMap
 *
 * @template K of array-key
 * @template T
 * @extends AbstractTypedMap<K, T>
 * @package Phayne\Collection\Map
 */
class TypedMap extends AbstractTypedMap
{
    /**
     * Constructs a map object of the specified key and value types,
     * optionally with the specified data.
     *
     * @param string $keyType The data type of the map's keys.
     * @param string $valueType The data type of the map's values.
     * @param array<K, T> $data The initial data to set for this map.
     */
    public function __construct(
        private readonly string $keyType,
        private readonly string $valueType,
        array $data = [],
    ) {
        parent::__construct($data);
    }

    #[Override]
    public function keyType(): string
    {
        return $this->keyType;
    }

    #[Override]
    public function valueType(): string
    {
        return $this->valueType;
    }
}
