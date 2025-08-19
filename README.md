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

**see also:**

* [ModelView](https://github.com/foo123/modelview.js) a simple, fast, powerful and flexible MVVM framework for JavaScript
* [tico](https://github.com/foo123/tico) a tiny, super-simple MVC framework for PHP
* [LoginManager](https://github.com/foo123/LoginManager) a simple, barebones agnostic login manager for PHP, JavaScript, Python
* [SimpleCaptcha](https://github.com/foo123/simple-captcha) a simple, image-based, mathematical captcha with increasing levels of difficulty for PHP, JavaScript, Python
* [Dromeo](https://github.com/foo123/Dromeo) a flexible, and powerful agnostic router for PHP, JavaScript, Python
* [PublishSubscribe](https://github.com/foo123/PublishSubscribe) a simple and flexible publish-subscribe pattern implementation for PHP, JavaScript, Python
* [Localizer](https://github.com/foo123/Localizer) a simple and versatile localization class (l10n) for PHP, JavaScript, Python
* [Importer](https://github.com/foo123/Importer) simple class &amp; dependency manager and loader for PHP, JavaScript, Python
* [Contemplate](https://github.com/foo123/Contemplate) a fast and versatile isomorphic template engine for PHP, JavaScript, Python
* [HtmlWidget](https://github.com/foo123/HtmlWidget) html widgets, made as simple as possible, both client and server, both desktop and mobile, can be used as (template) plugins and/or standalone for PHP, JavaScript, Python (can be used as [plugins for Contemplate](https://github.com/foo123/Contemplate/blob/master/src/js/plugins/plugins.txt))
* [Paginator](https://github.com/foo123/Paginator)  simple and flexible pagination controls generator for PHP, JavaScript, Python
* [Formal](https://github.com/foo123/Formal) a simple and versatile (Form) Data validation framework based on Rules for PHP, JavaScript, Python
* [Dialect](https://github.com/foo123/Dialect) a cross-vendor &amp; cross-platform SQL Query Builder, based on [GrammarTemplate](https://github.com/foo123/GrammarTemplate), for PHP, JavaScript, Python
* [DialectORM](https://github.com/foo123/DialectORM) an Object-Relational-Mapper (ORM) and Object-Document-Mapper (ODM), based on [Dialect](https://github.com/foo123/Dialect), for PHP, JavaScript, Python
* [Unicache](https://github.com/foo123/Unicache) a simple and flexible agnostic caching framework, supporting various platforms, for PHP, JavaScript, Python
* [Xpresion](https://github.com/foo123/Xpresion) a simple and flexible eXpression parser engine (with custom functions and variables support), based on [GrammarTemplate](https://github.com/foo123/GrammarTemplate), for PHP, JavaScript, Python
* [Regex Analyzer/Composer](https://github.com/foo123/RegexAnalyzer) Regular Expression Analyzer and Composer for PHP, JavaScript, Python
