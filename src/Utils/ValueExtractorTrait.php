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

use Phayne\Collection\Exception\InvalidPropertyOrMethod;
use Phayne\Collection\Exception\UnsupportedOperationException;

use function is_array;
use function is_object;
use function method_exists;
use function property_exists;
use function sprintf;

/**
 * Trait ValueExtractorTrait
 *
 * @package Phayne\Collection\Utils
 */
trait ValueExtractorTrait
{
    protected function extractValue(mixed $element, ?string $propertyOrMethod): mixed
    {
        if ($propertyOrMethod === null) {
            return $element;
        }

        if (! is_object($element) && ! is_array($element)) {
            throw new UnsupportedOperationException(sprintf(
                'The collection type "%s" does not support the $propertyOrMethod parameter',
                $this->type(),
            ));
        }

        if (is_array($element)) {
            return $element[$propertyOrMethod] ?? throw new InvalidPropertyOrMethod(sprintf(
                'Key or index "%s" not found in collection elements',
                $propertyOrMethod,
            ));
        }

        if (property_exists($element, $propertyOrMethod)) {
            return $element->$propertyOrMethod;
        }

        if (method_exists($element, $propertyOrMethod)) {
            return $element->{$propertyOrMethod}();
        }

        throw new InvalidPropertyOrMethod(sprintf(
            'Method or property "%s" not defined in %s',
            $propertyOrMethod,
            $element::class,
        ));
    }
}
