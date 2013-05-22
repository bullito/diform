<?php
namespace diform;

/**
 * Description of control
 *
 * @author ble
 * @property mixed $value
 * @property string $type
 * @property string $name
 * 
 */
class control
{
    /**
     * \diform instance whom control is attached to
     * @var \diform 
     */
    protected $form;
    
    /**
     *
     * @var string 
     */
    protected $tag = 'input';
    
    /**
     *
     * @var string 
     */
    protected $content;
    
    /**
     *
     * @var mixed 
     */
    protected $val;
    
    /**
     *
     * @var string 
     */
    protected $label;
    
    /**
     *
     * @var string 
     */
    protected $tip;
    
    /**
     *
     * @var string 
     */
    protected $feedback;
    
    /**
     *
     * @var assoc 
     */
    protected $attributes = array();
    
    /**
     *
     * @var string 
     */
    protected $name;
    
    /**
     *
     * @var array 
     */
    protected $rules = array();

    /**
     *
     * @var bool 
     */
    protected $checkValidity;
    
    /**
     *
     * @var synchronize val and attributes[value] 
     */
    protected $sync_value = true;
    
    /**
     * 
     * @param \diform $form
     */
    public function __construct($form = null)
    {
        $this->form = $form;
        
        foreach($this->attributes as $attr => $value)
        {
            $this->attr($attr, $value);
        }
    }
    

    /**
     * Return method result or attribute
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return method_exists($this, $name) ? 
            $this->$name() : 
            (isset($this->attributes[$name]) ?
                $this->attributes[$name] : 
                null
            )
        ;
    }

    public function __call($name, $args)
    {
        unset($this->attrs);

        if (!count($args))
        {
            $this->attr($name, true);
        }
        else if ($name == 'class')
        {
            $this->attr($name, array_filter(array_unique(
                explode(' ', $args[0])
            )));
        }
        else
        {
            $this->attr($name, $args[0]);
        }

        return $this;
    }

    public function attr($name /* , $value = null */)
    {
        if (preg_match('/[^a-zA-Z0-9_-]/', $name))
        {
            throw new exception(__METHOD__.": attribute name '$name' - wrong format");
        }
        
        if (func_num_args() == 2)
        {
            unset($this->attrs);
            $value  =   func_get_arg(1);
            
            $this->attributes[$name] = $value;
            
            switch($name)
            {
            case 'name':
                $this->name = preg_match('/^(.+)\[\]$/', $value, $matches) ?
                    $matches[1] : $value
                ;
                break;
            }
            
            if ($value === false)
            {
                unset($this->rules["[$name]"]);
            }
            else if (($rule = validator::rules4Attribute($name)))
            {
                $this->rules[$rule['matcher']]  =   true;
            }
            
            return $this;
        }
        else
        {
            return isset($this->attributes[$name]) ?
                $this->attributes[$name] : null;
        }
    }

    public function has_multiple()
    {
        return $this->has_multiple =   isset($this->attributes['multiple']) 
            && $this->attributes['multiple']
        ;
    }

    public function attrs()
    {
        $this->populate();
        $this->checkValidity();
        return $this->attrs = \diform::attrs($this->attributes);
    }

    public function label(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }
    
    public function tip(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }
    
    public function val(/* $value */)
    {
        if (func_num_args() == 1)
        {
            $this->val = func_get_arg(0);
            return $this;
        }
        else if (isset($this->val))
        {
            return $this->val;
        
        }
        else if (isset($this->name) && ($this->form->data->isPopulated() || true) && isset($this->form->data->{$this->name}))
        {
            return $this->val = $this->form->data->{$this->name};
        }
        else
        {
            return null;
        }
    }

    public function populate()
    {
        $this->val();
        
        if ($this->sync_value && isset($this->val))
        {
            $this->attributes['value'] = $this->val;
        }

        return $this;
    }

    public function prepare()
    {
        return $this;
    }

    public function render($return = false)
    {
        $this->attrs;
        $this->form->events->trigger('render_control', $this);
        $this->prepare();
        
        $return && ob_start();
        $has_tag = isset($this->tag);
        $has_content = isset($this->content);
        
        if ($has_tag)
        {
            echo '<', $this->tag;
            if ($this->attrs)
            {
                echo ' ', $this->attrs;
            }
            if ($has_content)
            {
                echo '>';
            }
        }

        if ($has_content)
        {
            echo $this->content;
        }

        if ($has_tag)
        {
            if ($has_content)
            {
                echo '</', $this->tag, '>';
            }
            else
            {
                echo ' />';
            }
        }

        return $return ? ob_get_clean() : $this;
    }

    public function __toString()
    {
        return $this->render(true);
    }

    public function checkValidity()
    {
        if (isset($this->checkValidity))
            return $this->checkValidity;
        
        if ($this->form && $this->form->data->isPopulated())
        {
            $check  =   validator::checkValidity($this);
        
            if ($check !== true)
            {
                $this->invalidate($check);
            }
            
            return ($this->checkValidity = $check);
        }
        
        return true;
    }
    
    /**
     * @todo externalize error template
     * @param string $error
     * @return \diform\control
     */
    public function error($error)
    {
        static $error_decorator = false;
        $error_decorator or $error_decorator = $this->form->config->error_decorator;
        
        $this->feedback(str_replace('${error}', $error, $error_decorator));
        return $this;
    }
    
    public function rules(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }
    
    public function rule($name, $check, $feedback = null)
    {
        assert(!empty($name));
        assert(is_callable($check) or function_exists($check));
        unset($this->checkValidity);
        $this->rules[$name] =   compact('name', 'check', 'feedback');
        return $this;
    }
    
    public function addClass($class)
    {
        if (!isset($this->attributes['class']))
        {
            $this->attributes['class'][] = $class;
        }
        if (is_string($this->attributes['class']))
        {
            $this->attributes['class'] = (array) $this->attributes['class'];
        }
        if (!in_array($class, $this->attributes['class']))
        {
            $this->attributes['class'][] = $class;
        }
        return $this;
    }

    public function removeClass($class)
    {
        if (!is_array($this->attributes['class']))
        {
            $this->attributes['class'] = (array) $this->attributes['class'];
        }

        foreach ($this->attributes['class'] as $rnk => $nclass)
        {
            if ($nclass == $class)
            {
                unset($this->attributes['class'][$rnk]);
            }
        }

        return $this;
    }

    public function form(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }

    public function attributes(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }

    public function feedback(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }
    
    public function invalidate($error = '')
    {
        $this->form->events->trigger('invalidate_control', $this);
        $this->error($error);
        return $this;
    }
    
    /**
     * @param array|object|\Traversable $batch
     * @return \diform\control
     */
    public function batch($batch)
    {
        if ($batch)
        {
            foreach($batch as $method => $arg)
            {
                $this->$method($arg);
            }
        }
        
        return $this;
    }
}
