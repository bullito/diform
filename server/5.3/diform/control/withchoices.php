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
    protected $labelization;
    
    public function subname()
    {
        return '';
    }
    
    public function prepare()
    {
        $this->content  =   '';
        
        $control   =   "\\diform\\control\\$this->sub_control";
        
        $item_model   =   new $control($this->form);
        
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
        if (is_array($this->labelization))
        {
            switch($this->labelization['type'])
            {
                case 'out':
                    isset($this->attributes['id']) or ($this->attributes['id'] =   
                        preg_replace('/([^0-9a-zA-Z_])/', '_', $this->attributes['name'] . '_' . $item->attributes['value'])
                    );
                    $label      =   "<label for=\"{$this->attributes['id']}\">$item->label</label>"; 
                    //  no break
                case 'in':
                    $label      =   isset($label) ? $label : $item->label;
                    $control    =   $item->render(true);
                    
                    switch($this->labelization['value'])
                    {
                        case 'left':     $render =   $label.$control;
                        case 'right':    $render =   $control.$label;
                    }
                    
                    return ($type == 'in') ? "<label>$render</label>" : $render;
                    
                case 'custom':
                case 'func':
                    $func   =   $this->labelization['value'];
                    return $func($item);
                
                default:
                    throw new \diform\exception(__METHOD__.": labelization [$this->labelization] not supported");
            }
        }
        else
        {
            return $item->render(true);
        }
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
    
    /**
     * 
     * @param string $position before|outer|after|null
     */
    public function labelization($type, $value)
    {
        $this->labelization =   compact('type', 'value');
        return $this;
    }
}
