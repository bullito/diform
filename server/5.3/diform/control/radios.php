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

    public function render_item($item)
    {
        $item->attributes(
            array_replace($this->attributes, $item->attributes)
        );
        return parent::render_item($item);
    }
}
