<?php

namespace diform;

/**
 * Description of validator
 *
 * @author b.le
 */
class validator
{

    protected static $rules = array();
    protected static $feedback_default;
    protected static $lang = 'en';

    public static function rule($matcher, $check, $feedback = null)
    {
        assert(is_string($matcher));
        assert(is_callable($check));
        assert(isset($feedback) || is_array($feedback));

        static::$rules[$matcher] = compact('name', 'check', 'feedback');
    }

    public static function is_disabled($control)
    {
        return isset($control->attributes['disabled']) &&
            $control->attributes['disabled'];
    }

    public static function rules4Attribute($attribute)
    {
        return isset(static::$rules["[$attributes]"]) ?
            static::$rules["[$attributes]"] : false
        ;
    }

    public static function check($control)
    {
        if (static::is_disabled($control))
        {
            return true;
        }

        $o = array();

        foreach ($control->rules() as $matcher => $rule)
        {
            ($rule === true) and ($rule = self::$rules[$matcher]);

            if (is_array($rule) && is_callable($rule['check']))
            {
                ($rule['check']($control)) or 
                    ($o[] = static::feedback4ControlAndRule($control, $rule))
                ;
            }
        }

        return array_filter($o) ? : true;
    }

    public static function feedback4ControlAndRule($control, $rule)
    {
        if (($feedback = $control->attr('data-message')))
        {
            return $feedback;
        }
        
        if (isset($rule['feedback']))
        {
            if (is_array($rule['feedback']))
            {
                $lang   =   static::lang4Control($control);
                
                if (isset($rule['feedback'][$lang]))
                {
                    return $rule['feedback'][$lang];
                }
                else if (($feedback = array_shift($rule['feedback'])))
                {
                    return $feedback;
                }
            }
            else if (is_string($rule['feedback']))
            {
                return $rule['feedback'];
            }
        }
        
        return self::$feedback_default ?: 'invalid';
    }
    
    public static function lang4Control($control)
    {
        if (isset($control->lang))
        {
            return $control->lang;
        }
        
        if (isset($control->form->lang))
        {
            return $control->form->lang;
        }
        
        return self::$lang;
    }
    
}

validator::rule('[required]', function($control)
    {

        return isset($control->val()) && !empty($control->val());
    }, array(
    'en' => 'required',
    'fr' => 'manquant'
));

validator::rule('[pattern]', function($control)
    {

        return preg_match('~' . $control->attributes['pattern'] . '~', $control->val());
    }, array(
    'en' => 'wrong format',
    'fr' => 'mal formaté'
));


validator::rule('[minlength]', function($control)
    {

        $minlength = parseInt($control->attributes['minlength']);
        $length = strlen($control->val());

        return ($length === 0 || $length >= $minlength);
    }, array(
    'en' => 'too short',
    'fr' => 'trop court'
));

validator::rule('[maxlength]', function($control)
    {

        $maxlength = parseInt($control->attributes['minlength']);
        $length = strlen($control->val());

        return ($length === 0 || $length >= $maxlength);
    }, array(
    'en' => 'too long',
    'fr' => 'trop long'
));
