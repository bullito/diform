<?php
namespace diform\control;
/**
 * Description of checkable
 *
 * @author ble
 */
class checkable extends \diform\control
{
    public $checkattr;
    
    public function populate()
    {
        $this->val();
        
        if (isset($this->val))
        {
            $this->attr(
                $this->checkattr, 
                is_array($this->val) ?
                    in_array($this->value, $this->val) :
                    $this->value == $this->val
            );
        }
        
        return $this;
    }
}
