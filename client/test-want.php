<?php
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

include dirname(__DIR__).'/server/5.3/diform.php';
diform::lazyLoad();

$i = new \diform\control\textarea;
$i
    ->name('test')
    ->label('ee')
    ->required()
    ->{'data-at-least'}(3)
    ->required(false)
    ->val(22)
;
$f = new diform(
    array('form'=>array('method'=>'post')), 
    $_POST ?: array(
        'testselect' => 2,
        'testselectmultiple' => array(1,3)
    )
);


$f->add($i);
$f->render();
$f->datetime('zkef')->label('zkef')->tip('test');
$f->date('ehjfz')->title("test'er");
$f->time('tehqefb');
$f->radios('hvvgh')->choices(array(1,2,3))->required();
//$f->checkbox('rr[]');
$f->select('testselectmultiple')->multiple()->choices(array('--select--'=>'', 'a'=>1, 2, 3, 'b'=>4));
$f->select('testselect')->required()->choices(array('--select--'=>'', 'a'=>1, 2, 3, 'b'=>4));
$f->checkboxes('cbs')->required()->choices(array('aa' => 'aaa', 'bb'));
$f->submit()->val('ok');
$f->render();
?>
<form>
    <input <?= $f('test[qsf][sdf]')->required()->attrs ?>/>
    <input <?= $f('test2[]', 'checkbox')->value('dd')->val('dd')->attrs ?> />
    <?= $f->textarea('ee', 'zzz')->required()->val('sss') ?> 
    <?= $f->test2[0] ?> 
    <?= $i ?> 
    <?= $f->submit() ?> 
    <?= $f->{'test[qsf][sdf]'}->class('clla ggfh')->addClass('df')->removeClass('clla')->value('eee') ?> 
</form>
<?

//var_dump($f);
