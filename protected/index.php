<?php

namespace Ada\Core;

require_once 'vendor/autoload.php';
set_include_path(__DIR__ . '/vendor');
spl_autoload_register();





die(var_dump(

    Check::bool('off')

));



Ada\Core\App::init()->exec();
