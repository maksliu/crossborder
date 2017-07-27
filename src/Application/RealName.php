<?php

namespace Yjpay\Crossborder\Application;

use Yjpay\Crossborder\Foundation\App;

class RealName extends App{

    private $rules = array(
        "merchOrderNo"=>'required|between:16,40',
        'realName'=>'required|max:50',
        'certNo' => 'required|size:18'
    );

    public $service = 'realNameQuery';

    private $data = [
        //'service'=>'realNameQuery',
    ];

    public function __construct(array $developerInfo, array $data){
        $this->data = array_merge($this->data, $data);
        $developerInfo['service'] = $this->service;
        parent::__construct($developerInfo, $this->data ,$this->rules);
    }

    public function run(){
        return $this->send('post');
    }

}
