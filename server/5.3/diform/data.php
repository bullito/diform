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
    public static $classmap = array();
    /**
     *
     * @var boolean 
     */
    protected $_isPopulated;
    
    /**
     *
     * @var array 
     */
    protected $_raw = array();
    /**
     *
     * @var array 
     */
    protected $_value;
    
    /**
     * 
     * @param array|object $data
     * @return \diform\data
     */
    public function extend($data)
    {
        $this->_extend($data);    //  object/array
        return $this;
    }

    /**
     * 
     * @param array $data
     * @param string $prefix
     */
    protected function _extend($data, $prefix = '')
    {
        if ($data)
        {
            foreach ($data as $key => $value)
            {
                $name = $prefix ? ($prefix . '[' . $key . ']') : $key;

                if (is_object($value))
                {
                    $class  =   get_class($value);
                    
                    if (isset(static::$classmap[$class]))
                    {
                        $map    =   static::$classmap[$class];
                        $value  =   $map($value);
                    }
                    
                    if (is_object($value))
                    {
                        $this->_extend($value, $name);
                        continue;
                    }
                }
                else if (is_array($value))
                {
                    if (is_string(key($value)))
                    {
                        $this->_extend($value, $name);
                        continue;
                    }
                    else if (!is_scalar(current($value)))
                    {
                        $this->_extend($value, $name);
                        continue;
                    }
                }
                
                $this->$name = $this->_raw[$name] = $value; 
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
            $this->request($GLOBALS['_'.strtoupper($this->_diform->config->form['method'])]);
        }
        
        return $this->_isPopulated;
    }
    
    /**
     * 
     * @return assoc
     */
    public function value()
    {
        if (!isset($this->_value))
        {
            $this->_value = array();
            
            foreach($this->_diform->controls() as $key => $control)
            {
                if ($control->checkValidity() === true && isset($this->$key))
                { 
                    $nodes  =   preg_split('~\]?\[~', trim($key,']'));
                    //var_dump($nodes);
                    $branch =   &$this->_value;
                    $final  =   array_pop($nodes);
                    foreach($nodes as $node)
                    {
                        isset($branch[$node]) or $branch[$node] =   array();
                        $branch =   &$branch[$node];
                    }
                    $branch[$final] =   $this->$key;   
                }
            }
        }
        return $this->_value;
    }
    
    public function request($data)
    {
        if ($data)
        {
            $this->_isPopulated =   true;
            $this->extend($data);    //  object/array
        }
        
        return $this;
    }
    
    /**
     * 
     * @return assoc
     */
    public function raw()
    {
        return $this->_raw;
    }
}

