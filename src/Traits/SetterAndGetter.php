<?php


namespace Qingtian\Traits;


trait SetterAndGetter
{
    /**
     * @var string 商户号
     */
     protected $shop_no;

    /**
     * @var string 秘钥
     */
     protected $key;

    /**
     * @var string 订单号
     */
     protected $order_sn;

    /**
     * @var float|int 支付金额
     */
     protected $payment;

    /**
     * @var array
     */
     protected $postData;

    /**
     * @var string 回调地址 不能带参数
     */
    protected $notify_url;
     
    /**
     * @param string $order_sn
     * @return $this
     */
    public function setOrderSn(string $order_sn): self
    {
        $this->order_sn = $order_sn;
        return $this;
    }

    /**
     * @param int|float $payment
     * @return $this
     */
    public function setPayment($payment): self
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * @param string $notify_url
     * @return $this
     */
    public function setNotifyUrl(string $notify_url): self
    {
        $this->notify_url = $notify_url;
        return $this;
    }



    /**
     * @return string
     */
    public function getShopNo(): string
    {
        return $this->shop_no;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getOrderSn(): string
    {
        return $this->order_sn;
    }

    /**
     * @return float|int
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }




}