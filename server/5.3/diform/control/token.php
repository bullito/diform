<?php
namespace diform\control;
/**
 * Description of number
 *
 * @author ble
 */
class token extends \diform\control
{
    public $attributes = array(
        'type' => 'hidden',
        'pattern' => '^-?\d*\.\d*$'
    );
    
    public function __construct($form = null)
    {
        parent::__construct($form);
        
        $token = static::register();
        $this->rule('token', function($control) {
            $class = get_class($control);
            return in_array($control->val(), $class::registered());
        });
        
        $this->val();
    }
    
    public static function register() 
    {
        $token = uniqid('diform_token_', true);
    }
    
    public function registered()
    {
        return $_SESSION['diform_token'];
    }
}
