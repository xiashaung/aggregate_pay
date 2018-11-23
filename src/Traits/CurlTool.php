<?php


namespace Qingtian\Traits;


trait CurlTool
{
    /**
     * @param $url
     * @param array $data
     * @return mixed
     * get请求地址
     */
    public function get($url, $data = [])
    {
        if ($data) $url .= http_build_query($data);
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $this->decodeInfo($tmpInfo);    //返回json对象
    }

    /**
     * @param $url
     * @param $data
     * @return mixed
     * post请求
     */
    public function post($url, $data)
    {
        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        $ch = curl_init(); // 启动一个CURL会话
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length:' . strlen($data)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($ch); // 执行操作
        if (curl_errno($ch)) {
            echo 'Errno' . curl_error($ch);//捕抓异常
        }
        curl_close($ch); // 关闭CURL会话
        return $this->decodeInfo($tmpInfo); // 返回数据，json格式
    }

    protected function decodeInfo($info)
    {
        return json_decode($info);
    }
}