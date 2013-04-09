<?php
namespace diform\control;
/**
 * Description of multiple
 *
 * @author ble
 */
class withchoices extends \diform\control 
{
    /**
     *
     * @var assoc|\Traversable 
     */
    protected $choices = array();
    protected $sub_control;
    
    public function subname()
    {
        return '';
    }
    
    public function prepare()
    {
        $this->content  =   '';
        
        $control   =   "\\diform\\control\\$this->sub_control";
        
        $item_model   =   new $control();
        
        /**
         * @todo manage optgroup
         */
        $val = $this->val();
        
        foreach($this->choices as $label => $value)
        {
            $item          =   clone $item_model;
            $item
                ->label(is_string($label) ? $label : $value)
                ->attr('value', $value)
            ;
            
            if (isset($val))
            {
                $item->val($val);
            }
            $this->content .=  $this->render_item($item);
        }
        
        return $this;
    }
    
    public function render_item($item)
    {
        return $item->render(true);
    }
    
    public function choices(/* $value */)
    {
        if (func_num_args() == 1)
        {
            $this->{__FUNCTION__} = func_get_arg(0);
            return $this;
        }
        else
        {
            return $this->{__FUNCTION__};
        }  
    }
    
    public function populate()
    {
        return $this;
    }
}
