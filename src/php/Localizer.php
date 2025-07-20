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

    public function locale($locale = null, $value = null)
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
                // array of translated strings
                if (!isset($this->_translations[$locale])) $this->_translations[$locale] = array();
                $this->_translations[$locale] = array_merge($this->_translations[$locale], $value);
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

    public function r($s, $args = null)
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
        $locale = $this->_currentLocale;
        $s = (string)$s;
        return $this->r($locale && isset($this->_translations[$locale]) && isset($this->_translations[$locale][$s]) ? $this->_translations[$locale][$s] : $s, $args);
    }

    public function cl(/*..args*/)
    {
        // localization by choosing among localised strings given in same order as supported locales
        $locale = $this->_currentLocale;
        $s = func_get_args();
        $args = count($s) > count($this->_locales) ? array_pop($s) : null;
        $index = array_search($locale, $this->_locales);
        return $this->r(false === $index || !isset($s[$index]) ? '' : $s[$index], $args);
    }

    public function l(/*..args*/)
    {
        // localization both by choosing and by lookup
        $args = func_get_args();
        if (2 > count($args) || null === $args[1] || is_array($args[1]))
        {
            return $this->ll($args[0], isset($args[1]) ? $args[1] : null);
        }
        else
        {
            return call_user_func_array(array($this, 'cl'), $args);
        }
    }

    public function cn($n, $singular, $plural)
    {
        // choose among singular/plural  based on $n
        return $this->isPlural($n) ? $plural : $singular;
    }

    public function ln($n, $singular, $plural, $args = null)
    {
        // singular/plural localization based on $n
        return $this->l($this->cn($n, $singular, $plural), $args);
    }
}
}