<?php
namespace diform\control;
/**
 * Description of textarea
 *
 * @author ble
 */
class info extends \diform\control
{
    public $tag = 'span';
    
    public function value($value)
    {
        $this->val = $value;
        return $this;
    }
    
    public function populate()
    {
        $this->val();
        
        $this->content = isset($this->val) ? $this->val : '';
        
        return $this;
    }
}
