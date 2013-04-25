<?php
namespace diform\control\token;

interface storing
{
    public static function instance();
    public function generate(); 
    public function add(); 
    public function values(); 
    public function remove($token);
}