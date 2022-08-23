##
#
# Simple class to localize texts for PHP, JavaScript, Python
# @version 0.1.0
# https://github.com/foo123/Localizer
#
##

class Localizer:
    VERSION = '0.1.0'

    def __init__(self):
        self._currentLocale = None
        self._translations = {}
        self._plurals = {}

    def locale(self, *args):
        l = len(args)
        if l:
            locale = str(args[0])
            translations = args[1] if l > 1 else None
            if callable(translations):
                # plural form for locale as callable
                self._plurals[locale] = translations
            elif isinstance(translations, dict):
                if not (locale in self._translations): self._translations[locale] = {}
                self._translations[locale].update(translations)
            self._currentLocale = locale
            return self
        return self._currentLocale

    def l(self, s, args = None):
        # localization
        locale = self._currentLocale
        s = str(s)
        ls = str(self._translations[locale][s]) if locale and (locale in self._translations) and (s in self._translations[locale]) else s
        if isinstance(args, (list, tuple)): ls = ls.format(*args)
        return ls

    def isPlural(self, n):
        # custom plural form per locale
        locale = self._currentLocale
        isPlural = bool(self._plurals[locale](n)) if locale and (locale in self._plurals) and callable(self._plurals[locale]) else (1 != n)
        return isPlural

    def nl(self, n, singular, plural, args = None):
        # singular/plural localization based on n
        return self.l(plural if self.isPlural(n) else singular, args)

# export it
__all__ = ['Localizer']
