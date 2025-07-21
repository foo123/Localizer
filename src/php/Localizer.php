<?php
/**
*
* Simple class to localize texts for PHP, JavaScript, Python
* @version 2.0.0
* https://github.com/foo123/Localizer
*
*/

if (!class_exists('Localizer', false))
{
class Localizer
{
    const VERSION = '2.0.0';

    private $_currentLocale = null;
    private $_locales = null;
    private $_translations = null;
    private $_plurals = null;

    public static $arg = '#\\{(\\d+)\\}#';

    public function __construct()
    {
        $this->_currentLocale = null;
        $this->_locales = array();
        $this->_translations = array();
        $this->_plurals = array();
    }

    public function locale($locale = null, $value = null, $replace = false)
    {
        if (func_num_args())
        {
            $locale = strtolower((string)$locale);
            if (!in_array($locale, $this->_locales))
            {
                $this->_locales[] = $locale;
            }
            if (is_callable($value))
            {
                // plural form for locale as callable
                $this->_plurals[$locale] = $value;
            }
            elseif (is_array($value))
            {
                // hash of translated strings and contexts
                /*
                structure of translations hash:
                [
                    // default context
                    'string1'=> 'translation1',
                    'string2'=> 'translation2',
                    // ..
                    // specific contexts
                    '@'=> [
                        'ctx1'=> [
                            'string1'=> 'translation1 for ctx1',
                            'string2'=> 'translation2 for ctx1',
                            // ..
                        ],
                        'ctx2'=> [
                            'string1'=> 'translation1 for ctx2',
                            'string2'=> 'translation2 for ctx2',
                            // ..
                        ],
                        // ..
                    ]
                ]
                */
                if (true === $replace)
                {
                    $this->_translations[$locale] = $value;
                }
                else
                {
                    if (!isset($this->_translations[$locale])) $this->_translations[$locale] = array();
                    $this->_translations[$locale] = $this->merge($this->_translations[$locale], $value, true);
                }
            }
            elseif ($value === true)
            {
                // set current locale
                $this->_currentLocale = $locale;
            }
            return $this;
        }
        return $this->_currentLocale;
    }

    public function isPlural($n)
    {
        // custom plural form per locale
        $locale = $this->_currentLocale;
        $isPlural = $locale && isset($this->_plurals[$locale]) && is_callable($this->plurals[$locale]) ? (bool)call_user_func($this->_plurals[$locale], $n) : (1 != $n);
        return $isPlural;
    }

    public function cn($n, $singular, $plural)
    {
        // choose among singular/plural  based on $n
        return $this->isPlural($n) ? $plural : $singular;
    }

    public function replace($s, $args = null)
    {
        // replace str {arg} placeholders with args
        $s = (string)$s;
        if (is_array($args))
        {
            $s = preg_replace_callback(self::$arg, function($match) use ($args) {
                $index = intval($match[1]);
                return (string)(isset($args[$index]) ? $args[$index] : $match[0]);
            }, $s);
        }
        return $s;
    }

    public function ll($s, $args = null)
    {
        // localization by translation lookup
        // context can be passed as second part of a tuple with string as first part
        $locale = $this->_currentLocale;
        $context = '';
        if (is_array($s))
        {
            if (1 < count($s)) $context = (string)$s[1];
            $s = $s[0];
        }
        if ('' == $context) $context = '*';
        $s = (string)$s;
        if ($locale && isset($this->_translations[$locale]))
        {
            $lookup = $this->_translations[$locale];
            $lookupctx = '*' !== $context && isset($lookup['@']) && isset($lookup['@'][$context]) ? $lookup['@'][$context] : $lookup;
            $ls = isset($lookupctx[$s]) ? $lookupctx[$s] : (isset($lookup[$s]) ? $lookup[$s] : $s);
        }
        else
        {
            $ls = $s;
        }
        return $this->replace($ls, $args);
    }

    public function cl(/*..args*/)
    {
        // localization by choosing among localised strings given in same order as supported locales
        // context is automatically taken care of since translations are given at the specific point
        $locale = $this->_currentLocale;
        $s = func_get_args();
        $args = count($s) > count($this->_locales) && is_array($s[count($s)-1]) ? array_pop($s) : null;
        $index = array_search($locale, $this->_locales);
        return false === $index || !isset($s[$index]) ? (isset($s[0]) ? $this->ll($s[0], $args) : '') : $this->replace($s[$index], $args);
    }

    public function l(/*..args*/)
    {
        // localization either by choosing or by lookup
        $args = func_get_args();
        if (2 > count($args) || is_array($args[1]))
        {
            return $this->ll($args[0], isset($args[1]) ? $args[1] : null);
        }
        else
        {
            return call_user_func_array(array($this, 'cl'), $args);
        }
    }

    public function ln($n, $singular, $plural, $args = null)
    {
        // singular/plural localization based on $n
        return $this->l($this->cn($n, $singular, $plural), $args);
    }

    private function merge($a, $b, $deep=false)
    {
        foreach (array_keys($b) as $k)
        {
            if ($deep)
            {
                if (isset($a[$k]) && is_array($a[$k]) && is_array($b[$k]))
                {
                    $ka = array_keys($a[$k]); $kb = array_keys($b[$k]);
                    if ($ka !== array_keys($ka) && $kb !== array_keys($kb))
                    {
                        $a[$k] = $this->merge($a[$k], $b[$k], $deep);
                    }
                    else
                    {
                        $a[$k] = $b[$k];
                    }
                }
                else
                {
                    $a[$k] = $b[$k];
                }
            }
            else
            {
                $a[$k] = $b[$k];
            }
        }
        return $a;
    }
}
}