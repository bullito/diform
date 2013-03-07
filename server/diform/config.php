<?php

namespace diform;

/**
 * Description of config
 *
 * @author ble
 */
class config
{

    public static $defaults = array(
        'template' => '%/diform/template/table.php',
        'form' => array(
            /* 'action' => '', */
            'method' => 'post',
        ),
        /* 'legend' => '', */
        'tip' => '?',
    );
    protected $template;
    public $form;
    public $controls;

    public function __construct($config = array())
    {

        $this->form     = (object) self::$defaults['form'];
        $this->controls = new \stdClass;
        /**
         * 
         */
        if (is_object($config) || is_array($config))
        {
            $this->extend($config);
        }

        $this->template || $this->template(self::$defaults['template']);
    }

    /**
     * 
     * @param array|\Traversable $config
     * @return \diform\config
     */
    public function extend($config)
    {
        foreach ($config as $prop => $value)
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

    /**
     * 
     * @param array|\Traversable $form
     * @return \diform\config
     */
    public function form($form)
    {
        foreach ($form as $key => $value)
        {
            $this->form->$key = $value;
        }
        return $this;
    }

    
    public function controls($controls)
    {
        foreach ($controls as $name => $props)
        {
            if (is_string($name))
                $props['name'] = $name;
            
            if ($props['name'])
            {
                $class                 = '\\diform\\control\\' . (isset($props['type']) ? $props['type'] : 'text');
                $this->controls->$name = new $class($props);
            }
        }
    }

    public function template($template = null)
    {
        if ($template)
        {
            $this->template = ($template{0} == '%') ?
                \diform::PATH . substr($template, 1) : 
                $template
            ;

            return $this;
        }
        else
        {
            return $this->template;
        }
    }

}
