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

use Exception;
use InvalidArgumentException;
use Override;
use Phayne\Collection\AbstractArray;
use BackedEnum;
use Traversable;

use function array_key_exists;
use function array_keys;
use function in_array;
use function var_export;

/**
 * Class AbstractMap
 *
 * This class provides a basic implementation of `MapInterface`, to minimize the
 *   effort required to implement this interface.
 *
 * @template K of array-key
 * @template T
 * @extends AbstractArray<T>
 * @implements MapInterface<K, T>
 * @package Phayne\Collection\Map
 */
abstract class AbstractMap extends AbstractArray implements MapInterface
{
    /**
     * @param array<K, T> $data The initial items to add to this map.
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @return Traversable<K, T>
     * @throws Exception
     */
    #[Override]
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }

    /**
     * @param K $offset The offset to set
     * @param T $value The value to set at the given offset.
     *
     * @inheritDoc
     * @psalm-suppress MoreSpecificImplementedParamType,DocblockTypeContradiction
     */
    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            throw new InvalidArgumentException(
                'Map elements are key/value pairs; a key must be provided for '
                . 'value ' . var_export($value, true),
            );
        }

        if ($offset instanceof BackedEnum) {
            $offset = $offset->name;
        }

        $this->data[$offset] = $value;
    }

    #[Override]
    public function containsKey(int | string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    #[Override]
    public function containsValue(mixed $value): bool
    {
        return in_array($value, $this->data, true);
    }

    #[Override]
    public function keys(): array
    {
        return array_keys($this->data);
    }

    /**
     * @param K $key The key to return from the map.
     * @param T | null $defaultValue The default value to use if `$key` is not found.
     *
     * @return T | null the value or `null` if the key could not be found.
     */
    #[Override]
    public function get(int | string $key, mixed $defaultValue = null): mixed
    {
        return $this[$key] ?? $defaultValue;
    }

    /**
     * @param K $key The key to put or replace in the map.
     * @param T $value The value to store at `$key`.
     *
     * @return T | null the previous value associated with key, or `null` if
     *     there was no mapping for `$key`.
     */
    #[Override]
    public function put(int | string $key, mixed $value): mixed
    {
        $previousValue = $this->get($key);
        $this[$key] = $value;

        return $previousValue;
    }

    /**
     * @param K $key The key to put in the map.
     * @param T $value The value to store at `$key`.
     *
     * @return T | null the previous value associated with key, or `null` if
     *     there was no mapping for `$key`.
     */
    #[Override]
    public function putIfAbsent(int | string $key, mixed $value): mixed
    {
        $currentValue = $this->get($key);

        if ($currentValue === null) {
            $this[$key] = $value;
        }

        return $currentValue;
    }

    /**
     * @param K $key The key to remove from the map.
     *
     * @return T | null the previous value associated with key, or `null` if
     *     there was no mapping for `$key`.
     */
    #[Override]
    public function remove(int | string $key): mixed
    {
        $previousValue = $this->get($key);
        unset($this[$key]);

        return $previousValue;
    }

    #[Override]
    public function removeIf(int | string $key, mixed $value): bool
    {
        if ($this->get($key) === $value) {
            unset($this[$key]);

            return true;
        }

        return false;
    }

    /**
     * @param K $key The key to replace.
     * @param T $value The value to set at `$key`.
     *
     * @return T | null the previous value associated with key, or `null` if
     *     there was no mapping for `$key`.
     */
    #[Override]
    public function replace(int | string $key, mixed $value): mixed
    {
        $currentValue = $this->get($key);

        if ($this->containsKey($key)) {
            $this[$key] = $value;
        }

        return $currentValue;
    }

    #[Override]
    public function replaceIf(int | string $key, mixed $oldValue, mixed $newValue): bool
    {
        if ($this->get($key) === $oldValue) {
            $this[$key] = $newValue;

            return true;
        }

        return false;
    }

    /**
     * @return array<K, T>
     */
    #[Override]
    public function __serialize(): array
    {
        return parent::__serialize();
    }

    /**
     * @return array<K, T>
     */
    #[Override]
    public function toArray(): array
    {
        return parent::toArray();
    }
}
