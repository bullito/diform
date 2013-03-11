<?php
include './dot.php';

$form = diform(
    array(
    'lang' => 'fr',
    'form' => array(
        'name' => 'test',
        //'novalidate' => true
    ),
    'controls' => array(
        'test' => array(
            'label' => 'test',
            'type' => 'textarea',
            'placeholder' => 'type here',
            'pattern' => '\d+',
            'required' => true,
        ),
        'mail' => array(
            'type' => 'email'
        ),
        'ee' => array(
            'pattern' => '\d+',
        ),
        'submit' => array(
            'type' => 'submit'
        )
    )
    ), 
    null, 
    array(
        'on_invalidate_control' => array(
            function($control)
            {
                $control->addClass('invalid');
            }
        )
    )
)->request($_POST);
?>
<style type="text/css">
    .invalid {
        border: 1px solid red;
    }

    .error {
        color: red;
    }
</style>
<?= $form->data->isPopulated() ?>

<?

$form->render();

var_dump($form->checkValidity());