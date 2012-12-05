<?php
include dirname(__DIR__).'/server/diform.php';

$config = array(
    'form' => array(
        'name' => 'er',
        'method' => 'get',
        'action' => ''
    ),
    'tip' => '?',
    'legend'    =>  'testlegend',
    'inputs' => array(
        'test' => array(
            'value' => 'default',
            'attrs' => array(
                'class' => 'truc'
            ),
            'tip' => 'test tip'
        )
    )
);

$data = array('test' => 3);

$form = new diform($config, $data);
?>

<?//= $form ?>

<?
$m[] = microtime(true);

?>
<?=

$ff = diform()
    ->string(
        'test[trux]', 
        'ceci est un label', 
        array('class' => 'ee')
    )
    ->text(
        'test2', 'ceci est un test', 
        array(
            'value' => 'contenu', 
            'attrs' => array('required', 'cols' => 60))
    )
    ->radio('test3', 'test radio', array('attrs' => array('class' => 'ii', 'value' => 'myval')))
    ->radios('test4[test]', 'ee', array(
        'tip' => 'test',
        'choices' => array('a', 'b', 'c'), 
        'attrs' => array('class' => 'rr', 'required'))
    )
    ->select('test5[test][test]', '', array('choices' => array('', 'test')))
    ->select('test5_2', '', array(
        'choices' => array('', 'testss'), 
        'attrs'     =>  array('multiple')
    ))
    
    ->checkboxes('test6', 'dfd', array('choices' => array('a', 'B' => 'b', 'c')))
    ->checkboxes('test7', 'dfd', array('choices' => array('a', 'B' => 'b', 'c'), 'attrs' => array('multiple')))
    ->submit('submit', '', array('value' => 'valider'))
    ->data($inject = $_POST ?: array(
        'test' => array(
            'trux' => 'yesyes'
        ),
        'test3' => 'myval',
        'test4' => array(
            'test' => 'b'
        ),
        'test5' => array('test' => array('test' => 'test'))
    ))
?>
<?
$m[] = microtime(true);
?>
<pre><? var_dump($m, $inject); ?></pre>