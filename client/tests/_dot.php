<?php
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

include dirname(dirname(__DIR__)).'/server/diform.php';
diform::lazyLoad();

