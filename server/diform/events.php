<?php

namespace diform;

/**
 * Description of event
 *
 * @author b.le
 */
class events extends extendable
{
    protected $render_form = array();
    protected $render_control = array();
    protected $invalidate_form = array();
    protected $invalidate_control = array();
    
    public function __construct(\diform $diform, $events = null)
    {
        $this->_diform = $diform;
        
        if ($events)
            foreach($events as $event => $callbacks)
                if (isset($this->$event))
                    $this->$event =   $callbacks;
    }
    
    public function on($event, $callback)
    {
        if (isset($this->$event))
            $this->$event[] =   $callback;
        
        return $this;
    }
    
    public function trigger($event, $instance)
    {
        foreach($this->$event as $callback)
            $callback($instance);
        
        return $this;
    }
}