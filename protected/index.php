<?php

namespace Ada\Core;

require_once 'vendor/autoload.php';
set_include_path(__DIR__ . '/vendor');
spl_autoload_register();

//DataSet::init(parse_ini_file('C:\OSPanel\domains\sem-tools\components\com_ak\lib\core\config.ini', true))->getCmd('author')

$arr = [
    'a' => 'aQ)(*&$LKDAS',
    'f' => '0.25',
    'i' => 5
];
[
                'array',
                'bool',
                'float',
                'int',
                'null',
                'object',
                'resource',
                'string'
            ];
$obj = Proto::init();
$obj->a = 'a';
$obj->f = '0.25';
$obj->i = '5';

$resource = fopen('C:\OSPanel\domains\project\protected\index.php', 'r');


die(var_dump(

Url::init('http://sem-tools.maxx-marketing.de/index.php?option=com_ak&controller=urlkeywordranking'),
Url::init('http://сайт.рф')

));



Ada\Core\App::init()->exec();
