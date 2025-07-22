##
#
# Simple class to localize texts for PHP, JavaScript, Python
# @version 2.0.0
# https://github.com/foo123/Localizer
#
##

class Localizer:
    VERSION = '2.0.0'

    def __init__(self):
        self._currentLocale = None
        self._locales = []
        self._translations = {}
        self._plurals = {}

    def locale(self, *args):
        l = len(args)
        if l:
            locale = str(args[0]).lower()
            value = args[1] if l > 1 else None
            replace = args[2] if l > 2 else False

            if not (locale in self._locales):
                self._locales.append(locale)

            if callable(value):
                # plural form for locale as callable
                self._plurals[locale] = value

            elif isinstance(value, dict):
                # hash of translated strings and contexts
                #
                #structure of translations hash:
                #{
                #    # default context
                #    'string1': 'translation1',
                #    'string2': 'translation2',
                #    # ..
                #    # specific contexts
                #    '@': {
                #        'ctx1': {
                #            'string1': 'translation1 for ctx1',
                #            'string2': 'translation2 for ctx1',
                #            # ..
                #        },
                #        'ctx2': {
                #            'string1': 'translation1 for ctx2',
                #            'string2': 'translation2 for ctx2',
                #           # ..
                #        },
                #       # ..
                #    }
                #}
                #
                if replace is True:
                    self._translations[locale] = value
                else:
                    if not (locale in self._translations): self._translations[locale] = {}
                    self._translations[locale] = merge(self._translations[locale], value, True)

            elif value is True:
                # set current locale
                self._currentLocale = locale

            return self

        return self._currentLocale

    def isPlural(self, n):
        # custom plural form per locale
        locale = self._currentLocale
        isPlural = bool(self._plurals[locale](n)) if locale and (locale in self._plurals) and callable(self._plurals[locale]) else (1 != n)
        return isPlural

    def cn(self, n, singular, plural):
        # choose among singular/plural  based on n
        return plural if self.isPlural(n) else singular

    def replace(self, s, args = None):
        # replace str {arg} placeholders with args
        s = str(s)
        if isinstance(args, (list,tuple)) and len(args): s = s.format(*args)
        return s

    def ll(self, s, args = None):
        # localization by translation lookup
        # context can be passed as second part of a tuple with string as first part
        locale = self._currentLocale
        context = ''
        if isinstance(s, (list,tuple)):
            if 1 < len(s): context = str(s[1])
            s = s[0]
        if '' == context: context = '*'
        s = str(s)
        if locale and (locale in self._translations):
            lookup = self._translations[locale]
            lookupctx = lookup['@'][context] if '*' != context and ('@' in lookup) and (context in lookup['@']) else lookup
            ls = lookupctx[s] if (s in lookupctx) else (lookup[s] if (s in lookup) else s)
        else:
            ls = s
        return self.replace(ls, args)

    def cl(self, *s):
        # localization by choosing among localised strings given in same order as supported locales
        # context is automatically taken care of since translations are given at the specific point
        locale = self._currentLocale
        args = s.pop() if len(s) and isinstance(s[-1], (list,tuple)) else None
        try:
            index = self._locales.index(locale)
        except ValueError:
            index = -1
        return (self.ll(s[0], args) if len(s) and (s[0] is not None) else '') if -1 == index or index >= len(s) or (s[index] is None) else self.replace(s[index], args)

    def l(self, *args):
        # localization either by choosing or by lookup
        if 2 > len(args) or isinstance(args[1], (list,tuple)):
            return self.ll(args[0], args[1] if 2 <= len(args) else None)
        else:
            return self.cl(*args)

    def ln(self, n, singular, plural, args = None):
        # singular/plural localization based on n
        return self.l(self.cn(n, singular, plural), args if isinstance(args, (list,tuple)) else tuple())


def merge(a, b, deep = False):
    for k in b.keys():
        if deep:
            if (k in a) and isinstance(a[k], dict) and isinstance(b[k], dict):
                a[k] = merge(a[k], b[k], deep)
            else:
                a[k] = b[k]
        else:
            a[k] = b[k]
    return a

# export it
__all__ = ['Localizer']
