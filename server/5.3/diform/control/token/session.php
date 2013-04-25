<?php
namespace diform\control\token;

class session implements storing
{
    /**
     * key name for tokens in session
     * @var string 
     */
    protected static $register_key = 'diform_tokens';
    
    /**
     * singleton
     * @var session 
     */
    protected static $instance;
    
    
    public static function instance()
    {
        return self::$instance ?: self::$instance = new self;
    }

    protected function __construct() 
    {
        session_start();
    }

    public function __destruct() 
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
        $token = $this->generate();
        
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
        if (isset($_SESSION[static::$register_key][$token]))
        {
            if ($_SESSION[static::$register_key][$token] === true)
            {
                $_SESSION[static::$register_key][$token]   =   isset($_SERVER['REQUEST_TIME']) ?
                     $_SERVER['REQUEST_TIME'] : ($_SERVER['REQUEST_TIME'] = time())   
                ;
                return true;
            }
            else
            {
                return ($_SESSION[static::$register_key][$token] === $_SERVER['REQUEST_TIME']);
            }
        }
        else
        {
            return false;
        }
    }
}