/**
*
* Simple class to localize texts for PHP, JavaScript, Python
* @version 0.1.0
* https://github.com/foo123/Localizer
*
*/
!function(root, name, factory) {
"use strict";
if (('object' === typeof module) && module.exports) /* CommonJS */
    (module.$deps = module.$deps||{}) && (module.exports = module.$deps[name] = factory.call(root));
else if (('function' === typeof define) && define.amd && ('function' === typeof require) && ('function' === typeof require.specified) && require.specified(name) /*&& !require.defined(name)*/) /* AMD */
    define(name, ['module'], function(module) {factory.moduleUri = module.uri; return factory.call(root);});
else if (!(name in root)) /* Browser/WebWorker/.. */
    (root[name] = factory.call(root)||1) && ('function' === typeof(define)) && define.amd && define(function() {return root[name];});
}(  /* current root */          'undefined' !== typeof self ? self : this,
    /* module name */           "Localizer",
    /* module factory */        function ModuleFactory__Localizer(undef) {
"use strict";

var HAS = Object.prototype.hasOwnProperty,
    toString = Object.prototype.toString,
    arg = /\{(\d+)\}/g;

function is_array(x)
{
    return '[object Array]' === toString.call(x);
}
function is_object(x)
{
    return '[object Object]' === toString.call(x);
}
function is_callable(x)
{
    return 'function' === typeof x;
}
function merge(a, b)
{
    for (var k in b)
    {
        if (!HAS.call(b, k)) continue;
        a[k] = b[k];
    }
    return a;
}
function vsprintf(s, args)
{
    return s.replace(arg, function(match, index) {
        return String(args[+index]);
    });
}

function Localizer()
{
    var self = this,
        _currentLocale = null,
        _translations = {},
        _plurals = {};

    self.locale = function(locale, translations) {
        if (arguments.length)
        {
            locale = String(locale);
            if (is_callable(translations))
            {
                // plural form for locale as callable
                _plurals[locale] = translations;
            }
            else if (is_object(translations))
            {
                if (!HAS.call(_translations, locale)) _translations[locale] = {};
                merge(_translations[locale], translations);
            }
            _currentLocale = locale;
            return self;
        }
        return _currentLocale;
    };

    self.l = function(s, args) {
        // localization
        var locale = _currentLocale;
        s = String(s);
        var ls = locale && HAS.call(_translations, locale) && HAS.call(_translations[locale], s) ? String(_translations[locale][s]) : s;
        if (is_array(args)) ls = vsprintf(ls, args);
        return ls;
    };

    self.isPlural = function(n) {
        // custom plural form per locale
        var locale = _currentLocale;
        var isPlural = locale && HAS.call(_plurals, locale) && is_callable(_plurals[locale]) ? !!_plurals[locale](n) : (1 != n);
        return isPlural;
    };

    self.nl = function(n, singular, plural, args) {
        // singular/plural localization based on $n
        return self.l(self.isPlural(n) ? plural : singular, args);
    };
}
Localizer.VERSION = '0.1.0';
Localizer.prototype = {
    constructor: Localizer,
    locale: null,
    l: null,
    isPlural: null,
    nl: null
};

// export it
return Localizer;
});
