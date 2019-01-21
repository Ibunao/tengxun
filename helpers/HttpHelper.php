<?php
namespace app\helpers;
use Yii;
use yii\base\Object;
/**
 * 请求辅助
 */
class HttpHelper extends Object
{
	public static function httpCurl($url, $type = 'get', $postData = [])
    {
        //1.初始化curl
        $ch = curl_init();
        //2.设置curl的参数
        //设置请求的url
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置是直接打印出来 ture不打印
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);//设置为post请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);//设置请求的参数
        }
        //3.采集
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_errno($ch);
        }
        //4.关闭
        curl_close($ch);
        
        return $output;
    }
}