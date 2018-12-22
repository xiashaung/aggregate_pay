<?php


namespace Qingtian\Pay;


use Exception\MinProgramException;

class MinProgram    extends  Base
{
    protected $appId;

    protected $openId;

    protected $amount;

    protected $goodsName;

    protected $remark;

    protected $result;


    /**
     * MinProgram constructor.
     * @param $appId string
     * @param $openId  string
     * @param $amount  int|float 订单金额
     */
    public function __construct(string $appId, string $openId, $amount)
    {
        $this->appId = $appId;
        $this->openId = $openId;
        $this->amount = $amount;
        $this->api_url = 'http://www.qingtianfu.com/order/mini_program_pay';
    }

    /**
     * @return object
     * @throws MinProgramException
     */
    public function handle()
    {
        $this->formatPostData();
        $this->result = $result = $this->makePostRequest();
        if ($result->resp_code!='0000'){
            throw new MinProgramException($result->resp_msg);
        }
        $this->result->payInfo = json_encode($this->result->payInfo);
        return $this->result;
    }

    /**
     * 调用接口数据结构
     * @throws MinProgramException
     */
    protected function formatPostData()
    {
        if (!$this->appId) throw new MinProgramException('app_id is empty');
        if (!$this->openId) throw new MinProgramException('openId is empty');
        if (!$this->amount) throw new MinProgramException('amount is empty');
        if (!is_numeric($this->amount)) throw new MinProgramException('amount必须为数字类型');

        $this->postData = [
            'amount' => $this->amount*100,
            'appid' => $this->appId,
            'openid' => $this->openId,
            'goods_name' => $this->goodsName?:'客满美业',
            'remark' => $this->remark?:'客满美业',
            'pp_trade_no' => $this->pp_trade_no,
            'account' => $this->shop_no,
        ];
        if ($this->notify_url){
            $this->postData['notify_url'] = $this->notify_url;
        }
    }

    /**
     * @param mixed $goodsName
     * @return self
     * //设置商品名称
     */
    public function setGoodsName($goodsName): self
    {
        $this->goodsName = $goodsName;
        return $this;
    }

    /**
     * @param mixed $remark
     * @return self
     * 设置备注
     */
    public function setRemark($remark): self
    {
        $this->remark = $remark;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws MinProgramException
     */
    public function __get($name)
    {
        if (isset($this->result->payInfo->$name)){
            return $this->result->payInfo->$name;
        }
        throw new MinProgramException(sprintf("%s not found in payInfo",$name));
    }
}
