<?php


namespace Qingtian\Pay;


use Exception\PayException;

class UserScan  extends  Base
{

    protected $refresh_url;

    /**
     * @return mixed
     * @throws PayException
     * 执行支付调用 这个时候还未进行支付 等待成功返回并用返回的url生成二维码,提供给用户扫描即可
     */
    public function handle()
    {
        $this->postData();
        $res = $this->makePostRequest();;
        if ($res->resp_code == '0000') {
            $this->setRefreshUrl($res->refresh_url);
            return $res;
        } else {
            throw new PayException($res->resp_msg, $res->resp_code);
        }
    }


    /**
     * @return array
     * 调用接口信息
     */
    protected function postData(): array
    {
        $data =  [
            'amount' => $this->payment * 100,//支付金额 单位是分
            'account' => $this->shop_no,
            'pp_trade_no' => $this->order_sn,
            'payment_method' => "JSAPI",
        ];
        if ($this->notify_url) {
            $data['notify_url'] = $this->notify_url;
        }
        $this->postData = $data;
    }


    /**
     * @param mixed $refresh_url
     * 设置支付地址
     */
    public function setRefreshUrl($refresh_url): void
    {
        $this->refresh_url = $refresh_url;
    }

    /**
     * @return mixed
     */
    public function getRefreshUrl(): string
    {
        return $this->refresh_url;
    }

}
