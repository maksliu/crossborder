<?php
require_once __DIR__.'/vendor/autoload.php';

use Yjpay\Yht\Application\RealName;

$globalDeveloperInfo = [
  # 开发者ID
  'partnerId'=>'2014092*********8373',
  # 开发者密钥
  'secretKey'=>'2af0376a*********1ab889384a8ade9',
  #开启调试模式
  'debug'=>true,
  # 日志记录地址，默认为'/logs/',linux系统请先创建你的目录并保证目录有读写全下,不传此参数默认为'/logs/'
  'logPath'=>'/logs/'
];

$RealName = new RealName($globalDeveloperInfo,
  [
    'merchOrderNo'=>date('YmdHis').mt_rand(1111,9999),
    'realName'=>'王麻子',
    'certNo'=>'500100198301253212'
  ]
);

$result = $RealName->run();
var_dump($result);
