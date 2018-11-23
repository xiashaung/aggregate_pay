<?php


namespace Qingtian\Pay;

use Qingtian\Traits\CurlTool;
/**
 * Class Base
 * @package Qingtian\Pay
 * 聚合支付基础类
 */
abstract class Base
{
    use CurlTool;

    /**
     * @var string
     * 商户秘钥
     */
    protected $key;


    /**
     * @var string
     * 商户号
     */
    protected $shop_no;

    /**
     * @var string 订单号
     */
    protected $pp_trade_no;

    /**
     * @var array 请求接口的数据
     */
    protected $postData;

    /**
     * @var string 回调地址 不能带参数
     */
    protected $notify_url;

    /**
     * @var string
     * 聚合支付调用地址
     */
    protected $api_url = 'http://www.qingtianfu.com/apis/create_order';


    /**
     * @param array $data
     * @return array
     * 计算签名
     */
    public function makePostData(array $data)
    {
        //对数组进行排序
        ksort($data);
        $tmp = '';
        foreach ($data as $key => $rs) {
            if (is_array($rs)) {
                ksort($rs);
                foreach ($rs as $k => $v) {
                    $tmp .= $k . '=' . $v . '&';
                }
            } else {
                $tmp .= $key . '=' . $rs . '&';
            }
        }
        $sign = trim($tmp, '&');
        $sign .= '&key=' . $this->key;
//          dd($sign);
        $data['sign'] = md5($sign);
        return $data;
    }
    

    /**
     * @return string
     * 创建订单流水号
     */
    protected function createPPTradeNo()
    {
        return 'POS' . date('YmdHis') . rand(10000, 99999);
    }

    /**
     * @param string $pp_trade_no
     * @return $this
     * 设置订单号
     */
    public function setPpTradeNo(string $pp_trade_no): self
    {
        $this->pp_trade_no = $pp_trade_no;
        return $this;
    }

    /**
     * @param string $notify_url
     * @return $this
     * 设置回调地址
     */
    public function setNotifyUrl(string $notify_url): self 
    {
        $this->notify_url = $notify_url;
        return $this;
    }


    /**
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $shop_no
     * @return $this
     */
    public function setShopNo(string $shop_no): self
    {
        $this->shop_no = $shop_no;
        return $this;
    }

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }


}
