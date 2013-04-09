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
        
        $this->rule('token', function($control) {
            return ture;
        });
    }
}
