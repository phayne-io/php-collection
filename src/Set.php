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
 * Class Set
 *
 * @template T
 * @extends AbstractSet<T>
 * @package Phayne\Collection
 */
class Set extends AbstractSet
{
    /**
     * Constructs a set object of the specified type, optionally with the
     * specified data.
     *
     * @param string $setType The type or class name associated with this set.
     * @param array<array-key, T> $data The initial items to store in the set.
     */
    public function __construct(private readonly string $setType, array $data = [])
    {
        parent::__construct($data);
    }

    #[Override]
    public function type(): string
    {
        return $this->setType;
    }
}
