<?php


namespace Qingtian\Pay;

use Pay\PayInterface;
use Qingtian\Traits\CurlTool;
use Qingtian\Traits\SetterAndGetter;

/**
 * Class Base
 * @package Qingtian\Pay
 * 聚合支付基础类
 */
abstract class Base  implements PayInterface
{
    use CurlTool,SetterAndGetter;

    /**
     * @var string
     * 聚合支付调用地址
     */
    protected $api_url = 'http://www.qingtianfu.com/apis/create_order';

    public function __construct(string $shopNo, string $key)
    {
        $this->shop_no = $shopNo;
        $this->key = $key;
    }

    /**
     * @return mixed
     * 执行post请求
     */
    public function makePostRequest()
    {
       return  $this->post($this->api_url,$this->makeSign($this->postData));
    }

    /**
     * @param array $data
     * @return array
     * 计算签名
     */
    public function makeSign(array $data)
    {
        $data['sign'] = makeSign($data,$this->key);
        return $data;
    }
}
