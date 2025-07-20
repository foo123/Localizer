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

            if not (locale in self._locales):
                self._locales.append(locale)

            if callable(value):
                # plural form for locale as callable
                self._plurals[locale] = value

            elif isinstance(value, dict):
                # dict of translated strings
                if not (locale in self._translations): self._translations[locale] = {}
                self._translations[locale].update(value)

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

    def r(self, s, args = None):
        # replace str {arg} placeholders with args
        s = str(s)
        if isinstance(args, (list, tuple)): s = s.format(*args)
        return s

    def ll(self, s, args = None):
        # localization by translation lookup
        locale = self._currentLocale
        s = str(s)
        return self.r(self._translations[locale][s] if locale and (locale in self._translations) and (s in self._translations[locale]) else s, args)

    def cl(self, *s):
        # localization by choosing among localised strings given in same order as supported locales
        locale = self._currentLocale
        args = s.pop() if len(s) > len(self._locales) else None
        try:
            index = self._locales.index(locale)
        except ValueError:
            index = -1
        return self.r('' if -1 == index or index >= len(s) else s[index], args)

    def l(self, *args):
        # localization both by choosing and by lookup
        if 2 > len(args) or args[1] is None or isinstance(args[1], (list,tuple)):
            return self.ll(args[0], args[1] if 2 <= len(args) else None)
        else:
            return self.cl(*args)

    def cn(self, n, singular, plural):
        # choose among singular/plural  based on n
        return plural if self.isPlural(n) else singular

    def ln(self, n, singular, plural, args = None):
        # singular/plural localization based on n
        return self.l(self.cn(n, singular, plural), args)


# export it
__all__ = ['Localizer']
