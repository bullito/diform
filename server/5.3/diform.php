<?php
/**
 * Description of diform
 * @author Buu-Lâm LÊ buulam.le[at]gmail.com
 * 
 * @method \diform|\diform\control\radio radio(string $name, mixed $value) add a radio button to form
 * @method \diform|\diform\control\radios radios(string $name, mixed $value) add radios with the same name to form
 * @method \diform|\diform\control\select select(string $name, mixed $value) add a drop list to form
 * @method \diform|\diform\control\submit submit(string $name = 'token') add a submit to form
 * @method \diform|\diform\control\text text(string $name, mixed $value) add a textfield to form
 * @method \diform|\diform\control\textarea textarea(string $name, mixed $value) add a textarea to form
 * @method \diform|\diform\control\time time(string $name, mixed $value) add a timefield to form
 * @method \diform|\diform\control\token token(string $name = 'token') add a token to form
 * 
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
     * allow chaining inputs or not
     * @var bool 
     */
    protected $_chained = false;
    
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

    /**
     * autoload method
     * @param string $class
     */
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
            if (in_array($value, array(null, '', false, array()), true))
                continue;
            else if ($value === true)
                $value = $key;
            else if (is_array($value))
                $value = implode(' ', array_map(array(get_class(), 'escape'), $value));
            else
                $value = static::escape($value);
            
            $arr[] = "$key=\"$value\"";
        }
        return implode(' ', $arr);
    }

    /**
     * prepares attribute value to be rendered (codeigniter style)
     * @param string $value
     * @return string
     */
    public static function escape($value)
    {
        return str_replace(array("'", '"'), array('&#39;', '&quot;'), stripslashes($value));
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
     * Enables/disables chaining inputs declaration
     * @param type $boo
     * @return \diform
     */
    public function chain($boo = true)
    {
        $this->_chained = $boo;
        
        return $this;
    }
    /**
     * Alternate way to the add a control
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return \diform
     */
    public function __invoke($name, $type = 'text', $value = null, $batch = null)
    {
        return $this->add(
            $this->control($type)
                ->attr('name', $name)
                ->val($value)
                ->batch($batch)
        );
    }

    /**
     * instanciate new \diform\control by type
     * @param string $type
     * @return \diform\control
     */
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
    
    /**
     * Alternate way to the add a control
     * @tutorial hh
     * @param string $type
     * @param args $args
     * @return \diform|\diform\control
     */
    public function __call($type, $args)
    {
        $control = $this->control($type);
        $class      =   get_class($control);
        
        foreach($args as $rank => $arg)
        {
            if (isset($class::$args[$rank]))
            {
                $control->{$class::$args[$rank]}($arg);
            }
        }
        
        return $this->add($control);
    }

    /**
     * Add a control to the form
     * @param \diform\control $control
     * @return \diform|\diform\control
     */
    public function add($control)
    {
        $name = $control->attr('name');
        
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
    
        return $this->_chained ? $this : $control;
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
     * set/get \diform\config instance
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
     * set/get \diform\data instance
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

    /**
     * set/get lang (en, fr, ...)
     * @return type
     */
    public function lang(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }

    /**
     * 
     * @param bool $return
     * @return string|\diform
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

    /**
     * 
     * @param array $request
     * @return \diform
     */
    public function request($request)
    {
        $this->data->request($request);
        return $this;
    }
    
    /**
     * add a trigger event to form
     * @param string $event
     * @param callable $callback
     * @return \diform
     */
    public function on($event, $callback)
    {
        $this->events->on($event, $callback);
        return $this;
    }
    
    /**
     * Returns controls of form
     * @return array
     */
    public function controls()
    {
        return $this->controls;
    }
}

if (!function_exists('diform'))
{
    /**
     * diform instanciation wrapper
     * @param array $config
     * @param array $events
     * @param array $data
     * @return \diform
     */
    function diform($config = null, $events = null, $data = null)
    {
        return new diform($config, $events, $data);
    }
}

