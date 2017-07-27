<?php
/**
 * Created by PhpStorm.
 * User: manarch
 * Date: 2017/7/12
 * Time: 17:13
 */

namespace Yjpay\Crossborder\Foundation;

use Overtrue\Validation\Translator;
use Overtrue\Validation\Factory as ValidatorFactory;
use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class App
{
    /**
     * 测试网关
     */
    const DEBUG_GATEWAY = 'http://openapi.yijifu.net';

    /**
     * 正式网关
     */
    const PRODUCE_GATEWAY = 'https://api.yiji.com';

    /**
     * @var 配置数据
     */
    protected $configData;
    /**
     * @var 配置验证规则
     */
    protected $configRules = [
        'partnerId' => 'required|size:20',
        'secretKey' => 'required|size:32',
        'service'   => 'required'
    ];

    /**
     * @var 等待请求的数据
     */
    protected $waitRequestData = [
        'signType'=>'MD5',
        'version'=>'1.0',
    ];

    /**
     * @var 请求数据的验证规则
     */
    protected $requestRules = [
        'orderNo'=>'required|between:16,40',
        'partnerId' => 'required|size:20',
        'service'   => 'required',
        'signType'=>'required|in:MD5,SHA1,RSA',
        'sign'=>'required|size:32',
        'version'=>'numeric',
        'merchOrderNo'=>'between:16,40',
        'context'=>'max:128',
        'returnUrl'=>'url|max:128',
        'notifyUrl'=>'url|max:128'
    ];

    /**
     * @var 错误信息
     */
    protected $error = true;

    /**
     * @var 商户密钥
     */
    protected $secretKey;

    private $debug = false;
    protected $log;
    protected $logPath = "./logs/";


    /**
     * App constructor.
     * @param array $configData
     * @param array $waitRequestData
     * @param array $configRules
     * @param array $requestRules
     */

    public function __construct(array $configData, array $waitRequestData, array $requestRules)
    {
        //var_dump($configData);
        $this->mergeConfigData($configData);

        $this->mergeWaitRequestData($waitRequestData);
        $this->mergeRequestRules($requestRules);
//var_dump($this);
    }

    /**
     * 作用:合并子类传过来的数据
     * @param array $configData
     */
    private function mergeConfigData(array $configData){

        # 验证数据合法性
        $this->validation($configData,$this->configRules);
        $this->configData = $configData;
        $this->secretKey = $this->configData['secretKey'];
        unset($this->configData['secretKey']);
        # debug模式设置
        if(isset($this->configData['debug'])){
            $this->debug = (bool)$this->configData['debug'];
            unset($this->configData['debug']);
        }

        # 日志设置
        if(isset($this->configData['logPath'])){
            $this->logPath = $this->configData['logPath'].$this->configData['service'].'-'.date('Y-m-d').'.log';
            unset($this->configData['logPath']);
        }else{
            is_dir($this->logPath) ?:mkdir($this->logPath);
            $this->logPath = $this->logPath.$this->configData['service'].'-'.date('Y-m-d').'.log';
        }

        $this->log = new Logger($this->configData['service']);
        $this->log->pushHandler(new StreamHandler($this->logPath, Logger::WARNING));

    }

    /**
     * 作用:合并等待请求的数据
     * @param array $waitRequestData
     */
    private function mergeWaitRequestData(array $waitRequestData){
        $this->waitRequestData = array_merge($this->waitRequestData,$waitRequestData);
    }

    /**
     * 作用:合并等待请求的数据
     * @param array $waitRequestData
     */
    private function mergeRequestRules(array $requestRules){
        $this->requestRules = array_merge($this->requestRules,$requestRules);
    }


    /**
     * 数据校验
     * @param array $data 数据
     * @param array $rules 规则
     */
    private function validation(array $data, array $rules){

        if($this->error !== true){
            return false;
        }
        // 示例化验证器
        $factory = new ValidatorFactory(new Translator);
        // 验证数据
        $validator = $factory->make($data , $rules );

        $this->error = $validator->passes() ?: $validator->errors();

        //var_dump($errorType);

        // if($this->error !== true && is_string($this->error)){
        //
        //     $this->error .= json_encode($errorType);
        //
        // }else{
        //     $this->error = $errorType;
        // }

    }

    /**
     * 作用:http请求
     * @param $method
     * @param $requestData
     * @return mixed
     */
    private function HttpSend($method, $requestData){
        $url = $this->debug ? self::DEBUG_GATEWAY : self::PRODUCE_GATEWAY ;
        $client = new Client(['base_uri' => $url]);
        $response = '';
        switch ($method){
            case 'post':
                $response = $client->post('/gateway.html',['form_params'=>$requestData]);
                break;
            case 'get':
                $response = $client->get('/gateway.html',['query'=>$requestData]);
                break;
            default:
                $response = $client->post('/gateway.html',['form_params'=>$requestData]);
                break;
        }

        //var_dump(json_decode($response->getBody(), 1)) ;

        if($response->getStatusCode() == 200){
            return json_decode($response->getBody(), 1);
        }
    }

    /**
     * Form表单支持
     * @return string
     */
    private function Form(array $requestData){
        $url = $this->debug ? self::DEBUG_GATEWAY : self::PRODUCE_GATEWAY ;
        $html ='';
        $html .= '<form action="'.$url.'/gateway.html" method="POST" name="'.$this->configData['service'].'-form">';
        foreach($requestData as $key => $value){
            $html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }
        $html .= '<button type="submit">Submit</button>';
        $html .= '</form>';
        return $html;
    }

    /**
     * 组装请求数据
     */
    private function buildRequestData(){
        $this->waitRequestData = array_merge($this->waitRequestData,$this->configData);
        $this->waitRequestData['orderNo'] = 'Q-'.date('YmdHis').mt_rand(100000,999999);
        $this->requestFilterAndSort();
        $this->waitRequestData['sign'] = $this->getSignString($this->waitRequestData);
        $this->validation($this->waitRequestData,$this->requestRules);

    }

    /**
     * 发送请求
     * @param $method
     * @return mixed|string|错误信息
     */
    public function send($method){
        //return $this->error;
        //return $this->configData;
        $this->buildRequestData();
        //return $this->error;
        if($this->error !== true){
            $this->log->addWarning($this->error);
            return $this->error;
        }
        switch ($method) {
            case 'post':
                return $this->HttpSend('post',$this->waitRequestData);
                break;
            case 'get':
                return $this->HttpSend('get',$this->waitRequestData);
                break;
            case 'form':
                return $this->Form($this->waitRequestData);
                break;
            default:
                return $this->HttpSend('post',$this->waitRequestData);
                break;
        }
    }

    /**
     * 作用:清除空数据并排序
     * @param array $data
     */
    private function requestFilterAndSort(){
        $this->waitRequestData = array_filter($this->waitRequestData);
        ksort($this->waitRequestData);
    }

    /**
     * 作用:计算sign值
     * @param array $data
     * @return string
     */
    private function getSignString(array $data){
        $waitString = '';

        foreach($data as $k => $v){
            $waitString .= '&'.$k.'='.$v;
        }

        $waitString = trim($waitString,'&');
        //echo $waitString.$this->secretKey."\n\n";
        return md5($waitString.$this->secretKey);
    }
}
