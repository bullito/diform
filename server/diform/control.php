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

    protected $form;
    protected $tag = 'input';
    protected $content;
    protected $val;
    protected $label;
    protected $feedback;
    protected $attributes = array();
    protected $name;

    public function __construct($form = null)
    {
        $this->form = $form;
    }

    public function __get($name)
    {
        if (method_exists($this, $name))
        {
            return $this->$name();
        }
        else
        {
            return isset($this->attributes[$name]) ?
                $this->attributes[$name] : null;
        }
    }

    public function __call($name, $args)
    {
        unset($this->attrs);

        if (!count($args))
        {
            $this->attributes[$name] = true;
        }
        else if ($name == 'class')
        {
            $this->attributes[$name] = array_filter(array_unique(
                    explode(' ', $args[0])
                ));
        }
        else
        {
            $this->attributes[$name] = $args[0];
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
        return $this->attrs = \diform::attrs($this->attributes);
    }

    public function label(/* $value */)
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
        $this->render();
        return '';
    }

    public function checkValidity()
    {
        $o  =   new \stdClass();
        
        return $o;
    }
    

    public function addClass($class)
    {
        if (!is_array($this->attributes['class']))
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

    public function form()
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }

    public function attributes()
    {
        return (func_num_args() && (($this->{__FUNCTION__} = func_get_arg(0)) || true)) ?
            $this : $this->{__FUNCTION__}
        ;
    }

}
