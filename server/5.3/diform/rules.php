<?php
namespace diform;

validator::rule('[required]', function($control)
    {
        $val = $control->val();
        return isset($val) && !empty($val);
    }, array(
    'en' => 'required',
    'fr' => 'manquant'
));

validator::rule('[minlength]', function($control)
    {
        $minlength = parseInt($control->attributes['minlength']);
        $length    = strlen($control->val());

        return ($length === 0 || $length >= $minlength);
    }, array(
    'en' => 'too short',
    'fr' => 'trop court'
));

validator::rule('[maxlength]', function($control)
    {
        $maxlength = parseInt($control->attributes['maxlength']);
        $length    = strlen($control->val());

        return ($length === 0 || $length >= $maxlength);
    }, array(
    'en' => 'too long',
    'fr' => 'trop long'
));

validator::rule('[pattern]', function($control)
    {

        $val = $control->val();
        if (empty($val))
            return true;

        $pattern = $control->attributes['pattern'];

        return preg_match("~$pattern~", $val);
    }, array(
    'en' => 'wrong format',
    'fr' => 'mal format&eacute;'
));

validator::rule('[min]', function($control)
    {
        $min   = $control->attributes['min'];
        $value = $control->val();

        return (empty($value) || $min <= $value);
    }, array(
    'en' => 'too low',
    'fr' => 'valeur trop basse'
));

validator::rule('[max]', function($control)
    {
        $max   = $control->attributes['max'];
        $value = $control->val();

        return (empty($value) || $value <= $max);
    }, array(
    'en' => 'too high',
    'fr' => 'valeur trop &eacute;lev&eacute;e'
));
