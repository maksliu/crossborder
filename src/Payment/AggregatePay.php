<?php
/*
 * This file is part of the Yjpay/Crossborder.
 *
 * (c) manarch <manarchliu@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Yjpay\Crossborder\Payment;

use Yjpay\Crossborder\Foundation\App;

class AggregatePay extends App{

    private $rules = array(
        "merchOrderNo"=>'required|between:16,40',
        'returnUrl'=>'required|max:180',
        'notifyUrl'=>'required|max:180',
        'tradeNo'=>'size:20',
        'buyerUserId'=>'between:1,64',
        'userTerminalType'=>'required|in:PC,MOBILE',
        'tradeName'=>'max:180',
        'goodsType'=>'max:64',
        'goodsName'=>'max:64',
        'memo'=>'max:180',
        'sellerUserId'=>'size:20',
        'tradeAmount'=>'numeric',
        'openid'=>'min:32',
        'chargeExtends'=>'max:500',
        'autoCloseDuration'=>'integer',
        'macAddress'=>'between:0,48',
        'userEndIp'=>'ip',
        'paymentType'=>'in:PAYMENT_TYPE_SUPER,PAYMENT_TYPE_YJ,PAYMENT_TYPE_WECHAT,BALANCE,QUICKPAY,ONLINEBANK,THIRDSCANPAY,OFFLINEPAY',
        'memberType'=>'in:MEMBER_TYPE_YIJI,MEMBER_TYPE_PATERN,MEMBER_TYPE_CARD',
        'name'=>'min:2',
        'stable'=>'boolean',
        'mobileNo'=>'numeric',
        'mobileNoStable'=>'boolean',
        'cardNo'=>'between:16,19',
        'cardNoStable'=>'boolean',
        'certNo'=>'between:15,18',
        'certNoStable'=>'boolean',
        'bankCode'=>'in:WEIXIN,ALIPAY',
        'personalCorporateType'=>'in:CORPORATE,PERSONAL',
        'cardType'=>'in:CREDIT,DEBIT',
        'shareProfits'=>'max:180',
        'shareMethod'=>'in:M,S',
        'sellerMerchantId'=>'max:180',
        'customerNo'=>'max:32'
    );

    private $MobileRules = array(
        "merchOrderNo"=>'required|between:16,40',
        'returnUrl'=>'required|max:180',
        'notifyUrl'=>'required|max:180',
        'buyerUserId'=>'between:1,64',
        'userTerminalType'=>'required|in:MOBILE',
        'tradeName'=>'max:180',
        'goodsType'=>'max:64',
        'goodsName'=>'max:64',
        'memo'=>'max:180',
        'sellerUserId'=>'size:20',
        'tradeAmount'=>'required|numeric',
        'openid'=>'min:32',
        'chargeExtends'=>'max:500',
        'autoCloseDuration'=>'integer',
        'macAddress'=>'between:0,48',
        'userEndIp'=>'ip',
        'paymentType'=>'in:PAYMENT_TYPE_SUPER,PAYMENT_TYPE_YJ,PAYMENT_TYPE_WECHAT',
        'memberType'=>'required|in:MEMBER_TYPE_YIJI,MEMBER_TYPE_PATERN,MEMBER_TYPE_CARD',
        'name'=>'min:2',
        'stable'=>'boolean',
        'mobileNo'=>'numeric',
        'mobileNoStable'=>'boolean',
        'cardNo'=>'between:16,19',
        'cardNoStable'=>'boolean',
        'certNo'=>'between:15,18',
        'certNoStable'=>'boolean',
        'shareProfits'=>'max:180',
        'shareMethod'=>'in:M,S',
        'sellerMerchantId'=>'max:180',
        'customerNo'=>'max:32'
    );

    private $MobileWechatRules = [
        "merchOrderNo"=>'required|between:16,40',
        'returnUrl'=>'required|max:180',
        'notifyUrl'=>'required|max:180',
        'buyerUserId'=>'between:1,64',
        'userTerminalType'=>'required|in:MOBILE',
        'tradeName'=>'max:180',
        'goodsType'=>'max:64',
        'goodsName'=>'max:64',
        'memo'=>'max:180',
        'sellerUserId'=>'size:20',
        'tradeAmount'=>'required|numeric',
        'openid'=>'required|min:32',
        'chargeExtends'=>'max:500',
        'autoCloseDuration'=>'integer',
        'macAddress'=>'between:0,48',
        'userEndIp'=>'ip',
        'paymentType'=>'required|in:PAYMENT_TYPE_WECHAT',
        'memberType'=>'required|in:MEMBER_TYPE_YIJI,MEMBER_TYPE_PATERN,MEMBER_TYPE_CARD',
        'name'=>'min:2',
        'stable'=>'boolean',
        'mobileNo'=>'numeric',
        'mobileNoStable'=>'boolean',
        'cardNo'=>'between:16,19',
        'cardNoStable'=>'boolean',
        'certNo'=>'between:15,18',
        'certNoStable'=>'boolean',
        'shareProfits'=>'max:180',
        'shareMethod'=>'in:M,S',
        'sellerMerchantId'=>'max:180',
        'customerNo'=>'max:32'
    ];

    private $data = [
        'service'=>'aggregatePay'
    ];
    private $MobileData = [
        'service'=>'aggregatePay',
        'userTerminalType'=>'MOBILE',
        'paymentType'=>'PAYMENT_TYPE_SUPER'
    ];

    private $MobileWechatData = [
        'service'=>'aggregatePay',
        'userTerminalType'=>'MOBILE',
        'paymentType'=>'PAYMENT_TYPE_WECHAT'
    ];

    private $PayType = [
        'Mobile',
        'PC',
        'MobileWechat'
    ];

    public $service = 'aggregatePay';

    public function __construct( array $developerInfo, array $data,$PayType = 'PC'){

        $developerInfo['service'] = $this->service;
        switch ($PayType) {
            case 'Mobile':
                $this->MobilePay($developerInfo,$data);
                break;
            case 'MobileWechat':
                $this->MobileWechat($developerInfo,$data);
                break;
            case 'PC':
                $this->pc($developerInfo,$data);
                break;
            default:
                $this->pc($developerInfo,$data);
                break;
        }

    }

    private function pc(array $developerInfo, array $data){
        $this->data = array_merge($this->data, $data);
        parent::__construct($developerInfo, $this->data ,$this->rules);
    }

    private function MobilePay(array $developerInfo, array $data){
        $this->MobileData = array_merge($this->MobileData, $data);
        parent::__construct($developerInfo, $this->MobileData ,$this->MobileRules);
    }

    private function MobileWechat(array $developerInfo, array $data){
        $this->MobileWechatData = array_merge($this->MobileWechatData, $data);
        parent::__construct($developerInfo, $this->MobileWechatData ,$this->MobileWechatRules);
    }

    public function run(){
         return $this->send('form');
    }

}
