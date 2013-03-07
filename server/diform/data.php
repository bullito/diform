<?php
namespace diform;

/**
 * Description of data
 *
 * @author ble
 */
class data {
    
    /**
     *
     * @var \diform 
     */
    protected $_diform;
    
    /**
     *
     * @var boolean 
     */
    protected $_isPopulated = false;
    
    public function __construct(\diform $diform = null, $data = array()) {
        
        $this->_diform  = $diform;
        $this->extend($data);
    }
    
    public function extend($data, $prefix = '')
    {
        $data   =   (array)json_decode(json_encode($data));
        
        $this->_isPopulated =  $this->_isPopulated || !!$data;
        
        foreach($data as $key => $value)
        {
            $name   =   $prefix ? ($prefix.'['.$key.']') : $key;
            
            if (is_object($value))
            {
                $this->extend($value, $name);
            }
            else
            {   
                $this->$name = $value;
            }
        }
        
        return $this;
    }
    
    public function isPopulated()
    {
        return $this->_isPopulated;
    }
}

