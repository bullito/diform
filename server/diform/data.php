<?php

namespace diform;

/**
 * Description of data
 *
 * @author ble
 */
class data extends extendable
{

    public static $defaults = array();

    /**
     *
     * @var boolean 
     */
    protected $_isPopulated = false;

    public function extend($data)
    {
        $data = (array) json_decode(json_encode($data));
        $this->_extend($data);
        
        return $this;
    }

    public function _extend($data, $prefix = '')
    {
        foreach ($data as $key => $value)
        {
            $name = $prefix ? ($prefix . '[' . $key . ']') : $key;

            if (is_object($value))
            {
                $this->_extend($value, $name);
            }
            else
            {
                $this->$name = $value;
            }
        }
    }
    
    public function request($request)
    {
        if ($request)
        {
            $this->_isPopulated =   true;
            $this->extend($request);
        }
        
        return $this;
    }
    
    public function isPopulated()
    {
        return $this->_isPopulated;
    }
}

