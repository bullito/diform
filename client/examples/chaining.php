<?php

include './dot.php';
?>
<?= memory_get_peak_usage() ?>
<?=

    diform()
    ->request($_POST)
    ->file('test[a][a]')->form
    ->range('test[b]')->min(2)->max(5)->form
    ->submit()->form
?>

<?= memory_get_peak_usage() ?>

<?= memory_get_usage() ?>