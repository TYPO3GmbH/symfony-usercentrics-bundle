<?php

declare(strict_types=1);

/*
 * This file is part of the package t3g/symfony-usercentrics.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\UsercentricsBundle\Tests\Functional\Twig;

use T3G\Bundle\UsercentricsBundle\Twig\UsercentricsExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Test\IntegrationTestCase;

class UsercentricsExtensionTest extends IntegrationTestCase
{
    /**
     * @return ExtensionInterface[]
     */
    public function getExtensions(): array
    {
        return [
            new UsercentricsExtension(['id' => 'XXXXXXXX']),
        ];
    }

    protected function getFixturesDir(): string
    {
        return __DIR__ . '/Fixtures/';
    }
}