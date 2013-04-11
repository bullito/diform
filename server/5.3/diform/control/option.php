<?php
namespace diform\control;
/**
 * Description of text
 *
 * @author ble
 */
class option extends checkable
{
    public $checkattr = 'selected';
    public $tag = 'option';
    
    public function prepare()
    {
        $this->content  =   $this->label;
        return $this;
    }
}
