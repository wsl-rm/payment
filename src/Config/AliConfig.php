<?php
namespace alipay\Config;

use alipay\Common\PayException;

final class AliConfig
{
    // 支付宝的网关
    public $gatewayUrl = 'https://openapi.alipay.com/gateway.do';

    // 采用的编码
    public $charset = 'UTF-8';

    //appid
    public $app_id;

    // 合作者身份ID
    public $partner;

    // 用于异步通知的地址
    public $notify_url;

    // 用于同步通知的地址
    public $return_url;

    // 订单在支付宝服务器过期的时间，过期后无法支付
    public $timeExpire;

    // 用于rsa加密的私钥
    public $merchant_private_key;

    // 用于rsa解密的支付宝公钥
    public $alipay_public_key;

    // 加密方式 默认使用RSA2
    public $sign_type = 'RSA2';
    

    public function __construct(array $config)
    {
        // 初始化配置信息
        try {
            $this->initConfig($config);
        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * 检查传入的配置文件信息是否正确
     * @param array $config
     * @throws PayException
     * @author jmx
     */
    private function initConfig(array $config)
    {
        // 初始 app_id
        if (key_exists('app_id', $config) && !empty($config['app_id'])) {
            $this->app_id = $config['app_id'];
        } else {
            throw new PayException('appid不能为空');
        }

        // 初始 公钥
        if (key_exists('alipay_public_key', $config) && !empty($config['alipay_public_key'])) {
            $this->alipay_public_key = $config['alipay_public_key'];
        } else {
            throw new PayException('支付宝公钥不能为空');
        }

        // 初始 私钥
        if (key_exists('merchant_private_key', $config) && !empty($config['merchant_private_key'])) {
            $this->merchant_private_key = $config['merchant_private_key'];
        } else {
            throw new PayException('应用私钥不能为空');
        }

        // 初始 支付宝异步通知地址，可为空
        if (key_exists('notify_url', $config) && !empty($config['notify_url'])) {
            $this->notify_url = $config['notify_url'];
        }

        // 初始 支付宝 同步通知地址，可为空
        if (key_exists('return_url', $config) && !empty($config['return_url'])) {
            $this->return_url = $config['return_url'];
        }

        // 初始 支付宝订单过期时间，可为空 取值范围：1m～15d
        // m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）
        if (key_exists('time_expire', $config) && !empty($config['time_expire'])) {
            $this->timeExpire = $config['time_expire'];
        }

        // 初始 支付宝网关地址
        if (key_exists('geteway_url', $config) && !empty($config['geteway_url'])) {
            $this->gatewayUrl = $config['geteway_url'];
        }

        //判断是否沙箱环境
        if(key_exists('use_sandbox', $config) && $config['use_sandbox']){
            $this->gatewayUrl = str_replace('alipay','alipaydev',$this->gatewayUrl);
        }
    }
}