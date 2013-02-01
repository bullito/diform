<?php
include './_dot.php';

$form   =   new diform();

$form->data($_POST);

$form->text('test[req]')->required();
$form->submit('ee')->val('valider') ;

$form->checkValidity();
ob_start();
?>
<form action="" novalidate="novalidate" method="post">
    <input <?= $form->{'test[req]'}->attrs ?>/> <p><?= $form->{'test[req]'}->feedback ?></p>
    <?= $form->ee ?>
</form>
<?
$html   = ob_get_clean();
?>
<?= $html ?>