"use strict";

const Localizer = require('../../src/js/Localizer.js');

const l10n = new Localizer();

// setup supported locales
l10n.locale('en', {});
l10n.locale('el', {
    'I want to say {0}' : 'Θέλω να πώ {0}',
    'hello to you' : 'γειά σε σένα',
    'hello to all' : 'γειά σε όλους'
});
// set current locale
l10n.locale('el', true);

console.log('Localizer.VERSION = ' + Localizer.VERSION);
console.log(l10n.locale());
console.log(l10n.cl('hello to you', 'γειά σε σένα'));
console.log(l10n.cl('hello to all', 'γειά σε όλους'));
console.log(l10n.l('hello to you'));
console.log(l10n.l('hello to all'));
console.log(l10n.l('I want to say {0}', [l10n.l('hello to you')]));
console.log(l10n.l('I want to say {0}', [l10n.l('hello to all')]));
console.log(l10n.ln(1, 'hello to you', 'hello to all'));
console.log(l10n.ln(2, 'hello to you', 'hello to all'));
