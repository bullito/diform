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
    protected $_isPopulated;

    /**
     * 
     * @param array|object $data
     * @return \diform\data
     */
    public function extend($data)
    {
        if ($data)
        {
            $this->_isPopulated =   true;
            $this->_extend($data);
        }
        
        return $this;
    }

    /**
     * 
     * @param array $data
     * @param string $prefix
     */
    protected function _extend($data, $prefix = '')
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

    /**
     * 
     * @return boolean
     */
    public function isPopulated()
    {
        if (!isset($this->_isPopulated))
        {
            $this->_isPopulated =   false;
            $this->extend($GLOBALS['_'.strtoupper($this->_diform->config->form['method'])]);
        }
        
        return $this->_isPopulated;
    }
}

