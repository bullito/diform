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

    public static function rule($name, $check, $feedback = null)
    {
        assert(is_string($name));
        assert(is_callable($check));
        assert(isset($feedback) || is_array($feedback));
        
        static::$rules[$name]   =   compact('name', 'check', 'feedback');        
    }
    
    public static function is_disabled($control)
    {
        return isset($control->attributes['disabled']) &&
        $control->attributes['disabled'];
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

validator::rule('required', function($control) {
    
    if (!isset($control->attributes['required']))
    {
        return true;
    }
    
    $val    =   $control->val();
    
    if (!isset($val))
    {
        return false;
    }
    
    if (empty($val))
    {
        return false;
    }
    
    return true;
}, array(
    'en' => 'required',
    'fr' => 'manquant'
));

validator::rule('pattern', function($control) {
    
    if (!isset($control->attributes['pattern']))
    {
        return true;
    }
    
    return preg_match('~'.$control->attributes['pattern'].'~', $control->val());
}, array(
    'en'    =>  'wrong format',
    'fr'    =>  'mal formaté'
));

validator::rule('maxlength', function($control) {
    
    return 
        !isset($control->attributes['maxlength'])
    or
        strlen($control->val()) <= $control->attributes['maxlength']
        || !$control->invalidate('maximum ' . $control->attributes['maxlength'] . ' caractères')
    
    ;
});

validator::rule('minlength', function($control) {
    echo 'minliength';
     if (!isset($control->attributes['minlength']))
     {
         return true;
    }
     
    $minlength =   parseInt($control->attributes['minlength']);
    $length    =   strlen($control->val());
    
    return (! $length || $length >= $minlength);
});

//validator::rule('data-at-', $check, $feedback)