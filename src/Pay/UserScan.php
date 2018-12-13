<?php


namespace Qingtian\Pay;


use Exception\PayException;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;

class UserScan  extends Base
{
    protected $refresh_url;

    protected $amount;

    protected $qrcode;
    

    public function __construct(float $amount)
    {
        $this->amount = $amount;
        $this->qrcode = new BaconQrCodeGenerator();
    }


    /**
     * @return mixed
     * @throws PayException
     * 执行支付调用 这个时候还未进行支付 等待成功返回并用返回的url生成二维码,提供给用户扫描即可
     */
    public function pay()
    {
        $this->check();
        $this->postData = $this->postData();
        $res = $this->makePostRequest();;
        if ($res->resp_code == '0000') {
            $this->setRefreshUrl($res->refresh_url);
            return $res;
        } else {
            throw new PayException($res->resp_msg, $res->resp_code);
        }
    }


    /**
     * @param int $size
     * @return mixed
     * @throws PayException
     * 获取支付二维码
     */
    public function getQrCode($size = 400)
    {
        if (!$this->refresh_url) {
            throw new PayException("支付地址信息错误");
        }
        return $this->qrcode->size($size)->generate($this->refresh_url);
    }


    /**
     * @return array
     * 调用接口信息
     */
    protected function postData(): array
    {
        $order_sn = $this->pp_trade_no?: $this->createPPTradeNo();
        $data =  [
            'amount' => $this->amount * 100,//支付金额 单位是分
            'account' => $this->shop_no,
            'pp_trade_no' => $order_sn,
            'payment_method' => "JSAPI",
        ];
        if ($this->notify_url) {
            $data['notify_url'] = $this->notify_url;
        }
        return $data;
    }

    /**
     * @throws Pa
     */
    protected function check()
    {
        if (!$this->key) {
            throw new PayException('商户秘钥未设置');
        }
        if (!$this->shop_no) {
            throw new PayException('商户号未设置');
        }
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
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getRefreshUrl(): string
    {
        return $this->refresh_url;
    }

}
