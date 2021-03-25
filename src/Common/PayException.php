<?php
namespace alipay\Common;

class PayException extends \Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}