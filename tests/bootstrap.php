<?php declare(strict_types=1);

/*
 * This file is part of the package t3g/symfony-usercentrics-bundle.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

if (!($loader = @include __DIR__ . '/../vendor/autoload.php')) {
    echo <<<'EOT'
You need to install the project dependencies using Composer:
$ wget http://getcomposer.org/composer.phar
OR
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install --dev
$ phpunit
EOT;
    exit(1);
}
