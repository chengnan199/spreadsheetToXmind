<?php

require './vendor/autoload.php';

$fileName = './测试.xlsx';
$path = './服务端11.xmind';
(new \src\controller\Xmind())->index($fileName,$path);
