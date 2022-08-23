<?php
/**
*
* Simple class to localize texts for PHP, JavaScript, Python
* @version 0.1.0
* https://github.com/foo123/Localizer
*
*/

if (!class_exists('Localizer', false))
{
class Localizer
{
    const VERSION = '0.1.0';

    private $currentLocale = null;
    private $translations = array();
    private $plurals = array();

    public function __construct()
    {
    }

    public function locale($locale = null, $translations = null)
    {
        if (func_num_args())
        {
            $locale = (string)$locale;
            if (is_callable($translations))
            {
                // plural form for locale as callable
                $this->plurals[$locale] = $translations;
            }
            elseif (is_array($translations) && !empty($translations))
            {
                if (!isset($this->translations[$locale])) $this->translations[$locale] = array();
                $this->translations[$locale] = array_merge($this->translations[$locale], $translations);
            }
            $this->currentLocale = $locale;
            return $this;
        }
        return $this->currentLocale;
    }

    public function l($s, $args = null)
    {
        // localization
        $locale = $this->currentLocale;
        $s = (string)$s;
        $ls = $locale && isset($this->translations[$locale]) && isset($this->translations[$locale][$s]) ? (string)$this->translations[$locale][$s] : $s;
        if (is_array($args)) $ls = vsprintf($ls, $args);
        return $ls;
    }

    public function isPlural($n)
    {
        // custom plural form per locale
        $locale = $this->currentLocale;
        $isPlural = $locale && isset($this->plurals[$locale]) && is_callable($this->plurals[$locale]) ? (bool)call_user_func($this->plurals[$locale], $n) : (1 != $n);
        return $isPlural;
    }

    public function nl($n, $singular, $plural, $args = null)
    {
        // singular/plural localization based on $n
        return $this->l($this->isPlural($n) ? $plural : $singular, $args);
    }
}
}