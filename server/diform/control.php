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
     *
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
     * @param \diform $form
     */
    public function __construct($form = null)
    {
        $this->form = $form;
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
        else if (isset($this->form->data->{$this->name}))
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
        if (isset($this->val))
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
        $this->prepare();
        
        $return && ob_start();
        $this->attrs;
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
        if ($this->form && $this->form->data->isPopulated())
        {
            $check  =   validator::checkValidity($this);
        
            if ($check !== true)
            {
                $this->invalidate($check);
            }
            
            return $check;
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
        $this->feedback("<span class=\"error\">$error</span>");
        return $this;
    }
    
    public function rules(/* $value */)
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }
    
    public function rule($rule, $func, $feedback = array('en' => 'invalid'))
    {
        assert(!empty($rule));
        assert(is_callable($func));
        
        $this->rules[$rule] =   $func;
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
        $this->addClass('invalid');
        $this->error($error);
        return $this;
    }
}
