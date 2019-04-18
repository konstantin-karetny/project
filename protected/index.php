<?php

namespace Ada\Core;

require_once 'vendor/autoload.php';
set_include_path(__DIR__ . '/vendor');
spl_autoload_register();

//DataSet::init(parse_ini_file('C:\OSPanel\domains\sem-tools\components\com_ak\lib\core\config.ini', true))->getCmd('author')

$arr = [
    'a' => 'a',
    'f' => 0.25,
    'i' => 5
];

$obj = Proto::init();
$obj->a = 'a';
$obj->f = 0.25;
$obj->i = 5;

$resourse = fopen('C:\OSPanel\domains\project\trunk\protected\index.php', 'r');

die(var_dump(

    Type::str($resourse)

));



Ada\Core\App::init()->exec();
