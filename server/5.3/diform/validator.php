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

    public static function rules(/* */)
    {
        if (($rules = func_get_args()))
        {
            static::$rules  = array_replace_recursive(static::$rules, $rules);
        }
        return static::$rules;
    }
}

include __DIR__.'/rules.php';
