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
    
    protected $sync_value = false;
    
    protected $attributes = array(
        'type' => 'hidden',
        'name' => 'token'
    );
    
    public function __construct($form = null)
    {
        parent::__construct($form);
        
        $storing    =   __NAMESPACE__."\\token\\$this->storing";
        $this->storing  =       $storing::instance();
        
        $this->attributes['value'] = $this->storing->add();
        
        $this->rule('token', function($control) {
            return $control->storing->check($control->val());
        }, array(
            'fr' => 'token non valide', 
            'en' => 'token not valid')
        );
    }
}
