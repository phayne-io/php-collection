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

use function array_combine;
use function array_key_exists;
use function is_int;

/**
 * Class NamedParameterMap
 *
 * `NamedParameterMap` represents a mapping of values to a set of named keys
 *   that may optionally be typed
 *
 * @extends AbstractMap<string, mixed>
 * @package Phayne\Collection\Map
 */
class NamedParameterMap extends AbstractMap
{
    use TypeTrait;
    use ValueToStringTrait;

    /**
     * Named parameters defined for this map.
     *
     * @var array<string, string>
     */
    private readonly array $namedParameters;

    /**
     * Constructs a new `NamedParameterMap`.
     *
     * @param array<array-key, string> $namedParameters The named parameters defined for this map.
     * @param array<string, mixed> $data An initial set of data to set on this map.
     */
    public function __construct(array $namedParameters, array $data = [])
    {
        $this->namedParameters = $this->filterNamedParameters($namedParameters);
        parent::__construct($data);
    }

    /**
     * Returns named parameters set for this `NamedParameterMap`.
     *
     * @return array<string, string>
     */
    public function getNamedParameters(): array
    {
        return $this->namedParameters;
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (! array_key_exists($offset, $this->namedParameters)) {
            throw new InvalidArgumentException(
                'Attempting to set value for unconfigured parameter \''
                . $this->toolValueToString($offset) . '\'',
            );
        }

        if ($this->checkType($this->namedParameters[$offset], $value) === false) {
            throw new InvalidArgumentException(
                'Value for \'' . $offset . '\' must be of type '
                . $this->namedParameters[$offset] . '; value is '
                . $this->toolValueToString($value),
            );
        }

        $this->data[$offset] = $value;
    }

    /**
     * Given an array of named parameters, constructs a proper mapping of
     * named parameters to types.
     *
     * @param array<array-key, string> $namedParameters The named parameters to filter.
     *
     * @return array<string, string>
     */
    protected function filterNamedParameters(array $namedParameters): array
    {
        $names = [];
        $types = [];

        foreach ($namedParameters as $key => $value) {
            if (is_int($key)) {
                $names[] = $value;
                $types[] = 'mixed';
            } else {
                $names[] = $key;
                $types[] = $value;
            }
        }

        return array_combine($names, $types) ?: [];
    }
}
