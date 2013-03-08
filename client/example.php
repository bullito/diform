<?php
include dirname(__DIR__) . '/server/diform.php';
diform::lazyLoad();
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <? var_dump($_POST); ?>
        
        <?
        $f = diform(['form' => ['name' => 'test', 'method' => 'post', 'novalidate' => 'novalidate']])
            ->data($_POST)
            ->lang('fr')
            ->email('test[a]')
            ->form
            ->text('test[c]')
                ->label('test.c')
                ->required()
                ->form
            ->select('test[d]')->choices(['a', 'b', 'C' => 'c'])->form
            ->submit()->form;
        $f('test[d]', 'radios')->choices(['a', 'b', 'c'])->required();    
        
        ?>
        
        <?= $f ?>
        
    </body>
</html>
