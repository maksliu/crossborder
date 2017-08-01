<?php
namespace Yjpay\Crossborder\Payment;

use Yjpay\Crossborder\Foundation\App;

class FastPayTradeMergePay extends App {
    private $rules = array(
        'outUserId'=>'between:1,64',
        'buyerUserId'=>'size:20',
        'buyerRealName'=>'between:1,64',
        'tradeInfo'=>'required',
        'paymentType'=>'required|in:BALANCE,QUICKPAY,ONLINEBANK,THIRDSCANPAY,OFFLINEPAY,PAYMENT_TYPE_SUPER,PAYMENT_TYPE_YJ,PAYMENT_TYPE_WECHAT,PAYMENT_TYPE_UPMP',
        'userTerminalType'=>'in:PC,MOBILE',
        'buyerOrgName'=>'max:180',
        'behavior'=>'between:0,255',
        'mainTradeNo'=>'max:180',
        'customerNo'=>'max:180',
        'merchOrderNo'=>'required|between:16,40'
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
        'service'=>'fastPayTradeMergePay'
    ];

    public $service = 'fastPayTradeMergePay';

    public function __construct(array $developerInfo, array $parameter){
        $developerInfo['service'] = $this->service;
        $this->data = array_merge($this->data, $parameter);
        parent::__construct($developerInfo, $this->data ,$this->rules);
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

    public function run(){
        if($this->tradeInfoErrorType !== true ){
          return $this->tradeInfoErrorType;
        }
        return $this->send('form');
    }


}
