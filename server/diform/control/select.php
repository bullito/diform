<?php
namespace diform\control;
/**
 * Description of select
 *
 * @author ble
 */
class select extends withchoices
{
    protected $tag = 'select';
    protected $sub_control = 'option';


    public function attrs()
    {
        if ($this->has_multiple)
        {
            if (isset($this->attributes['name']) 
                && !preg_match('~(.+)\[\]~', $this->attributes['name']))
            {
                $this->attributes['name'] .= '[]';
            }
        }
        return parent::attrs();
    }   
    
}
