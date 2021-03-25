<?php
namespace alipay;

use alipay\Config\Config;
use alipay\Charge\WebCharge;
use alipay\Common\PayException;

class Charge{

    protected $payWay;

    private function initCharge($type,array $aliconf){
        try {
            switch ($type) {
                case Config::ALI_CHANNEL_WEB:
                    $this->payWay = new WebCharge($aliconf);
                    break;
                default :
                    throw new PayException('当前仅支持：支付宝PC网页支付');
            }
        } catch (PayException $e) {
            throw $e;
        }
    }

    public function run($type,array $aliconf,array $postData){
        $this->initCharge($type,$aliconf);
        return $this->payWay->hand($postData);
    }

    public function notify_url($type,array $arr,array $config){
        switch ($type) {
            case Config::ALI_CHANNEL_WEB:
                $payWay = new WebCharge($config);
                return $payWay->notify_url($arr);
                break;
            default :
                throw new PayException('当前仅支持：支付宝PC网页支付');
        }
    }

    public function return_url($type,array $arr,array $config){
        switch ($type) {
            case Config::ALI_CHANNEL_WEB:
                $payWay = new WebCharge($config);
                return $payWay->return_url($arr);
                break;
            default :
                throw new PayException('当前仅支持：支付宝PC网页支付');
        }
    }

}