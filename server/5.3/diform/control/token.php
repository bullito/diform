<?php
namespace diform\control;
/**
 * Description of number
 *
 * @author ble
 */
class token extends \diform\control
{
    protected static $register_key = 'diform_tokens';
    protected $generated;
    
    public $attributes = array(
        'type' => 'hidden',
        'pattern' => '^-?\d*\.\d*$'
    );
    
    public function __construct($form = null)
    {
        parent::__construct($form);
        
        session_start();
        $this->generated = static::register();
        
        $this->rule('token', function($control) {
            
            $class = get_class($control);
            
            if (in_array($token = $control->val(), $class::registered()))
            {
                static::unregister($token);
                return true;
            }
        });
        
        $this->val();
    }
    
    public static function generate()
    {
        return uniqid('diform_token_', true);
    }

    public static function register() 
    {
        $token = static::generate();
        
        $_SESSION[static::$register_key][$token]  =   true;
        
        return $token;
    }
    
    public static function unregister($token)
    {
        unset($_SESSION[static::$register_key][$token]);
    }
    
    /**
     * 
     * @return array
     */
    public function registered()
    {
        return $_SESSION[static::$register_key];
    }
    
    /**
     * avoid replacing value by request input
     * request input will be kept 
     * to compare with registered tokens
     * @return \diform\control\token
     */
    public function populate()
    {
        $this->val();
        return $this;
    }
}
