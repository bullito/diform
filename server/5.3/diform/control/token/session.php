<?php
namespace diform\control\token;

class session implements storing
{
    protected static $register_key = 'diform_tokens';
    protected static $instance;
    
    public function instance()
    {
        return self::$instance ?: self::$instance = new self;
    }

    protected function __construct() 
    {
        session_start();
    }

    protected function __destruct() 
    {
        foreach($this->values() as $token => $val)
        {
            if ($val !== true)
            {
                $this->remove($token);
            }
        }
    }
    
    public function generate()
    {
        return uniqid('diform_token_', true);
    }

    
    public function add() 
    {
        $token = static::generate();
        
        $_SESSION[static::$register_key][$token]  =   true;
        
        return $token;
    }
    
    public function remove($token)
    {
        unset($_SESSION[static::$register_key][$token]);
    }
    
    /**
     * 
     * @return array
     */
    public function values()
    {
        return $_SESSION[static::$register_key];
    }
    
    public function check($token)
    {
        if (isset($_SESSION[$token]))
        {
            if ($_SESSION[$token] === true)
            {
                $_SESSION[$token]   =   isset($_SERVER['REQUEST_TIME']) ?
                     $_SERVER['REQUEST_TIME'] : ($_SERVER['REQUEST_TIME'] = time())   
                ;
                return true;
            }
            else
            {
                return ($_SESSION[$token] === $_SERVER['REQUEST_TIME']);
            }
        }
        else
        {
            return false;
        }
    }
}