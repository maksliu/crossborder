<?php
namespace Yjpay\Yht\Application;

use Yjpay\Yht\Foundation\App;

class SinglePaymentUpload extends App{

      protected $rules = array(
          'orderFlowType'=>'in:NORMAL',
          'eplatEntName'=>'between:1,180',
          'eplatEntCode'=>'between:1,180',
          'eshopEntName'=>'required|between:1,180',
          'eshopEntCode'=>'required|between:1,180',
          'customsCode'=>'required|between:1,10',
          'outOrderNo'=>'required|between:0,30',
          'tradeNo'=>'required|min:1',
          //'paymentType'=>'between:',
          'payerDocType'=>'required|in:Identity_Card',
          'payerId'=>'required|size:18',
          'payerName'=>'required|between:0,32',
          'bizTypeCode'=>'in:DIRECT_IMPORT,FREE_TAX_IMPORT',
          'goodsCurrency'=>'required|in:CNY',
          'goodsAmount'=>'required|numeric',
          'taxCurrency'=>'required|in:CNY',
          'taxAmount'=>'required|numeric',
          'freightCurrency'=>'required|in:CNY',
          'freightAmount'=>'required|numeric',
          'appStatus'=>'in:DECLARE',
          'ieType'=>'in:IMPORT,EXPORT',
          'eplatCodeForNgct'=>'between:0,32',
          'eEntCodeForNgct'=>'between:0,32',
          'eplatDNS'=>'between:0,32',
          'ngtcCode'=>'between:0,32',
          'operationType'=>'in:ADD,MODIFY,DELETE'
      );

      protected $data = [
        'service'=>'singlePaymentUpload',
        'version'=>'1.0'
      ];

      public $service = 'singlePaymentUpload';

      public function __construct(array $developerInfo, array $parameter ){
          $developerInfo['service'] = $this->service;
          $this->data = array_merge($this->data, $parameter);
          $this->data['tradeNo'] = '["'.$this->data['tradeNo'].'"]';
          parent::__construct($developerInfo, $this->data ,$this->rules);
      }

      public function run(){
         return $this->send('post');
      }


}
