<?php


namespace Qingtian\Pay;


class CheckOrder   extends  Base
{
    protected $order_sn;

    /**
     * @var string 状态码 0000表示成功 其他表示失败
     */
    protected $code;
    /**
     * @var string 信息
     */
    protected $respMsg;
    /**
     * @var  int  1 支付宝 2微信 0 其他
     */
    protected $channel = 0;

    /**
     * @var
     */
    protected $result;
    
    public function __construct(string $order_sn)
    {
        $this->order_sn = $order_sn;
        $this->api_url = "http://www.qingtianfu.com/apis/order_query";
    }
    
    public function handle()
    {
           $data = [
               'order_sn'=>$this->order_sn,
               'account'=>$this->shop_no,
           ];
          $this->result = $res =  $this->post($this->api_url,$this->makePostData($data));
          $this->code = $res->resp_code;
          $this->respMsg = $res->resp_msg;
          if (!$this->check()){
              switch ($res->pay_type){
                  case "WXPAY":
                      $this->channel = 2;
                      break;
                  case "ALIPAY":
                      $this->channel = 1;
                      break;
                  default:
                      $this->channel = 0;
                      break;
              }
          }
    }

    public function check()
    {
        return $this->code =='0000';
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getRespMsg()
    {
        return $this->respMsg;
    }

    /**
     * @return int
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return mixed
     * {#336 ▼
     * +"transaction_id": "507172408"
     * +"total_amount": "2"//支付金额
     * +"coupon_fee": "0" //优惠金额
     * +"coupon_fee_no_cash": "0"
     * +"settlement_total_fee": "2"
     * +"resp_code": "0000" //支付结果
     * +"sign": "0bdecb50e06cffef18f512ec76e65d03"
     * +"pay_type": "WXPAY" //支付类型 ALIPAY
     * +"resp_msg": "交易成功"
     * +"order_sn": "POS2018112316304859034"
     * }
     */
    public function getResult()
    {
        return $this->result;
    }


}
