## 擎天付 PHP SDK

### 引入

    composer require xiashuang/qingtianpay

### 支持方式
    
    1. 用户扫描商家二维码 (微信/支付宝)
    2. 商家扫描用户付款码(微信/支付宝)
    
## 使用

### 用户扫描商家收款码
    
     <?php
     
     //实例化 $amount最小值为0.02
     $pay = new QingtianPay\Pay\UserScan(float $amount);
     
     $res = $pay->setShopNo('商户号')//设置商户号
         ->setKey('秘钥')//设置上架秘钥
         ->setPpTradeNo('订单号')//设置订单号 这个参数不是必须的
         ->setNotifyUrl('回调地址')//设置回调地址 注意回调地址不能以 ?a=1&b=2形式带参数
         ->pay();//执行调用 成功返回成功参数 失败抛 Qingtianpay\Exception\PayException 出异常
         
      var_dump($res);
       $res = [
          'resp_code' => '0000',//0000 代表成功 其他情况失败
          'sign' => '589c74aa9f40dee1b56388ed3dbaf8f4',//签名验证
          'resp_msg' => '成功',//成功或失败信息
          'order_sn' => 'POS2018112409284815192',//传递的订单号
          'refresh_url' => 'url',//用来生成二维码的地址
          ];   
         
      //获取调用参数
      $data = $pay->getPostData();
      var_dump($data);
      
      //获取用来生成二维码的地址
      $url = $pay->getRefreshUrl();
      
      //或者也可以直接调用二维码 $size默认为400
      $qrcode = $pay->getQrcode($size=400);
      
            
      
### 商家扫描用户支付条码

      <?php
          
          //实例化 $amount最小值为0.02 authNo为条形码编号
          $pay = new QingtianPay\Pay\ShopScan(float $amount,String $authNo);
          
          $res = $pay->setShopNo('商户号')//设置商户号
              ->setKey('秘钥')//设置上架秘钥
              ->setPpTradeNo('订单号')//设置订单号 这个参数不是必须的
              ->setNotifyUrl('回调地址')//设置回调地址 注意回调地址不能以 ?a=1&b=2形式带参数
              ->pay();//执行调用 成功返回成功参数 失败抛 Qingtianpay\Exception\PayException 出异常
              
           //获取调用参数
           $data = $pay->getPostData();
           var_dump($data);
           
            

### 支付查询结果
    
    <?php
         $check = new  \Qingtian\Pay\CheckOrder($this->order_sn);
              $check->setShopNo($this->shop_no)//设置商户号
              ->setKey($this->key) //设置key
              ->handle();//执行查询操作
         //验证支付结果
         $check->check();//返回结果true或者false;
         //查询结果信息
         $check->getRespMsg();
         //获取结果code
         $check->getResult();
         //获取支付渠道
         $check->getChannel(); // 1支付宝 2 微信 0 其他
         //获取接口返回结果
         {
               "transaction_id": "507172408"
              "total_amount": "2"//支付金额
              "coupon_fee": "0" //优惠金额
              "coupon_fee_no_cash": "0"
              "settlement_total_fee": "2"
              "resp_code": "0000" //支付结果
              "sign": "0bdecb50e06cffef18f512ec76e65d03"
              "pay_type": "WXPAY" //支付类型 ALIPAY
              "resp_msg": "交易成功"
              "order_sn": "POS2018112316304859034"//订单号
              }
            
         $check->getResult(); //返回类型为object
         
           
              

### 退款
    
    <?php  $obj = new Qingtian\Pay\Refund(string $order_sn,string $refund_order_sn,         float  $amount);
           $obj->setShopNo('商户号')->setKey('秘钥')->handle();
           //获取退款结果


### 小程序支付

        <?php
            
           $minProgram  = new Qingtian\Pay\MinProgram($app_id,$opend_id,$amount);
           
            $result = $minProgram->setShopNo('商户号')//设置商户号
                       ->setKey('秘钥')//设置上架秘钥
                       ->setPpTradeNo('订单号')//设置订单号
                       ->setGoodsName('商品名称')//商品名称
                       ->setReamrk('备注')//备注
                       ->setNotifyUrl('回调地址')//设置回调地址 注意回调地址不能以 ?a=1&b=2形式带参数
                       ->handle();//执行调用 成功返回成功参数 失败抛
            var_dump($reesult)'
            {
                "transaction_id": "537603639",
                "payment_channel": "WXPAY",
                "total_amount": "2.0",
                "resp_code": "success",
                "sign": "bfd95cb94402fa7f98ce5452f82defa5",
                "resp_msg": "成功",
                "payInfo": 
                {"appId":"wx124a6dddd9d48614",
                "timeStamp":"1545303611","nonceStr":"a6db0c1c95204421b7d9ad1d53177d26","package":"prepay_id=wx20190011252147248443378a2488922445","signType":"RSA",
                "paySign":"mRpMczHHTWCsXeW7f/xjQn2tJGMp6QJ8eB5Qx3rpmzWEa/GfR/Yy1AQPJ/uGWWH7o37acJSEBO1XN7sbjx4ZmU+84vlrTBQH6BpC/bNhNvCxvx7ZazNEnirY2RY3ib9es401zA22INWMrSIj8E40swhnSOBQHQ2Q3kTPZDqZiDQoPTGOnOyoop7sSuhCU4kBzP9iklGtXHTnIqhZEF1tx3Moqs/OV+imXEgOENPGCcRltNKUCxMefzTwLlNl38Isjhu4dOdMTXU88ZvwigvEFqTKrAVAi+amhYXY3J4WtzcfHlhfhFhLAY+3uVuq5wMJjyiCmp62K7Q4c9qBn1uqPA=="
                },
                
              //获取payInfo的信息还可以这样
              
              var_dump($program->appId);//wx124a6dddd9d48614

                       