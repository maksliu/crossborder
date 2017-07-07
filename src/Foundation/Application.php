<?php
/*
 * This file is part of the Yjpay/Crossborder.
 *
 * (c) manarch <manarchliu@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

 namespace Yjpay\Crossborder\Foundation;

 use Overtrue\Validation\Translator;
 use Overtrue\Validation\Factory as ValidatorFactory;

 class Application {

   protected $config = [
     #'version'='1.0'
     'signType'='MD5',
   ];

   /**
   * 定义验证规则
   */
   protected $rule = array(
     "partnerId" => "required|digits:20|string",
     "secretKey"=>"required|digits:32|string"
   );


   public function  __construct( array $config ){

     if($this->validation($config) === true){

        $this->config = array_merge($this->config,$config)
     }

   }


   /**
   * 验证数据
   */
   protected function validation( array $data ){

     // 示例化验证器
     $factory = new ValidatorFactory(new Translator);
     // 验证数据
     $validation_rls = $factory->make($data , $this->rule );
     // 返回验证结果
     return $validation_rls ?: $validation_rls->messages()->all();

   }



 }
