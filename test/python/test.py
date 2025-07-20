import os, sys

DIR = os.path.dirname(os.path.abspath(__file__))

def import_module(name, path):
    import imp
    try:
        mod_fp, mod_path, mod_desc  = imp.find_module(name, [path])
        mod = getattr( imp.load_module(name, mod_fp, mod_path, mod_desc), name )
    except ImportError as exc:
        mod = None
        sys.stderr.write("Error: failed to import module ({})".format(exc))
    finally:
        if mod_fp: mod_fp.close()
    return mod

# import the Localizer.py (as a) module, probably you will want to place this in another dir/package
Localizer = import_module('Localizer', os.path.join(DIR, '../../src/python/'))
if not Localizer:
    print ('Could not load the Localizer Module')
    sys.exit(1)
else:
    pass


l10n = Localizer()

# setup supported locales
l10n.locale('en', {})
l10n.locale('el', {
    'I want to say {0}' : 'Θέλω να πώ {0}',
    'hello to you' : 'γειά σε σένα',
    'hello to all' : 'γειά σε όλους'
})
# set current locale
l10n.locale('el', True)

print('Localizer.VERSION = ' + Localizer.VERSION)
print(l10n.locale())
print(l10n.ll('hello to you'))
print(l10n.ll('hello to all'))
print(l10n.cl('hello to you', 'γειά σε σένα'))
print(l10n.cl('hello to all', 'γειά σε όλους'))
print(l10n.ll('I want to say {0}', [l10n.l('hello to you')]))
print(l10n.ll('I want to say {0}', [l10n.l('hello to all')]))
print(l10n.ln(1, 'hello to you', 'hello to all'))
print(l10n.ln(2, 'hello to you', 'hello to all'))
print(l10n.l('hello to you', 'γειά σε σένα'))
print(l10n.l('hello to you'))
