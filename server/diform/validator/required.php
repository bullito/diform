<?php
namespace diform\validator;
/**
 * Description of required
 *
 * @author b.le
 */
class required extends \diform\validator
{
    public static function check($control)
    {
        $val    =   $control->val();
        if (!isset($val))
        {
            return false;
        }
        if (empty($val))
        {
            return false;
        }
        return true;
    }
}
