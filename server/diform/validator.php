<?php
namespace diform;
/**
 * Description of validator
 *
 * @author b.le
 */
class validator
{
    protected static $rules    =   array();
    protected static $feedback_default;

    public static function rule($matcher, $check, $feedback = null)
    {
        assert(is_string($matcher));
        assert(is_callable($check));
        assert(isset($feedback) || is_array($feedback));
        
        static::$rules[$matcher]   =   compact('name', 'check', 'feedback');        
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
        
        $o  =   array();
        
        foreach(static::$rules as $rule => $func)
        {
            $o[$rule]  =   !$func($control);
        }
        
        foreach($control->rules() as $rule => $func)
        {
            $o['custom-'.$rule]  =  !$func($control);
        }

        return array_filter($o) ?: true;
    }
}

validator::rule('[required]', function($control) {
    
    return isset($control->val()) && !empty($control->val());
}, array(
    'en' => 'required',
    'fr' => 'manquant'
));

validator::rule('[pattern]', function($control) {
    
  return preg_match('~'.$control->attributes['pattern'].'~', $control->val());
}, array(
    'en'    =>  'wrong format',
    'fr'    =>  'mal formaté'
));


validator::rule('[minlength]', function($control) {

    $minlength =   parseInt($control->attributes['minlength']);
    $length    =   strlen($control->val());
    
    return ($length === 0 || $length >= $minlength);
},
 array(
    'en' => 'too short',
    'fr' => 'trop court'
));

validator::rule('[maxlength]', function($control) {
    
    $maxlength =   parseInt($control->attributes['minlength']);
    $length    =   strlen($control->val());
    
    return ($length === 0 || $length >= $maxlength);
}, array(
    'en' => 'too long',
    'fr' => 'trop long'
));
