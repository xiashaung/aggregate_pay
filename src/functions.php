<?php

/**
 * @param array $data
 * @param string $machKey
 * @return string
 * 验证方法
 */
function makeSign(array $data,string $machKey)
{
    //对数组进行排序
    ksort($data);
    $tmp = '';
    foreach ($data as $key => $rs) {
        if (is_array($rs)) {
            ksort($rs);
            foreach ($rs as $k => $v) {
                $tmp .= $k . '=' . $v . '&';
            }
        } else {
            $tmp .= $key . '=' . $rs . '&';
        }
    }
    $sign = trim($tmp, '&');
    $sign .= '&key=' . $machKey;
    return  md5($sign);
}
