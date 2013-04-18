<?php
namespace diform\control;
/**
 * Description of number
 *
 * @author ble
 */
class token extends \diform\control
{
    public $storing = 'session';
    
    public $attributes = array(
        'type' => 'hidden',
        'pattern' => '^-?\d*\.\d*$'
    );
    
    public function __construct($form = null)
    {
        parent::__construct($form);
        
        $storing    =   "token\\$this->storing";
        $this->storing  =   new $storing;
        
        $this->value = $this->storing->add();
        
        $this->rule('token', function($control) {
            
            return $control->storing->check();
        });
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
