<?php

/**
 * This file is part of linkavie/php-openapi and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/linkavie/php-openapi for the canonical source repository
 * @copyright Copyright (c) 2014-2025 Linkavie. (https://linkavie.com)
 */

declare(strict_types=1);

$finder = new PhpCsFixer\Finder()
    ->in(__DIR__)
;

return new PhpCsFixer\Config()
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])->setRiskyAllowed(true)
    ->setCacheFile('build/.php-cs-fixer.cache')
    ->setFinder($finder);
