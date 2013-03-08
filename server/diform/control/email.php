<?php
namespace diform\control;
/**
 * Description of text
 *
 * @author ble
 */
class email extends \diform\control
{
    public $attributes = array(
        'type' => 'email',
        'pattern' => '^[a-zA-Z0-9_]+(([._-]?[a-zA-Z0-9]+)*)@(([a-zA-Z0-9]+[.-]?)*)([a-zA-Z0-9]+)\.(([a-zA-Z]{2})|biz|net|com|gov|mil|org|edu|int|info)$'
    );
}
