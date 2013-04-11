<?php

/**
 * Description of diform
 *
 * @author ble
 * @method \diform\control\text text(string $name, mixed $value) add a textfield to form
 * @method \diform\control\textarea textarea(string $name, mixed $value) add a textarea to form
 */
class diform
{
    /** @var string  */

    const PATH = __DIR__;

    /**
     *
     * @var array|of|\diform\control 
     */
    protected $controls = array();

    /**
     *
     * @var \diform\config 
     */
    public $config;

    /**
     *
     * @var \diform\data 
     */
    public $data;

    /**
     *
     * @var \diform\events
     */
    public $events;

    /**
     * tag attributes
     * @var assoc 
     */
    public $attributes = array();


    /**
     * Activate/deactivate lazyloading for diform.
     * Lazy Load is not auto-enabled because of possible redundancy
     * with other strategies when integrating in a framework / project.
     * @param bool $boo
     */
    public static function lazyLoad($boo = true)
    {
        if ($boo)
            spl_autoload_register(array(__CLASS__, 'loadClass'));
        else
            spl_autoload_unregister(array(__CLASS__, 'loadClass'));
    }

    public static function loadClass($class)
    {
        $path = self::PATH . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($path))
            include $path;
    }

    /**
     * returns html attributes for a vector
     * 
     * @param array|Traversable $vector
     * @return string
     */
    public static function attrs($vector)
    {
        $arr = array();
        
        foreach ($vector as $key => $value)
        {
            if (!isset($value) || $value === false)
                continue;
            else if ($value === true)
                $arr[] = "$key=\"$key\"";
            else if (is_array($value))
                $arr[] = $key . '="' . implode(' ', $value) . '"';
            else
                $arr[] = "$key=\"$value\"";
        }
        return implode(' ', $arr);
    }

    /**
     * 
     * @param array $config
     * @param array $events
     * @param array $data
     */
    public function __construct($config = null, $events = null, $data = null)
    {
        foreach(array('config', 'events', 'data') as $prop)
        {
            $class  =   '\\diform\\'.$prop;
            $this->$prop = new $class($this, $$prop);
        }
    }

    /**
     * Equivalent to the add method
     * @param string $type
     * @param string $name
     * @param mixed $val
     * @return \diform\control
     */
    public function __invoke($name, $type = 'text', $value = null)
    {
        $control = $this->control($type);
        $control
            ->attr('name', $name)
            ->value($value)
        ;

        $this->add($control);

        return $control;
    }

    public function control($type)
    {
        $class_default  = '\\diform\\control';
        if (class_exists($class = $class_default . '\\' . $type))
        {
            return new $class($this);
        }
        else
        {
            $control    =   new $class_default($this);
            $control->attr('type', $type);
            return $control;
        }
    }
    
    public function __call($type, $args)
    {
        $control = $this->control($type);

        if (isset($args[0]))
        {
            $control->attr('name', $args[0]);
        }
        if (isset($args[1]))
        {
            $control->value($args[1]);
        }
        $this->add($control);

        return $control;
    }

    public function add($control)
    {
        $name = $control->attr('name');
        //$this->controls[] = $control;
        if (!isset($name))
        {
            $this->controls[] = $control;
        }
        else if (preg_match('/^(.+)\[\]$/', $name, $matches))
        {
            $this->controls[$matches[1]][] = $control;
        }
        else
        {
            $this->controls[$name] = $control;
        }

        return $this;
    }

    /**
     * 
     * @param string $name
     * @return \diform\control|boolean
     */
    public function __get($name)
    {
        return isset($this->controls[$name]) ?
            $this->controls[$name] : false
        ;
    }

    /**
     * 
     * @param array|\Traversable $config
     * @return \diform\config
     */
    public function config(/* $config */)
    {
        return (func_num_args() && ($this->{__FUNCTION__}->extend(func_get_arg(0)))) ?
            $this : $this->{__FUNCTION__}
        ;
    }

    /**
     * 
     * @param array|\Traversable $data
     * @return diform\data
     */
    public function data(/* $data */)
    {
        return (func_num_args() && ($this->{__FUNCTION__}->extend(func_get_arg(0)))) ?
            $this : $this->{__FUNCTION__}
        ;
    }

    /**
     * check Validity of the form.
     * Returns true or an assoc of all errors (one by control)
     * @return boolean|array
     */
    public function checkValidity()
    {
        $errors = array();
        foreach ($this->controls as $control)
        {
            if (($name = $control->name))
            {
                if (is_string($error = $control->checkValidity()))
                {
                    $errors[$name] = $error;
                }
            }
        }

        return $errors ? : true;
    }

    public function lang(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }

    /**
     * 
     * @param boolean $return
     * @return string|diform
     */
    public function render($return = false)
    {
        $this->events->trigger('render_form', $this);

        $return && ob_start();
        extract((array) $this->config);
        include $this->config->template();
        return $return ? ob_get_clean() : $this;
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->render(true);
    }

    public function request($request)
    {
        $this->data->request($request);
        return $this;
    }
}

/**
 * 
 * @param array $config
 * @param array $events
 * @param array $data
 * @return \diform
 */
function diform($config = null, $events = null, $data = null)
{
    return new diform($config, $events, $data);
}
