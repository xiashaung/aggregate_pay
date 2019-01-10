<?php


namespace Qingtian\Pay;


use Exception\MinProgramException;

class MinProgram    extends  Base
{
    protected $appId;

    protected $openId;

    protected $goodsName;

    protected $remark;

    protected $result;

    protected $api_url = 'http://www.qingtianfu.com/order/mini_program_pay';


    /**
     * @return object
     * @throws MinProgramException
     */
    public function handle()
    {
        $this->formatPostData();
        $this->result = $result = $this->makePostRequest();
        if ($result->resp_code=='0000'){
            $this->result->payInfo = json_decode($this->result->payInfo);
            return $this->result;
        }
        throw new MinProgramException($result->resp_msg);
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
            'amount' => $this->payment*100,
            'appid' => $this->appId,
            'openid' => $this->openId,
            'goods_name' => $this->goodsName?:'客满美业',
            'remark' => $this->remark?:'客满美业',
            'pp_trade_no' => $this->order_sn,
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
     * @param mixed $appId
     * @return self
     */
    public function setAppId(string $appId): self
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * @param mixed $openId
     * @return self
     */
    public function setOpenId(string $openId): self
    {
        $this->openId = $openId;
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
