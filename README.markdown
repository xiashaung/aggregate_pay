##擎天付 PHP SDK

###引入

    composer require xiashuang/qingtianpay

###支持方式
    
    1. 用户扫描商家二维码 (微信/支付宝)
    2. 商家扫描用户付款码(微信/支付宝)
    
##使用

###用户扫描商家收款码
    
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
      
            
      
###商家扫描用户支付条码

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
           
            

###返回参数说明

  
            