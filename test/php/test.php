<?php

require(dirname(__FILE__) . '/../../src/php/Localizer.php');

$l10n = new Localizer();
// setup supported locales
$l10n->locale('en', []);
$l10n->locale('el', [
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
echo $l10n->locale() . PHP_EOL;
echo $l10n->ll('hello to you') . PHP_EOL;
echo $l10n->ll('hello to all') . PHP_EOL;
echo $l10n->cl('hello to you', 'γειά σε σένα') . PHP_EOL;
echo $l10n->cl('hello to all', 'γειά σε όλους') . PHP_EOL;
echo $l10n->l('I want to say {0}', [$l10n->l('hello to you')]) . PHP_EOL;
echo $l10n->l('I want to say {0}', [$l10n->l('hello to all')]) . PHP_EOL;
echo $l10n->ln(1, 'hello to you', 'hello to all') . PHP_EOL;
echo $l10n->ln(2, 'hello to you', 'hello to all') . PHP_EOL;
echo $l10n->l('hello to you', 'γειά σε σένα μόνο') . PHP_EOL;
echo $l10n->l(['hello to you','ctx1']) . PHP_EOL;
