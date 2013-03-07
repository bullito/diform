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
    protected $_template;
    protected $_diform;
    public $form = array();
    public $controls = array();

    public function __construct(\diform $diform = null, $extend = array())
    {
        $this->_diform  = $diform;
        $this->form     = static::$defaults['form'];
        $this->controls = new \stdClass;
        /**
         * 
         */
        $this->extend($extend);
        
        $this->_template || $this->template(self::$defaults['template']);
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
        $this->form = array_replace_recursive($this->form, (array) $form);
        return $this;
    }

    public function controls($controls)
    {
        foreach ($controls as $name => $props)
        {
            if (is_string($name))
                $props['name'] = $name;

            $type = isset($props['type']) ? $props['type'] : 'text';
            unset($props['type']);

            $class = '\\diform\\control\\' . $type;

            $control = new $class($this->_diform);
            
            foreach ($props as $prop => $value)
                $control->$prop($value);

            $this->controls->{$props['name']} = $props;

            if ($this->_diform)
                $this->_diform->add($control);
        }

        return $this;
    }

    public function template($template = null)
    {
        if ($template)
        {
            $this->_template = ($template{0} == '%') ?
                \diform::PATH . substr($template, 1) :
                $template
            ;

            return $this;
        }
        else
        {
            return $this->_template;
        }
    }

}
