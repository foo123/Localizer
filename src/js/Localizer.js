/**
*
* Simple class to localize texts for PHP, JavaScript, Python
* @version 2.0.0
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

function Localizer()
{
    if (!(this instanceof Localizer)) return new Localizer();
    var self = this,
        _currentLocale = null,
        _locales = [],
        _translations = {},
        _plurals = {};

    self.locale = function(locale, value) {
        if (arguments.length)
        {
            locale = String(locale).toLowerCase();
            if (-1 === _locales.indexOf(locale))
            {
                _locales.push(locale);
            }
            if (is_callable(value))
            {
                // plural form for locale as callable
                _plurals[locale] = value;
            }
            else if (is_object(value))
            {
                // object of translated strings
                if (!HAS.call(_translations, locale)) _translations[locale] = {};
                merge(_translations[locale], value);
            }
            else if (value === true)
            {
                // set current locale
                _currentLocale = locale;
            }
            return self;
        }
        return _currentLocale;
    };

    self.isPlural = function(n) {
        // custom plural form per locale
        var locale = _currentLocale;
        var isPlural = locale && HAS.call(_plurals, locale) && is_callable(_plurals[locale]) ? !!_plurals[locale](n) : (1 != n);
        return isPlural;
    };

    self.r = function(s, args) {
        // replace str {arg} placeholders with args
        s = String(s);
        if (is_array(args))
        {
            s = s.replace(arg, function(match, index) {
                index = parseInt(index);
                return String(HAS.call(args, index) ? args[index] : match);
            });
        }
        return s;
    };

    self.ll = function(s, args) {
        // localization by translation lookup
        var locale = _currentLocale;
        s = String(s);
        return self.r(locale && HAS.call(_translations, locale) && HAS.call(_translations[locale], s) ? _translations[locale][s] : s, args);
    };

    self.cl = function(/*..args*/) {
        // localization by choosing among localised strings given in same order as supported locales
        var locale = _currentLocale, s = arguments,
            args = s.length > _locales.length ? s[s.length-1] : null,
            index = _locales.indexOf(_currentLocale);
        return self.r(-1 === index || null == s[index] ? '' : s[index], args);
    };

    self.l = function(/*..args*/) {
        // localization both by choosing and by lookup
        if (2 > arguments.length || null == arguments[1] || is_array(arguments[1]))
        {
            return self.ll(arguments[0], arguments[1]);
        }
        else
        {
            return self.cl.apply(self, arguments);
        }
    }

    self.cn = function(n, singular, plural) {
        // choose among singular/plural  based on n
        return self.isPlural(n) ? plural : singular;
    };

    self.ln = function(n, singular, plural, args) {
        // singular/plural localization based on $n
        return self.l(self.cn(n, singular, plural), args);
    };
}
Localizer.VERSION = '2.0.0';
Localizer.prototype = {
    constructor: Localizer,
    locale: null,
    isPlural: null,
    r: null,
    ll: null,
    cl: null,
    l: null,
    cn: null,
    ln: null
};

// utils
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

// export it
return Localizer;
});
