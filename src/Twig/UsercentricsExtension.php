<?php

declare(strict_types=1);

/*
 * This file is part of the package t3g/symfony-usercentrics-bundle.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\Bundle\UsercentricsBundle\Twig;

use T3G\Bundle\UsercentricsBundle\Exception\InvalidKeyFormatException;
use T3G\Bundle\UsercentricsBundle\Exception\NonAmbiguousSourceException;
use T3G\Bundle\UsercentricsBundle\Exception\UndefinedKeyException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UsercentricsExtension extends AbstractExtension
{
    private const IDENTIFIER = 'usercentrics';

    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return static::IDENTIFIER;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(static::IDENTIFIER, [$this, 'generateScriptTag'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Generates a <script> tag usable for usercentrics.
     *
     * @param string $dps                  The exact Data Processing Service as configured in usercentrics
     * @param array  $additionalAttributes Attributes used for the <script> tag, e.g. src, async, defer
     * @param string $inlineCode           Code executed on load
     *
     * @return string
     *
     * @throws NonAmbiguousSourceException
     * @throws \InvalidArgumentException
     */
    public function generateScriptTag(string $dps = '', array $additionalAttributes = [], string $inlineCode = ''): string
    {
        if ('' === $dps) {
            $characterPattern = '[a-z0-9]+';
            $id = (string) ($this->config['id'] ?? '');
            if ('' === $id) {
                throw new UndefinedKeyException('It seems no usercentric key has been configured.');
            }

            if (!preg_match('/^' . $characterPattern . '$/i', $id)) {
                throw new InvalidKeyFormatException(sprintf('The configured key %s does not match the expected format %s', $id, $characterPattern));
            }

            return sprintf('<script type="application/javascript" src="https://app.usercentrics.eu/latest/main.js" id="%s"></script>', htmlspecialchars((string) $this->config['id']));
        }

        if (!empty($additionalAttributes['src']) && !empty($inlineCode)) {
            throw new NonAmbiguousSourceException('$attributes[\'src\'] and $inlineCode were both set, use only one of both.');
        }

        if (empty($additionalAttributes['src']) && empty($inlineCode)) {
            throw new \InvalidArgumentException('No JavaScript code provided. Either include JavaScript via $attributes[\'src\'] or $inlineCode.');
        }

        $hardcodedAttributes = [];
        $hardcodedAttributes['type'] = 'text/plain';
        $hardcodedAttributes['data-usercentrics'] = $dps;

        // Remove hardcoded attributes from additional attributes and merge both afterwards
        $attributes = array_merge(
            $hardcodedAttributes,
            array_diff_key($additionalAttributes, $hardcodedAttributes)
        );

        return sprintf('<script%s>%s</script>', $this->generateAttributeString($attributes), $inlineCode);
    }

    private function generateAttributeString(array $attributes): string
    {
        $attributeString = '';
        foreach ($attributes as $attributeKey => $attributeValue) {
            $attributeString .= ' ';
            if (true === $attributeValue) {
                // Handle attribute as HTML5 attribute
                $attributeString .= htmlspecialchars($attributeKey);
            } else {
                $attributeString .= htmlspecialchars($attributeKey) . '="' . htmlspecialchars($attributeValue) . '"';
            }
        }

        return $attributeString;
    }
}
