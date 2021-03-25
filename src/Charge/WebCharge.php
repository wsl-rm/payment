<?php
namespace alipay\Charge;

use alipay\Config\AliConfig;
use alipay\Common\PayException;

require_once dirname(__DIR__).'/Api/pagepay/service/AlipayTradeService.php';
require_once dirname(__DIR__).'/Api/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

class WebCharge
{
    protected $config;

    public function __construct(array $config){
        try {
            $this->config = (Array)new AliConfig($config); 
        } catch (PayException $e) {
            throw $e;
        }
    }

    public function hand(array $data){
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($data['out_trade_no']);

        //订单名称，必填
        $subject = trim($data['subject']);

        //付款金额，必填
        $total_amount = trim($data['amount']);

        //商品描述，可空
        $body = trim($data['body']);

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($this->config);
        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $response = $aop->pagePay($payRequestBuilder,$this->config['return_url'],$this->config['notify_url']);
        //输出表单
        var_dump($response);
    }

    public function notify_url(array $arr){
        $alipaySevice = new \AlipayTradeService($this->config); 
        $alipaySevice->writeLog(var_export($arr,true));
        $result = $alipaySevice->check($arr);
        return $result;
    }

    public function return_url(array $arr){
        $alipaySevice = new \AlipayTradeService($this->config); 
        $result = $alipaySevice->check($arr);
        return $result;
    }
}