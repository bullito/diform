<?php
include __DIR__.'/../server/5.3/diform.php';
\diform::lazyLoad();

$_POST['ee'] = '665';
var_dump($_POST);
diform(null, array(
        'invalidate_control' => function($control) {
            $control->addClass('invalid');
        }
    ))
    ->text('ee')//->form
    ->render()
;
