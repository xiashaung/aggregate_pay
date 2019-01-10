<?php


namespace Qingtian\Pay;

use Exception\PayException;

class  ShopScan extends Base
{

    /**
     * @var string 支付宝或微信条形码
     */
    protected $authNo;


    public function handle()
    {
        $this->postData();
        $res = $this->makePostRequest();;
        if ($res->resp_code == '0000') {
            return $res;
        } else {
            throw new PayException($res->resp_msg,$res->resp_code);
        }
    }

    /**
     * 接口调用数据
     */
    protected function postData()
    {
        $data =  [
            'account' => $this->shop_no,
            'pp_trade_no' => $this->order_sn,
            'amount' => $this->payment*100,//单位为分 最少2分
            'payment_method' => "SK",
            'authno' => $this->authNo,
        ];
        if ($this->notify_url){
            $data['notify_url'] = $this->notify_url;
        }
        $this->postData = $data;
    }

    /**
     * @param string $authNo
     * @return $this
     */
    public function setAuthNo(string $authNo): self
    {
        $this->authNo = $authNo;
        return $this;
    }


}
