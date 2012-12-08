<?php
namespace diform\control;
/**
 * Description of number
 *
 * @author ble
 */
class number extends \diform\control
{
    public $attributes = array(
        'type' => 'number',
        'pattern' => '^-?\d*\.\d*$'
    );
}
