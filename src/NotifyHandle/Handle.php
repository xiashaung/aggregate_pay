<?php


namespace Qingtian\NotifyHandle;


use Exception\PayException;

class Handle
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var  string
     */
    protected $key;

    /**
     * @var \Closure
     */
    protected $callback;

    const APLPAY_JSAPI = 4; //支付宝jsapi
    const APLPAY_SCAN = 4; //支付
    const WECHAT_SCAN = 3; //微信扫码
    const WECHAT_JSAPI = 7;//微信jsapi
    CONST WECHAT_PUBLIC = 7;//微信公众号
    CONST WECHAT_APPLET = 7;//微信小程序

    /**
     * RefundNotifyHandle constructor.
     * @param array $data
     * @param string $key
     * @param \Closure $callback
     */
    public function __construct(array $data, string $key, \Closure $callback)
    {
        $this->data = $data;
        $this->key = $key;
        $this->callback = $callback;
    }

    /**
     * @param string $orderSn
     * @param float $orderAmount
     * 处理函数
     */
    public function handle(string $orderSn, float $orderAmount)
    {
        $data = $this->data;
        //删除签名
        unset($data['sign']);
        $data['order_amount'] = $orderAmount;
        $data['order_no'] = $orderSn;
        $sign = makeSign($data, $this->key);
        if ($sign != $this->data['sign']) {
            throw new PayException('签名验证错误');
        }
        //验证通过 执行回调函数
        $callback = $this->callback;
        $callback($this);
    }

    /**
     * @return int
     * 获取支付类型
     */
    public function getPayType()
    {
        switch ($this->data['app_pay_type']) {
            case 'WXPAY':
                return $this->chooseWxPay();
                break;
            case 'ALIPAY':
                return $this->chooseAliPay();
                break;
            default:
                return 0;
                break;
        }
    }

    /**
     * @return int
     * 选择支付宝支付方式
     */
    protected function chooseAliPay()
    {
        switch ($this->data['pay_type']){
            case 'SCAN':
                return self::APLPAY_SCAN;
                break;
            case 'SWIPE':
                return self::APLPAY_JSAPI;
                break;
            default:
                return 0;
                break;
        }
    }

    /**
     * @return int
     * 微信支付方式
     */
    protected function chooseWxPay()
    {
        switch ($this->data['pay_type']) {
            case 'SCAN':
                return self::WECHAT_SCAN;
                break;
            case 'SWIPE':
                return self::WECHAT_JSAPI;
                break;
            case 'PUBLIC':
                return self::WECHAT_PUBLIC;
                break;
            case 'APPLET':
                return self::WECHAT_APPLET;
                break;
            default:
                return 0;
                break;
        }
    }

    /**
     * @return mixed
     * 获取第三方的订单号
     */
    public function getThirdPartOrderSn()
    {
        return $this->data['channel_order_num'];
    }

    /**
     * @return mixed
     * 获取渠道订单号 一般用于退款
     */
    public function getChannelOrderSn()
    {
        return $this->data['system_serial'];
    }
}
