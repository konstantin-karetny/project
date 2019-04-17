<?php

namespace Ada\Core;

require_once 'vendor/autoload.php';
set_include_path(__DIR__ . '/vendor');
spl_autoload_register();

die(var_dump(new \SplString('asdf')));

die(var_dump(Type\Str::init('asfda')->pos('asdf')));

die(var_dump(

Type\UInteger::init('safdaas-fd1-asdf5-')

));


die(var_dump(

    DataSet::init([
        'val1' => 1,
        'val2' => 'as!@#(**fd'
    ])->getInteger(Type\Cmd::init('val2'))

));



Ada\Core\App::init()->exec();
