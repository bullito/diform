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
        'error_decorator' => '<span class="error">{$error}</span>'
    );
    
    protected $_template;
    protected $_diform;
    
    public $form     = array();
    public $controls = array();
    public $error_decorator;

    public function __construct(\diform $diform = null, $extend = null)
    {
        $this->_diform  = $diform;
        $this->controls = new \stdClass;

        $this->extend(static::$defaults);
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
        if (isset($config))
        {
            foreach ($config as $prop => $value)
            {
                if (method_exists($this, $prop))
                    $this->$prop($value);
                else
                    $this->$prop = $value;
            }
        }

        return $this;
    }

    public function lang($lang)
    {
        $this->_diform->lang($lang);
        return $this;
    }

    /**
     * set form configuration
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

            $control = $this->_diform->control($type);

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
