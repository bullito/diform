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
    
    public static function rule($rule, $func)
    {
        static::$rules[$rule]   =   $func;
    }
    
    public static function check($control)
    {
        $o  =   array();
        foreach(static::$rules as $rule => $func)
        {
            $o[$rule]  =   $func($control);
        }
        
        foreach($control->rules() as $rule => $func)
        {
            $o['custom-'.$rule]  =   $func($control);
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
});

validator::rule('pattern', function($control) {
    
    return 
        !isset($control->attributes['pattern']) 
    or 
        preg_match('~'.$control->attributes['pattern'].'~', $control->val())
    ;
});

validator::rule('min-length', function($control) {
    
    return 
        !isset($control->attributes['min-length']) 
    or 
        strlen($control->val()) >= $control->attributes['min-length']
    ;
});

validator::rule('max-length', function($control) {
    
    return 
        !isset($control->attributes['max-length'])
    or
        strlen($control->val()) <= $control->attributes['max-length']
    ;
});