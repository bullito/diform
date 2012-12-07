<?php
/**
 * Description of testinvoke
 *
 * @author ble
 */
class testinvoke {
    //put your code here
    public function __invoke() {
        echo 'hello';
        return $this;
    }
    public function _()
    {
        
    }
}

class test {
    public function __call($name, $arguments) {
        $name = $this->$name;
        $name($arguments);
    }
    public $rr;
    
    public function __construct()
    {
        $this->rr = new testinvoke();
    }
}
$test = new test();
//$test->rr = new testinvoke();
$test->rr();
?>


<?

$inv = new testinvoke;
$inv()->__invoke();