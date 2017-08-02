<?php

namespace Yjpay\Crossborder\Application;

class Signature{

    protected $secretKey;
    protected $sign;

    public function signature($secretKey,$data){
      $this->sign = $data['sign'];
      unset($data['sign']);
      $this->secretKey = $secretKey;
      ksort($data);
      return $this->getSignString($data) == $this->sign ? $data : false ;
    }

    private function getSignString(array $data){
        $waitString = '';
        foreach($data as $k => $v){
            $waitString .= '&'.$k.'='.$v;
        }
        $waitString = trim($waitString,'&');
        $sign = md5($waitString.$this->secretKey);
        return $sign;
    }

}
