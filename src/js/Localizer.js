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

    self.locale = function(locale, value, replace) {
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
                // hash of translated strings and contexts
                /*
                structure of translations hash:
                {
                    // default context
                    'string1': 'translation1',
                    'string2': 'translation2',
                    // ..
                    // specific contexts
                    '@': {
                        'ctx1': {
                            'string1': 'translation1 for ctx1',
                            'string2': 'translation2 for ctx1',
                            // ..
                        },
                        'ctx2': {
                            'string1': 'translation1 for ctx2',
                            'string2': 'translation2 for ctx2',
                            // ..
                        },
                        // ..
                    }
                }
                */
                if (true === replace)
                {
                    _translations[locale] = value;
                }
                else
                {
                    if (!HAS.call(_translations, locale)) _translations[locale] = {};
                    _translations[locale] = merge(_translations[locale], value, true);
                }
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

    self.cn = function(n, singular, plural) {
        // choose among singular/plural  based on n
        return self.isPlural(n) ? plural : singular;
    };

    self.replace = function(s, args) {
        // replace str {arg} placeholders with args
        s = String(s);
        if (is_array(args) && args.length)
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
        // context can be passed as second part of a tuple with string as first part
        var locale = _currentLocale, context = '', lookup, lookupctx, ls;
        if (is_array(s))
        {
            if (1 < s.length) context = String(s[1]);
            s = s[0];
        }
        if ('' === context) context = '*';
        s = String(s);
        if (locale && HAS.call(_translations, locale))
        {
            lookup = _translations[locale];
            lookupctx = '*' !== context && HAS.call(lookup, '@') && HAS.call(lookup['@'], context) ? lookup['@'][context] : lookup;
            ls = HAS.call(lookupctx, s) ? lookupctx[s] : (HAS.call(lookup, s) ? lookup[s] : s);
        }
        else
        {
            ls = s;
        }
        return self.replace(ls, args);
    };

    self.cl = function(/*..args*/) {
        // localization by choosing among localised strings given in same order as supported locales
        // context is automatically taken care of since translations are given at the specific point
        var locale = _currentLocale, s = [].slice.call(arguments),
            args = s.length && is_array(s[s.length-1]) ? s.pop() : null,
            index = _locales.indexOf(_currentLocale);
        return -1 === index || null == s[index] ? (null != s[0] ? self.ll(s[0], args) : '') : self.replace(s[index], args);
    };

    self.l = function(/*..args*/) {
        // localization either by choosing or by lookup
        if (2 > arguments.length || is_array(arguments[1]))
        {
            return self.ll(arguments[0], arguments[1]);
        }
        else
        {
            return self.cl.apply(self, arguments);
        }
    }

    self.ln = function(n, singular, plural, args) {
        // singular/plural localization based on n
        return self.l(self.cn(n, singular, plural), is_array(args) ? args : []);
    };
}
Localizer.VERSION = '2.0.0';
Localizer.prototype = {
    constructor: Localizer,
    locale: null,
    isPlural: null,
    cn: null,
    replace: null,
    ll: null,
    cl: null,
    l: null,
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
function merge(a, b, deep)
{
    for (var k in b)
    {
        if (!HAS.call(b, k)) continue;
        if (deep)
        {
            if (is_object(a[k]) && is_object(b[k]))
            {
                a[k] = merge(a[k], b[k], deep);
            }
            else
            {
                a[k] = b[k];
            }
        }
        else
        {
            a[k] = b[k];
        }
    }
    return a;
}

// export it
return Localizer;
});
