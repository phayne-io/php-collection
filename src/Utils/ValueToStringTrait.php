<?php

/**
 * This file is part of phayne-io/php-collection and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-collection for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\Collection\Utils;

use DateTimeInterface;

use function assert;
use function get_resource_type;
use function is_array;
use function is_bool;
use function is_callable;
use function is_object;
use function is_resource;
use function is_scalar;

/**
 * Trait ValueToStringTrait
 *
 * @package Phayne\Collection\Utils
 */
trait ValueToStringTrait
{
    /**
     * Returns a string representation of the value.
     *
     * - null value: `'NULL'`
     * - boolean: `'TRUE'`, `'FALSE'`
     * - array: `'Array'`
     * - scalar: converted-value
     * - resource: `'(type resource #number)'`
     * - object with `__toString()`: result of `__toString()`
     * - object DateTime: ISO 8601 date
     * - object: `'(className Object)'`
     * - anonymous function: same as object
     *
     * @param mixed $value the value to return as a string.
     */
    protected function toolValueToString(mixed $value): string
    {
        // null
        if ($value === null) {
            return 'NULL';
        }

        // boolean constants
        if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        // array
        if (is_array($value)) {
            return 'Array';
        }

        // scalar types (integer, float, string)
        if (is_scalar($value)) {
            return (string) $value;
        }

        // resource
        if (is_resource($value)) {
            return '(' . get_resource_type($value) . ' resource #' . (int) $value . ')';
        }

        // From here, $value should be an object.
        assert(is_object($value));

        // __toString() is implemented
        if (is_callable([$value, '__toString'])) {
            return (string) $value->__toString();
        }

        // object of type \DateTime
        if ($value instanceof DateTimeInterface) {
            return $value->format('c');
        }

        // unknown type
        return '(' . $value::class . ' Object)';
    }
}
