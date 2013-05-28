<?php
namespace diform\control;
/**
 * Description of radio
 *
 * @author ble
 */
class radios extends withchoices {
    
    protected $tag;
    protected $sub_control = 'radio';
    protected $labelization = array('type' => 'in', 'value' => 'right');

    public function val()
    {
        return isset($this->form->data->{$this->name}) ?
            $this->form->data->{$this->name} : null
        ;
    }
    
    public function items()
    {
        $items  =   parent::items();
        
        foreach($items as $item)
        {
            $item->attributes(
                array_replace($this->attributes, $item->attributes)
            );
        }
        
        return $items;
    }
}
