<?php
namespace diform;
/**
 * Description of extendable
 *
 * @author b.le
 */
class extendable
{
    /**
     *
     * @var \diform
     */
    protected $_diform;
    
    /**
     *
     * @var array 
     */
    public static $defaults = array();

    public function __construct(\diform $diform = null, $extend = null)
    {
        $this->_diform  =   $diform;
        $this->extend(static::$defaults)->extend($extend);
    }
    
    public function extend($data)
    {
        if (isset($data))
        {
            foreach ($data as $key => $val)
                $this->$key = $val;
        }
        
        return $this;
    }
}

