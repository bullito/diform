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
        'test[zz]' => array(
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
            'type' => 'file',
        ),
        'submit' => array(
            'type' => 'submit'
        )
    )
    ), 
    null, 
    array(
        'invalidate_control' => array(
            function($control)
            {
                $control->addClass('invalid');
            }
        )
    )
)->request($_POST);
?>
<style type="text/css">
    .invalid {border: 1px solid red;}
    .error {color: red;}
</style>

<?= $form ?>

<script type="text/javascript" src="../js/jquery/1.7.1/min.js"></script>
<script type="text/javascript" src="http://cdn.jquerytools.org/1.2.7/form/jquery.tools.min.js"></script>
<script type="text/javascript">
        $.tools.validator.messages  =   <?= json_encode(\diform\validator::feedbacks()) ?>;
        $.tools.validator.addEffect('next-td', function(errors, event) {
            $.each(errors, function(index, error) {
                $(error.input).addClass('invalid').closest('td').next().html('<?= $form->config->error_decorator ?>'.replace(/\{\$error\}/g, error.messages[0]));
            });
        }, function(inputs) {
            $.each(inputs, function(index, input) {
                $(input).removeClass('invalid').closest('td').next().html('');
            });
        });
</script>
<script type="text/javascript">
    $('form').validator({lang:'fr', effect: 'next-td'});
</script>