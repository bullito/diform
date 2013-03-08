<?php
include './dot.php';

$form = diform(
    array(
    'lang' => 'fr',
    'form' => array(
        'name' => 'test',
        'novalidate' => true
    ),
    'controls' => array(
        'test' => array(
            'label' => 'test',
            'type' => 'textarea',
            'placeholder' => 'type here',
            'required' => true,
            'pattern' => '\d+'
        ),
        'mail' => array(
            'type' => 'email'
        ),
        'submit' => array(
            'type' => 'submit'
        )
    )
    ), 
    $_POST, 
    array(
        'on_invalidate_control' => array(
            function($control)
            {
                $control->addClass('invalid');
            }
        )
    )
);
?>
<style type="text/css">
    .invalid {
        border: 1px solid red;
    }

    .error {
        color: red;
    }
</style>


<?

$form->render();

var_dump($form->checkValidity());