# 易极付国际易汇通API

## 安装

**安装要求**

1. php > 5.3.0

1. curl

`composer require manarch/crossborder`

## 使用

在入口文件加入`require_once __DIR__.'/vendor/autoload.php';`

* 实名认证

```
require_once __DIR__.'/vendor/autoload.php';

use Yjpay\Crossborder\Application\RealName;

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

```

* 单笔支付

```
use Yjpay\Crossborder\Payment\AggregatePay;

$guoNeiDeveloperInfo = [
  # 开发者ID
  'partnerId'=>'2016061*********8575',
  # 开发者密钥
  'secretKey'=>'6592639b*********8c2bd2500778c45',
  # 开启调试模式
  'debug'=>true,
  # 日志记录地址，默认为'/logs/',linux系统请先创建你的目录并保证目录有读写全下
  'logPath'=>'/logs/'
];

// $AggregatePay = new AggregatePay(array $DeveloperInfo ,array $parameter [, string $payType]);

// array $DeveloperInfo : [ 'partnerId','secretKey','debug' ] 开发者信息
// array $parameter : 参数
// string $payType : PC , Mobile , MobileWechat 支付终端


$AggregatePay = new AggregatePay($guoNeiDeveloperInfo,[
  'merchOrderNo'=>date('YmdHis').mt_rand(1111,9999),
  ...
  ],
  'PC'
  );

  $result = $AggregatePay->run();
```

* 合并支付

```
// $FastPayTradeMergePay = new FastPayTradeMergePay(array $DeveloperInfo ,array $parameter [, string $payType]);

// array $DeveloperInfo : [ 'partnerId','secretKey','debug' ] 开发者信息
// array $parameter : 参数
// string $payType : pc , mobile 支付终端

$tradeInfoData = [
  [
    'merchOrderNo'=>date('YmdHis').mt_rand(111111,999999),
    'sellerUserId'=>'20160617020000748575',
    'tradeAmount'=>mt_rand(10,101),
    'currency'=>'CNY',
    'goodsName'=>'test name'
  ],
  [
    'merchOrderNo'=>date('YmdHis').mt_rand(111111,999999),
    'sellerUserId'=>'20160617020000748575',
    'tradeAmount'=>mt_rand(10,101),
    'currency'=>'CNY',
    'goodsName'=>'test name'
  ]
];

$FastPayTradeMergePay = new FastPayTradeMergePay($guoNeiDeveloperInfo,
  [
    'merchOrderNo'=>date('YmdHis').mt_rand(111111,999999),
    'tradeInfo'=>json_encode($tradeInfoData),
    'returnUrl'=>'http://jbyefaun.xicp.net/returnUrl.php',
    'notifyUrl'=>'http://jbyefaun.xicp.net/notifyUrl.php',
    ...
  ]
);

$res = $FastPayTradeMergePay->run();
```

* 支付单上传

```
use Yjpay\Crossborder\Application\SinglePaymentUpload;

// $SinglePaymentUpload = new SinglePaymentUpload(array $DeveloperInfo ,array $parameter);

// array $developerInfo : [ 'partnerId','secretKey','debug' ] 开发者信息
// array $parameter : 参数

$SinglePaymentUpload = new SinglePaymentUpload($guoNeiDeveloperInfo,[
  'merchOrderNo'=>date('YmdHis').mt_rand(111111,999999),
  ...
  ]);

$result = $SinglePaymentUpload->run();
```

* 验签

```
require_once __DIR__.'/vendor/autoload.php';

use Yjpay\Crossborder\Application\Signature;

$signature = new Signature;
$res = $signature->signature('6592639bb*********c2bd2500778c45',filter_input_array(INPUT_POST));

// $signature->signature(string $secretKey, string $parameter);

// string $secretKey 密钥
// string $parameter 系统通知的数据

```
