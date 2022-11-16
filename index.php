<?php

require './vendor/autoload.php';

$fileName = './老年大学教务平台服务端.xlsx';
$path = './服务端11.xmind';
(new \src\controller\Xmind())->index($fileName,$path);
