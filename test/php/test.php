<?php

require(dirname(__FILE__) . '/../../src/php/Localizer.php');

$l10n = new Localizer();
$l10n->locale('el', [
    'I want to say %1$s' => 'Θέλω να πώ %1$s',
    'hello to you' => 'γειά σε σένα',
    'hello to all' => 'γειά σε όλους'
]);

echo 'Localizer::VERSION = ' . Localizer::VERSION . PHP_EOL;
echo $l10n->locale() . PHP_EOL;
echo $l10n->l('hello to you') . PHP_EOL;
echo $l10n->l('hello to all') . PHP_EOL;
echo $l10n->l('I want to say %1$s', [$l10n->l('hello to you')]) . PHP_EOL;
echo $l10n->l('I want to say %1$s', [$l10n->l('hello to all')]) . PHP_EOL;
echo $l10n->nl(1, 'hello to you', 'hello to all') . PHP_EOL;
echo $l10n->nl(2, 'hello to you', 'hello to all') . PHP_EOL;
