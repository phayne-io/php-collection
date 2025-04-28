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
use Phayne\Collection\AbstractCollection;

/**
 * Class AbstractSet
 *
 * This class contains the basic implementation of a collection that does not
 *   allow duplicated values (a set), to minimize the effort required to implement
 *   this specific type of collection.
 *
 * @template T
 * @extends AbstractCollection<T>
 * @package Phayne\Collection
 */
abstract class AbstractSet extends AbstractCollection
{
    #[Override]
    public function add(mixed $element): bool
    {
        if ($this->contains($element)) {
            return false;
        }

        return parent::add($element);
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->contains($value)) {
            return;
        }

        parent::offsetSet($offset, $value);
    }
}
