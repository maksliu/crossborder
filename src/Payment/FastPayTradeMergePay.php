<?php
namespace Yjpay\Crossborder\Payment;

use Yjpay\Crossborder\Foundation\App;

class FastPayTradeMergePay extends App {
    private $rules = array(
        'outUserId'=>'between:1,64',
        'buyerUserId'=>'size:20',
        'buyerRealName'=>'between:1,64',
        'tradeInfo'=>'required',
        'paymentType'=>'in:BALANCE,QUICKPAY,ONLINEBANK,THIRDSCANPAY,OFFLINEPAY,PAYMENT_TYPE_SUPER,PAYMENT_TYPE_YJ,PAYMENT_TYPE_WECHAT,PAYMENT_TYPE_UPMP',
        'userTerminalType'=>'in:PC,MOBILE',
        'buyerOrgName'=>'max:180',
        'behavior'=>'between:0,255',
        'mainTradeNo'=>'max:180',
        'customerNo'=>'max:180',
        'merchOrderNo'=>'required|between:16,40',
        'returnUrl'=>'required|max:180',
        'notifyUrl'=>'required|max:180',
    );

    private $PCrules = array(
        'paymentType'=>'in:BALANCE,QUICKPAY,ONLINEBANK,THIRDSCANPAY,OFFLINEPAY',
        'userTerminalType'=>'required|in:PC',
    );

    private $MobileRules = array(
        'paymentType'=>'in:PAYMENT_TYPE_SUPER,PAYMENT_TYPE_YJ,PAYMENT_TYPE_WECHAT,PAYMENT_TYPE_UPMP',
        'userTerminalType'=>'required|in:MOBILE',
    );

    private $tradeInfoRules = array(
        'merchOrderNo'=>'required|between:1,64',
        'tradeName'=>'between:0,64',
        'sellerUserId'=>'required|between:0,20',
        'tradeAmount'=>'required|numeric',
        'currency'=>'required|in:CNY',
        'goodsTypeCode'=>'between:1,64',
        'goodsTypeName'=>'between:1,64',
        'goodsName'=>'required|between:1,64',
        'memo'=>'between:0,128',
        'shareProfits'=>'min:0',
        'sellerOrgName'=>'between:0,128',
        'autoCloseDuration'=>'integer'
    );

    private $tradeInfoErrorType = true;

    private $data = [
        'service'=>'fastPayTradeMergePay',
        'version'=>'2.0'
    ];

    private $PCdata = [
        'service'=>'fastPayTradeMergePay',
        'version'=>'2.0',
        'userTerminalType'=>'PC'
    ];

    private $MobileData = [
        'service'=>'fastPayTradeMergePay',
        'version'=>'2.0',
        'userTerminalType'=>'MOBILE'
    ];



    public $service = 'fastPayTradeMergePay';

    public function __construct(array $developerInfo, array $parameter, $payType = ''){
        $developerInfo['service'] = $this->service;
        switch ($payType) {
          case 'pc':
            $this->pcPay($developerInfo,$parameter);
            break;

          case 'mobile':
              $this->MobilePay($developerInfo,$parameter);
            break;

          default:
            $this->pay($developerInfo,$parameter);
            break;
        }
    }

    private function validationTradeInfo(){

        if(isset($this->data['tradeInfo'])){
            $tradeInfoData = json_decode($this->data['tradeInfo'],1);
            if($this->tradeInfoErrorType !== true){
                return false;
            }
            foreach ($tradeInfoData as $key => $value) {
                $this->tradeInfoErrorType = $this->validationA($value,$this->tradeInfoRules);
            }
        }

    }

    private function pay($developerInfo,$parameter){
       $this->data = array_merge($this->data, $parameter);
       parent::__construct($developerInfo, $this->data ,$this->rules);
    }

    private function pcPay($developerInfo,$parameter){
       $this->data = array_merge($this->PCdata, $parameter);
       $this->rules = array_merge($this->rules, $this->PCrules);
       parent::__construct($developerInfo, $this->data ,$this->rules);
    }

    private function MobilePay($developerInfo,$parameter){
       $this->data = array_merge($this->MobileData, $parameter);
       $this->rules = array_merge($this->rules, $this->MobileRules);
       parent::__construct($developerInfo, $this->data ,$this->rules);
    }

    public function run(){
        $this->validationTradeInfo();
        if($this->tradeInfoErrorType !== true ){
          return $this->tradeInfoErrorType;
        }
        return $this->send('form');
    }


}
