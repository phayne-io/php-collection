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

use Override;

/**
 * Class Collection
 *
 * @template T
 * @extends AbstractCollection<T>
 * @package Phayne\Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Constructs a collection object of the specified type, optionally with the
     * specified data.
     *
     * @param string $collectionType The type or class name associated with this
     *     collection.
     * @param array<array-key, T> $data The initial items to store in the collection.
     */
    public function __construct(private readonly string $collectionType, array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function type(): string
    {
        return $this->collectionType;
    }
}
