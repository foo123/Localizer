<?php
/**
*
* Simple class to localize texts for PHP, JavaScript, Python
* @version 1.0.0
* https://github.com/foo123/Localizer
*
*/

if (!class_exists('Localizer', false))
{
class Localizer
{
    const VERSION = '1.0.0';

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
            $locale = (string)$locale;
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

    public function cl(/*..args*/)
    {
        // choose among given localised strings
        // based on index of current locale among supported locales
        $args = func_get_args();
        $index = array_search($this->_currentLocale, $this->_locales);
        return false === $index ? $args[0] : $args[$index];
    }

    public function l($s, $args = null)
    {
        // localization
        $locale = $this->_currentLocale;
        $s = (string)$s;
        $ls = $locale && isset($this->_translations[$locale]) && isset($this->_translations[$locale][$s]) ? (string)$this->_translations[$locale][$s] : $s;
        if (is_array($args))
        {
            $ls = preg_replace_callback(self::$arg, function($match) use ($args) {
                $index = intval($match[1]);
                return isset($args[$index]) ? (string)$args[$index] : $match[0];
            }, $ls);
        }
        return $ls;
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