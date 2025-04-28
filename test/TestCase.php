<?php

/**
 * This file is part of phayne-io/php-collection and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-collection for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace PhayneTest\Collection;

use Faker\Factory;
use Faker\Generator;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class TestCase
 *
 * @package PhayneTest\Collection
 */
class TestCase extends MockeryTestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }
}
