<?php
class test{

    protected $a;
    
    function tt()
    {
        echo $this->a ?: 'b';
    }

}
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
$test = new test;
$test->tt();