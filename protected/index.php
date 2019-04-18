<?php

namespace Ada\Core;

require_once 'vendor/autoload.php';
set_include_path(__DIR__ . '/vendor');
spl_autoload_register();

//DataSet::init(parse_ini_file('C:\OSPanel\domains\sem-tools\components\com_ak\lib\core\config.ini', true))->getCmd('author')
$var = 'sadf';
settype($var, 'resource');
die(var_dump($var));

die(var_dump(

Clean::cmds([[]])


));



Ada\Core\App::init()->exec();
