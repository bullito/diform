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
    public $data;
    
    public static function lazyLoad($boo = true)
    {
        if ($boo)
        {
            spl_autoload_register(array(__CLASS__, 'loadClass'));
        }
        else
        {
            spl_autoload_unregister(array(__CLASS__, 'loadClass'));
        }
    }

    public static function loadClass($class)
    {
        $path = self::PATH . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($path))
            include $path;
    }

    public static function attrs($vector)
    {
        $arr = array();
        foreach ($vector as $key => $value)
        {
            if (!isset($value) || $value === FALSE)
            {
                continue;
            }
            else if ($value === TRUE)
            {
                $arr[] = "$key=\"$key\"";
            }
            else if (is_array($value))
            {
                $arr[] = $key.'="'.implode(' ', $value) . '"';
            }
            else
            {
                $arr[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $arr);
    }

    public function __construct($config = null, $data = null)
    {
        
        $this->config   =   new \diform\config($config);
        $this->data     =   new \diform\data($data);
    }

    /**
     * Equivalent to the add method
     * @param string $control
     * @param string $name
     * @param mixed $val
     * @return \diform\control
     */
    public function __invoke($name, $control = 'text', $value = null)
    {
        $class = '\\diform\\control\\' . $control;
        $element = new $class($this);
        $element
            ->attr('name', $name)
            ->value($value)
        ;

        $this->add($element);

        return $element;
    }

    public function __call($control, $args)
    {
        $class = '\\diform\\control\\' . $control;
        $element = new $class($this);
        
        if (isset($args[0]))
        {
            $element->attr('name', $args[0]);
        }
        if (isset($args[1]))
        {
            $element->value($args[1]);
        }
        $this->add($element);
        
        return $element;
    }
    
    public function add($control)
    {
        $name   =   $control->attr('name');
        //$this->controls[] = $control;
        if (!isset($name))
        {
            $this->controls[] = $control;
        }
        else if (preg_match('/^(.+)\[\]$/', $name, $matches))
        {
            $this->controls[$matches[1]][]  =   $control;
        }
        else
        {
            $this->controls[$name]  =   $control;
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
     * @return string
     */
    public function __toString()
    {
        return $this->render(true);
    }
    
    /**
     * 
     * @param boolean $return
     * @return string|diform
     */
    public function render($return = false) {
        $this->prepare();
        $return && ob_start();
        extract((array) $this->config);
        include $this->config->template();
        return $return ? ob_get_clean() : $this;
    }
    
    /**
     * 
     * @param array|\Traversable $data
     * @return diform\data
     */
    public function data($data = null)
    {
        return $data ? 
            $this->data->extend($data) : 
            $this->data
        ;
    }
    
    public function prepare()
    {
        return $this;
    }
    
    /**
     * check Validity of the form.
     * Returns true or an assoc of all errors (one by control)
     * @return boolean|array
     */
    public function checkValidity()
    {
        $errors  =   array();
        foreach($this->controls as $control)
        {
            if (($name = $control->name))
            {
                if (is_string($error = $control->checkValidity()))
                {
                    $errors[$name]   =   $error;
                }
            }
        }
        
        return $errors ?: true;
    }
    
    public function lang(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }
}

/**
 * 
 * @param array $config
 * @param array $data
 * @return \diform
 */
function diform($config = null, $data = null)
{
    return new diform($config, $data);
}