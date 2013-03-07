<?php

/**
 * Description of diform
 *
 * @author ble
 * @method \diform\control\text text(string $name, mixed $value) add a textfield to form
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
     * 
     * @param type $control
     * @param type $name
     * @param type $val
     * @return \class
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
    
    public function __get($name)
    {
        if (isset($this->controls[$name]))
        {
            return $this->controls[$name];
        }
        
        return false;
    }

    public function __toString()
    {
        return $this->render(true);
    }
    
    public function render($return = false) {
        $this->prepare();
        $return && ob_start();
        extract((array) $this->config);
        include $this->config->template();
        return $return ? ob_get_clean() : $this;
    }
    
    public function data($name)
    {
        return '';
    }
    
    public function prepare()
    {
        return $this;
    }
    
    public function checkValidity()
    {
        $o  =   array();
        foreach($this->controls as $control)
        {
            if (($name = $control->name))
            {
                $o[$name]   =   (($validity = $control->checkValidity()) === true) ? false : $validity;
            }
        }
        
        return array_filter($o) ?: true;
    }
}


function diform($config = null, $data = null)
{
    return new diform($config, $data);
}