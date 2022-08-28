##
#
# Simple class to localize texts for PHP, JavaScript, Python
# @version 1.0.0
# https://github.com/foo123/Localizer
#
##

class Localizer:
    VERSION = '1.0.0'

    def __init__(self):
        self._currentLocale = None
        self._locales = []
        self._translations = {}
        self._plurals = {}

    def locale(self, *args):
        l = len(args)
        if l:
            locale = str(args[0])
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

    def cl(self, *args):
        # choose among given localised strings
        # based on index of current locale among supported locales
        try:
            index = self._locales.index(self._currentLocale)
        except ValueError:
            index = -1
        return args[0] if -1 == index else args[index]

    def l(self, s, args = None):
        # localization
        locale = self._currentLocale
        s = str(s)
        ls = str(self._translations[locale][s]) if locale and (locale in self._translations) and (s in self._translations[locale]) else s
        if isinstance(args, (list, tuple)): ls = ls.format(*args)
        return ls

    def cn(self, n, singular, plural):
        # choose among singular/plural  based on n
        return plural if self.isPlural(n) else singular

    def ln(self, n, singular, plural, args = None):
        # singular/plural localization based on n
        return self.l(self.cn(n, singular, plural), args)


# export it
__all__ = ['Localizer']
