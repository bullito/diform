<?php
namespace diform\control;
/**
 * Description of number
 *
 * @author ble
 */
class integer extends \diform\control
{
    public $attributes = array(
        'type' => 'number',
        'pattern' => '^-?\d+$',
        'step' => 1
    );
}
