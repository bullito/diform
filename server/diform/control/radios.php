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
    
    public function render_item($item)
    {
        $item->attributes(
            array_replace($this->attributes, $item->attributes)
        );
        return '<label>'.$item->render(true).$item->label.'</label>';
    }
}
