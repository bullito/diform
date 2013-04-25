<?php
namespace diform\control;
/**
 * Description of textarea
 *
 * @author ble
 */
class textarea extends \diform\control
{
    public $tag = 'textarea';
    
    public function value($value)
    {
        $this->val = $value;
        return $this;
    }
    
    public function populate()
    {
        $this->val();
        
        $this->content = isset($this->val) ? 
            str_replace(array('<', '>'), array('&lt;', '&gt;'), stripslashes($this->val)) 
            : ''
        ;
        
        return $this;
    }
}
