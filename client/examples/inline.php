<?php
include './dot.php';

$form  =   diform()->request($_POST);

?>


<form action="" <?= diform::attrs($form->config->form) ?> novalidate="">
    <p>
        <label>test <?= $form->text('test')->required()->pattern('[a-z]+') ?></label>
        <?= $form->test->feedback ?>
    </p>
    <p>
        <label>test2 <?= $form->textarea('test2') ?></label>
    </p>
    <p><label><input <?= $form->email('test3')->attrs() ?> /></label>
        <?= $form->test3->feedback ?>
    </p>
    
    <p><label>texte <?= $form('test4') ?></label></p>
    
    <p><label>date <?= $form('test_date', 'date') ?></label>
        <?= $form->test_date->feedback ?>
        <?= $form->test_date->seconds()  ?>
    </p>
    
   <p> <?= $form->submit()->val('go') ?></p>
</form>
<?= (int) ($form->checkValidity() === true)  ?>