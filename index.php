<?php

require('main.php');

Nv2\Lib\Nv2\Core\Module::instance()
    ->locale('fre-FR')
    ->environment('dev')
    ->execute();