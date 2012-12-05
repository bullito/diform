<?php
namespace diform;

/**
 * Description of data
 *
 * @author ble
 */
class data {
    
    
    public function __construct($data = array()) {
        
        $this->extend($data);
    }
    
    public function extend($data, $prefix = '')
    {
        $data   =   (array)json_decode(json_encode($data));
        
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
}

