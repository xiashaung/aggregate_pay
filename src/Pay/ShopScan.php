<?php


namespace Qingtian\Pay;


use App\Business\Pay\PayException;

class ShopScan extends Base
{
    /**
     * @var float 金额
     */
    protected $amount;

    /**
     * @var string 支付宝或微信条形码
     */
    protected $authNo;

    protected $postData;

    /**
     * ShopScanPay constructor.
     * @param float $amount 金额  用户条形码
     * @param string $authNo
     */
    public function __construct(float $amount, string $authNo)
    {
        $this->amount = $amount;
        $this->authNo = $authNo;
    }

    public function pay()
    {
        $this->postData =  $this->postData();
        $res = $this->post($this->api_url, $this->makePostData($this->postData));
        if ($res->resp_code == '0000') {
            return $res;
        } else {
            throw new PayException($res->resp_msg);
        }
    }

    /**
     * @return array
     * 接口调用数据
     */
    protected function postData()
    {
        $order_sn = $this->pp_trade_no?:$this->createPPTradeNo();
        return [
            'account' => $this->shop_no,
            'pp_trade_no' => $order_sn,
            'amount' => $this->amount*100,//单位为分 最少2分
            'payment_method' => "SK",
            'authno' => $this->authNo,
            'notify_url' => $this->notify_url, //支付回调地址
        ];
    }
}
