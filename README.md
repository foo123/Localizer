# Localizer

Simple, but versatile, class for localization (l10n) for PHP, JavaScript, Python

**version 2.0.0**

**Example**

```php
$l10n = new Localizer();

// setup supported locales
$l10n->locale('en', []); // en is 1st
$l10n->locale('el', [ // el is 2nd
    'I want to say {0}' => 'Θέλω να πώ {0}',
    'hello to you' => 'γειά σε σένα',
    'hello to all' => 'γειά σε όλους',
    '@' => [
        // specific context
        'ctx1' => [
            'hello to you' => 'γειά σε σένα μόνο',
        ],
    ],
]);

// set current locale
$l10n->locale('el', true);

echo 'Localizer::VERSION = ' . Localizer::VERSION . PHP_EOL;

// get current locale
echo $l10n->locale() . PHP_EOL;

// localize by lookup
echo $l10n->l('hello to you') . PHP_EOL;
echo $l10n->l('hello to all') . PHP_EOL;

// localize by choosing based on active locale
echo $l10n->l('hello to you', 'γειά σε σένα') . PHP_EOL;
echo $l10n->l('hello to all', 'γειά σε όλους') . PHP_EOL;

// localize with custom arguments
echo $l10n->l('I want to say {0}', [$l10n->l('hello to you')]) . PHP_EOL;
echo $l10n->l('I want to say {0}', [$l10n->l('hello to all')]) . PHP_EOL;

// localize singular/plural
echo $l10n->ln(1, 'hello to you', 'hello to all') . PHP_EOL;
echo $l10n->ln(2, 'hello to you', 'hello to all') . PHP_EOL;

// localize based on specific context
echo $l10n->l('hello to you', 'γειά σε σένα μόνο') . PHP_EOL;
echo $l10n->l(['hello to you','ctx1']) . PHP_EOL;
```
