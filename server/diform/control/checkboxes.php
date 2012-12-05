<?php
namespace diform\control;
/**
 * Description of text
 *
 * @author ble
 */
class checkboxes extends withchoices
{
    protected $tag;
    protected $sub_control = 'checkbox';
    
    public function render_item($item)
    {
        $item->attributes(
            array_replace($this->attributes, $item->attributes)
        );
        $item->attr('name', $this->attributes['name'].'[]');
        return '<label>'.$item->render(true).$item->label.'</label>';
    }
}
