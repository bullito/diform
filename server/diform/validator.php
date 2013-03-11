<?php

namespace diform;

/**
 * Description of validator
 *
 * @author b.le
 */
class validator
{

    public static $defaults = array(
        'feedback' => array(
            'en' => 'invalid'
        )
    );
    protected static $rules    = array();
    protected static $lang     = 'en';

    /**
     * 
     * @param string $matcher
     * @param callable $check
     * @param string|assoc $feedback
     */
    public static function rule($matcher, $check, $feedback = null)
    {
        assert(is_string($matcher));
        assert(is_callable($check));

        static::$rules[$matcher] = compact('matcher', 'check', 'feedback');
    }

    public static function is_disabled($control)
    {
        return isset($control->attributes['disabled']) &&
            $control->attributes['disabled'];
    }

    public static function rules4Attribute($attribute)
    {
        return isset(static::$rules["[$attribute]"]) ?
            static::$rules["[$attribute]"] : false
        ;
    }

    /**
     * 
     * @param control $control
     * @return boolean|string
     */
    public static function checkValidity($control)
    {

        if (static::is_disabled($control))
            return true;

        foreach ($control->rules() as $matcher => $rule)
        {
            if ($rule === true)
                $rule = self::$rules[$matcher];

            if (!$rule['check']($control))
                return static::feedback4ControlAndRule($control, $rule);
        }

        return true;
    }

    public static function feedback4ControlAndRule($control, $rule)
    {
        if (($feedback = $control->attr('data-message')))
            return $feedback;


        if (isset($rule['feedback']))
        {
            if (is_array($rule['feedback']))
            {
                $lang = static::lang4Control($control);

                if (isset($rule['feedback'][$lang]))
                    return $rule['feedback'][$lang];
                else if (($feedback = array_shift($rule['feedback'])))
                    return $feedback;
            }
            else if (is_string($rule['feedback']))
            {
                return $rule['feedback'];
            }
        }

        return static::$defaults['feedback'] ? : 'invalid';
    }

    public static function lang4Control($control)
    {
        if (isset($control->lang))
            return $control->lang;

        if (isset($control->form->lang))
            return $control->form->lang;

        return self::$lang;
    }

    public static function feedbacks()
    {
        $feedbacks = array('*' => static::$defaults['feedback']);
        foreach (static::$rules as $matcher => $rule)
        {
            if (isset($rule['feedback']))
            {
                $feedbacks[$matcher] = $rule['feedback'];
            }
        }
        return $feedbacks;
    }

}

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
