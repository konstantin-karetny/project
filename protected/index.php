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
$obj = new Proto();
$obj->a = 'a';
$obj->f = '0.25';
$obj->i = '5';

$resource = fopen(__FILE__, 'r');


$set = File::init('C:\OSPanel\domains\sem-tools\components\com_ak\lib\core\config.ini')->parseIni();
$set->unset('reply[returns][0]');


//http://project/sub/sub/index.php/sub?option=com_ak&controller=urlkeywordranking#frag//

die(var_dump(

Url::init(),
Url::init('http://sem-tools.maxx-marketing.de/index.php?option=com_ak&controller=urlkeywordranking#frag//'),
Url::init('http://сайт.рф')

));



Ada\Core\App::init()->exec();
