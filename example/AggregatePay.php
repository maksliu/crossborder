<?php
require_once __DIR__.'/vendor/autoload.php';

use Yjpay\Yht\Payment\AggregatePay;


$guoNeiDeveloperInfo = [
  # 开发者ID
  'partnerId'=>'2016061*********8575',
  # 开发者密钥
  'secretKey'=>'6592639b*********8c2bd2500778c45',
  # 开启调试模式
  'debug'=>true,
  # 日志记录地址，不填默认为'/logs/'；请先创建你的目录并保证目录有读写全下
  'logPath'=>'/logs/'
];

########### PC端支付 ###########
$AggregatePay = new AggregatePay($guoNeiDeveloperInfo,[
  'merchOrderNo'=>date('YmdHis').mt_rand(1111,9999),
  'goodsName'=>'this is a test goods',
  'sellerUserId'=>$guoNeiDeveloperInfo['partnerId'],
  'tradeAmount'=>12.31,
  'returnUrl'=>'http://exdomain.com/returnUrl.php',
  'notifyUrl'=>'http://exdomain.com/notifyUrl.php',
  'memberType'=>'MEMBER_TYPE_YIJI'
]);

$formString = $AggregatePay->run();
