<?php


namespace Qingtian\Pay;


use App\Business\Pay\PayException;
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
        $this->init();
    }


    protected function init()
    {
        $postData = $this->postData();
        $res = $this->post($this->api_url, $this->makePostData($postData));
        if ($res->resp_code == '0000') {
            $this->setRefreshUrl($res->refresh_url);
            return true;
        } else {
            throw new PayException($res->resp_msg);
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
        $this->qrcode->size($size)->generate($this->refresh_url);
    }


    /**
     * @return array
     * 调用接口信息
     */
    protected function postData(): array
    {
        $order_sn = $this->createPPTradeNo();
        return [
            'amount' => $this->amount * 100,//支付金额 单位是分
            'account' => $this->shop_no,
            'pp_trade_no' => $order_sn,
            'payment_method' => "JSAPI",
            'notify_url' => $this->notify_url, //支付回调地址
        ];
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
