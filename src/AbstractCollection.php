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

use Closure;
use InvalidArgumentException;
use Override;
use Phayne\Collection\Utils\TypeTrait;
use Phayne\Collection\Utils\ValueExtractorTrait;
use Phayne\Collection\Utils\ValueToStringTrait;

use function array_filter;
use function array_key_first;
use function array_key_last;
use function array_map;
use function array_merge;
use function array_reduce;
use function array_search;
use function array_udiff;
use function array_uintersect;
use function in_array;
use function is_int;
use function is_object;
use function spl_object_id;
use function sprintf;
use function usort;

/**
 * Class AbstractCollection
 *
 * This class provides a basic implementation of `CollectionInterface`, to
 *   minimize the effort required to implement this interface
 *
 * @template T
 * @extends AbstractArray<T>
 * @implements CollectionInterface<T>
 * @package Phayne\Collection
 */
abstract class AbstractCollection extends AbstractArray implements CollectionInterface
{
    use TypeTrait;
    use ValueToStringTrait;
    use ValueExtractorTrait;

    /**
     * @throws InvalidArgumentException if $element is of the wrong type.
     */
    #[Override]
    public function add(mixed $element): bool
    {
        $this[] = $element;

        return true;
    }

    #[Override]
    public function contains(mixed $element, bool $strict = true): bool
    {
        return in_array($element, $this->data, $strict);
    }

    /**
     * @throws InvalidArgumentException if $element is of the wrong type.
     */
    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->checkType($this->type(), $value) === false) {
            throw new InvalidArgumentException(
                'Value must be of type ' . $this->type() . '; value is '
                . $this->toolValueToString($value),
            );
        }

        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    #[Override]
    public function remove(mixed $element): bool
    {
        if (($position = array_search($element, $this->data, true)) !== false) {
            unset($this[$position]);

            return true;
        }

        return false;
    }

    /**
     * @throws Exception\InvalidPropertyOrMethod if the $propertyOrMethod does not exist
     *     on the elements in this collection.
     * @throws Exception\UnsupportedOperationException if unable to call column() on this
     *     collection.
     *
     * @inheritDoc
     */
    #[Override]
    public function column(string $propertyOrMethod): array
    {
        $temp = [];

        foreach ($this->data as $item) {
            /** @psalm-suppress MixedAssignment */
            $temp[] = $this->extractValue($item, $propertyOrMethod);
        }

        return $temp;
    }

    /**
     * @return T
     *
     * @throws Exception\NoSuchElementException if this collection is empty.
     */
    #[Override]
    public function first(): mixed
    {
        $firstIndex = array_key_first($this->data);

        if ($firstIndex === null) {
            throw new Exception\NoSuchElementException('Can\'t determine first item. Collection is empty');
        }

        return $this->data[$firstIndex];
    }

    /**
     * @return T
     *
     * @throws Exception\NoSuchElementException if this collection is empty.
     */
    #[Override]
    public function last(): mixed
    {
        $lastIndex = array_key_last($this->data);

        if ($lastIndex === null) {
            throw new Exception\NoSuchElementException('Can\'t determine last item. Collection is empty');
        }

        return $this->data[$lastIndex];
    }

    /**
     * @return CollectionInterface<T>
     *
     * @throws Exception\InvalidPropertyOrMethod if the $propertyOrMethod does not exist
     *     on the elements in this collection.
     * @throws Exception\UnsupportedOperationException if unable to call sort() on this
     *     collection.
     */
    #[Override]
    public function sort(?string $propertyOrMethod = null, Sort $order = Sort::Ascending): CollectionInterface
    {
        $collection = clone $this;

        usort(
            $collection->data,
            /**
             * @param T $a
             * @param T $b
             */
            function (mixed $a, mixed $b) use ($propertyOrMethod, $order): int {
                $aValue = $this->extractValue($a, $propertyOrMethod);
                $bValue = $this->extractValue($b, $propertyOrMethod);

                return ($aValue <=> $bValue) * ($order === Sort::Descending ? -1 : 1);
            },
        );

        return $collection;
    }

    /**
     * @param callable(T): bool $callback A callable to use for filtering elements.
     *
     * @return CollectionInterface<T>
     */
    #[Override]
    public function filter(callable $callback): CollectionInterface
    {
        $collection = clone $this;
        $collection->data = array_merge([], array_filter($collection->data, $callback));

        return $collection;
    }

    /**
     * @return CollectionInterface<T>
     *
     * @throws Exception\InvalidPropertyOrMethod if the $propertyOrMethod does not exist
     *     on the elements in this collection.
     * @throws Exception\UnsupportedOperationException if unable to call where() on this
     *     collection.
     */
    #[Override]
    public function where(?string $propertyOrMethod, mixed $value): CollectionInterface
    {
        return $this->filter(
        /**
         * @param T $item
         */
            function (mixed $item) use ($propertyOrMethod, $value): bool {
                $accessorValue = $this->extractValue($item, $propertyOrMethod);

                return $accessorValue === $value;
            },
        );
    }

    /**
     * @param callable(T): TCallbackReturn $callback A callable to apply to each
     *     item of the collection.
     *
     * @return CollectionInterface<TCallbackReturn>
     *
     * @template TCallbackReturn
     */
    #[Override]
    public function map(callable $callback): CollectionInterface
    {
        /** @var Collection<TCallbackReturn> */
        return new Collection('mixed', array_map($callback, $this->data));
    }

    /**
     * @param callable(TCarry, T): TCarry $callback A callable to apply to each
     *     item of the collection to reduce it to a single value.
     * @param TCarry $initial This is the initial value provided to the callback.
     *
     * @return TCarry
     *
     * @template TCarry
     */
    #[Override]
    public function reduce(callable $callback, mixed $initial): mixed
    {
        /** @var TCarry */
        return array_reduce($this->data, $callback, $initial);
    }

    /**
     * @param CollectionInterface<T> $other The collection to check for divergent
     *     items.
     *
     * @return CollectionInterface<T>
     *
     * @throws Exception\CollectionMismatchException if the compared collections are of
     *     differing types.
     */
    #[Override]
    public function diff(CollectionInterface $other): CollectionInterface
    {
        $this->compareCollectionTypes($other);

        $diffAtoB = array_udiff($this->data, $other->toArray(), $this->getComparator());
        $diffBtoA = array_udiff($other->toArray(), $this->data, $this->getComparator());

        /** @var array<array-key, T> $diff */
        $diff = array_merge($diffAtoB, $diffBtoA);

        $collection = clone $this;
        $collection->data = $diff;

        return $collection;
    }

    /**
     * @param CollectionInterface<T> $other The collection to check for
     *     intersecting items.
     *
     * @return CollectionInterface<T>
     *
     * @throws Exception\CollectionMismatchException if the compared collections are of
     *     differing types.
     */
    #[Override]
    public function intersect(CollectionInterface $other): CollectionInterface
    {
        $this->compareCollectionTypes($other);

        /** @var array<array-key, T> $intersect */
        $intersect = array_uintersect($this->data, $other->toArray(), $this->getComparator());

        $collection = clone $this;
        $collection->data = $intersect;

        return $collection;
    }

    /**
     * @param CollectionInterface<T> ...$collections The collections to merge.
     *
     * @return CollectionInterface<T>
     *
     * @throws Exception\CollectionMismatchException if unable to merge any of the given
     *     collections or items within the given collections due to type
     *     mismatch errors.
     */
    #[Override]
    public function merge(CollectionInterface ...$collections): CollectionInterface
    {
        $mergedCollection = clone $this;

        foreach ($collections as $index => $collection) {
            if (!$collection instanceof static) {
                throw new Exception\CollectionMismatchException(
                    sprintf('Collection with index %d must be of type %s', $index, static::class),
                );
            }

            // When using generics (Collection.php, Set.php, etc),
            // we also need to make sure that the internal types match each other
            if ($this->getUniformType($collection) !== $this->getUniformType($this)) {
                throw new Exception\CollectionMismatchException(
                    sprintf(
                        'Collection items in collection with index %d must be of type %s',
                        $index,
                        $this->type(),
                    ),
                );
            }

            foreach ($collection as $key => $value) {
                if (is_int($key)) {
                    $mergedCollection[] = $value;
                } else {
                    $mergedCollection[$key] = $value;
                }
            }
        }

        return $mergedCollection;
    }

    #[Override]
    public function limit(int $offset = 0, ?int $limit = null): CollectionInterface
    {
        $data = $this->data;

        if ($offset > count($data)) {
            $offset = count($data);
        }

        if ($limit && $limit > count($data)) {
            $limit = count($data);
        }

        $collection = clone $this;
        $collection->data = array_slice($data, $offset, $limit);

        return $collection;
    }

    /**
     * @param CollectionInterface<T> $other
     *
     * @throws Exception\CollectionMismatchException
     */
    private function compareCollectionTypes(CollectionInterface $other): void
    {
        if (! $other instanceof static) {
            throw new Exception\CollectionMismatchException('Collection must be of type ' . static::class);
        }

        // When using generics (Collection.php, Set.php, etc),
        // we also need to make sure that the internal types match each other
        if ($this->getUniformType($other) !== $this->getUniformType($this)) {
            throw new Exception\CollectionMismatchException('Collection items must be of type ' . $this->type());
        }
    }

    private function getComparator(): Closure
    {
        return /**
         * @param T $a
         * @param T $b
         */
            function (mixed $a, mixed $b): int {
                // If the two values are object, we convert them to unique scalars.
                // If the collection contains mixed values (unlikely) where some are objects
                // and some are not, we leave them as they are.
                // The comparator should still work and the result of $a < $b should
                // be consistent but unpredictable since not documented.
                if (is_object($a) && is_object($b)) {
                    $a = spl_object_id($a);
                    $b = spl_object_id($b);
                }

                return $a === $b ? 0 : ($a < $b ? 1 : -1);
            };
    }

    /**
     * @param CollectionInterface<mixed> $collection
     */
    private function getUniformType(CollectionInterface $collection): string
    {
        return match ($collection->type()) {
            'integer' => 'int',
            'boolean' => 'bool',
            'double' => 'float',
            default => $collection->type(),
        };
    }
}
