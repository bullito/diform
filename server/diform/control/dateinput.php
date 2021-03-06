<?php
namespace diform\control;
/**
 * Description of dateinput
 *
 * @author ble
 */
class dateinput extends \diform\control
{
    public static $format;
    public static $replaces =   array(
        'd' =>  '(([0-2]\d)|(3[0-1]))',
        'D' =>  '(Mon|Thu|Wes|Tue|Fri|Sat|Sun)',
        'j' =>  '([1-9]|([1-2]\d)|(3[01]))',
        'l' =>  '(Monday|Th)',
        'N' => '',
        'S' => '',
        'w' => '',
        'z' => '(([12]?\d{1,2})|3(([0-5]\d)|(6[0-5])))',
        'F' => '',
        'm' => '((0[1-9])|(1[0-2]))',
        'M' => '',
        'n' => '',
        't' => '(28|29|30|31)',
        'L' => '0|1',
        'o' => '(\d{4})',
        'Y' => '(\d{4})',
        'y' => '(\d{2})',
        'a' => '(am|pm)',
        'A' => '(AM|PM)',
        'B' => '(\d{3})',
        'g' => '([1-9]|(1[0-2]))',
        'G' => '(\d|(1\d)|(2[0-3]))',
        'h' => '((0[1-9])|(1[0-2]))',
        'H' => '(([01]\d)|(2[0-3]))',
        'i' => '([0-5]\d)',
        's' => '([0-5]\d)',
        'u' => '',  
        'e' => '',
        'I' => '',
        'O' => '',
        'P' => '',
        'T' => '',
        'Z' => '',
        'c' => '',
        'r' => '',
        'U' => '',
    );
    
    public function __construct($form = null)
    {
        parent::__construct($form);
        $nodes = explode('\\', get_class($this));
        $this->attr('type', array_pop($nodes));
        $this->attr('pattern', self::format2pattern(static::$format));
    }
    
    public static function format2pattern($format)
    {
        return '^'.str_replace(
            array_keys(static::$replaces), 
            array_values(static::$replaces), 
            $format
        ).'$';
    }
}
