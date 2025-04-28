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

/**
 * Enum Sort
 *
 * @package Phayne\Collection
 */
enum Sort: string
{
    /**
     * Sort items in a collection in ascending order.
     */
    case Ascending = 'asc';

    /**
     * Sort items in a collection in descending order.
     */
    case Descending = 'desc';
}
