<?php


namespace Pay;


interface PayInterface
{

    /**
     * PayInterface constructor.
     * @param string $shopNo 商户号
     * @param string $key  秘钥
     */
    public function __construct(string $shopNo,string $key);

    /**
     * @return mixed
     */
    public function handle();

      /**
     * @return array
     */
      public function getPostData():array ;
}