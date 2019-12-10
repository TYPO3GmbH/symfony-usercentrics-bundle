Usercentrics bundle
===================

This bundle provides a usercentrics.com implementation into Symfony.

Installation
------------

Install this bundle via composer: `composer req t3g/symfony-usercentrics-bundle`


Configuration
-------------

Create a file `config/packages/usercentrics.yaml` that contains the following configuration:

```yaml
usercentrics:
  id: xxxxxxxxx
```

Usage in Templates
------------------

At first, the usercentrics main library must be included. For this, the Twig function `usercentrics()` may be invoked
without any additional arguments:

```html
{{ usercentrics() }}
```

After that, additional Data Service Providers (DSP) may be included as well. See the following example for Google
Analytics:

```html
{{ usercentrics('Google Analytics', {async: true, src: 'https://www.googletagmanager.com/gtag/js?id=UA-XXXXXXXX-X'}) }}
{{ usercentrics(
    'Google Analytics',
    {},
    '
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag(\'js\', new Date());

        gtag(\'config\', \'UA-XXXXXXXX-X\');
    '
) }}
```