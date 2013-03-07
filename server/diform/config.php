<?php
namespace diform;
/**
 * Description of config
 *
 * @author ble
 */
class config {
    
    public static $defaults = array(
        'template'  =>  '%/diform/template/table.php',
        'form'      =>  array(
            /*'action' => '',*/
            'method' => 'post',
        ),
        /*'legend' => '',*/
        'tip' => '?',
    );
    
    protected $template;
    
    public $form; 
    public $inputs;
    
    public function __construct($config = array()) {
        
        $this->form     =   (object) self::$defaults['form'];
        $this->inputs   =   new \stdClass;
        /**
         * 
         */
        if (is_object($config) || is_array($config))
        {
            $this->extend($config);
        }
        
        $this->template || $this->template(self::$defaults['template']);
    }
    
    public function extend($config)
    {
        foreach($config as $prop => $value)
        {
            if (method_exists($this, $prop))
            {               
                $this->$prop($value);
            }
            else
            {
                $this->$prop = $value;
            }
        }
        
        return $this;
    }
    
    public function form($form)
    {
        foreach($form as $key => $value)
        {
            $this->form->$key = $value;
        }
        return $this;
    }
    
    public function inputs($inputs)
    {
        foreach($inputs as $name => $props)
        {
            is_string($name) && $props['name'] = $name;
            if ($props['name'])
            {
                $class = '\\diform\\control\\' . (isset($props['type']) ? $props['type'] : 'text');
                $this->inputs->$name = new $class($props);
            }
        }
    }
    
    public function template($template = null)
    {
        if ($template)
        {
            $this->template = ($template{0} == '%') ?
                \diform::PATH . substr($template, 1) : $template
            ;
            
            return $this;
        }
        else
        {
            return $this->template;
        }
    }
}
