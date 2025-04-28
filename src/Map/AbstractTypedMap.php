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

use InvalidArgumentException;
use Override;
use Phayne\Collection\Utils\TypeTrait;
use Phayne\Collection\Utils\ValueToStringTrait;

/**
 * Class AbstractTypedMap
 *
 * This class provides a basic implementation of `TypedMapInterface`, to
 *   minimize the effort required to implement this interface.
 *
 * @template K of array-key
 * @template T
 * @extends AbstractMap<K, T>
 * @implements TypedMapInterface<K, T>
 * @package Phayne\Collection\Map
 */
abstract class AbstractTypedMap extends AbstractMap implements TypedMapInterface
{
    use TypeTrait;
    use ValueToStringTrait;

    /**
     * @param K $offset
     * @param T $value
     *
     * @inheritDoc
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->checkType($this->keyType(), $offset) === false) {
            throw new InvalidArgumentException(
                'Key must be of type ' . $this->keyType() . '; key is '
                . $this->toolValueToString($offset),
            );
        }

        if ($this->checkType($this->valueType(), $value) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->valueType() . '; value is '
                . $this->toolValueToString($value),
            );
        }

        parent::offsetSet($offset, $value);
    }
}
