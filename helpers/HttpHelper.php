<?php
namespace app\helpers;
use Yii;
use yii\base\Object;
/**
 * 请求辅助
 */
class HttpHelper extends Object
{
	 /**
     * 错误编码
     */
    public static $errCode = 0;

    /**
     * 错误信息,无错误为''
     */
    public static $errMsg = '';
    /**
     * 清除错误信息,在每个函数的开始调用
     */
    private static function clearError()
    {
        self::$errCode = 0;
        self::$errMsg = '';
    }
    /**
     * 使用 cURL 实现 HTTP GET 请求
     *
     * @param       string          $url, 请求地址
     * @param       string          $host, 服务器 host 名, 默认为空(当一台机器有多个虚拟主机时需要指定 host)
     * @param       int             $timeout, 连接超时时间, 默认为2
     * @param       array           $$header_append, 追加的header头
     * @param       int             $failOnError, 连接超时时间, 默认为2
     *
     * @return      sting/bool      $data, 为返回数据, 失败返回 false
     */
    public static function get($url, $timeout = 30, $host = '', $header_append = array(), $failOnError = true)
    {
        self::clearError();
        // 设置header头
        $header = array('Content-transfer-encoding: text');
        if (!empty($header_append))
        {
            foreach ($header_append as $v)
            {
                $header[] = $v;
            }
        }
        if (!empty($host))
        {
            $header[] = 'Host: ' . $host;
        }

        $curl_handle = curl_init();

        // 连接超时
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, $timeout);
        // 执行超时
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 300);
        // HTTP返回错误时, 函数直接返回错误
        curl_setopt($curl_handle, CURLOPT_FAILONERROR, $failOnError);
        // 允许重定向
        curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
        // 允许重定向的最大次数
        curl_setopt($curl_handle, CURLOPT_MAXREDIRS, 2);
        // ssl验证host
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        // 返回为字符串
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        // 设置HTTP头
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $header);
        // 指定请求地址
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        // 执行请求
        $response = curl_exec($curl_handle);
        if ($response === false)
        {
            self::$errCode = 10615;
            self::$errMsg = 'cURL errno: ' . curl_errno($curl_handle) . '; error: ' . curl_error($curl_handle);
            // 关闭连接
            curl_close($curl_handle);

            return false;
        }

        // 关闭连接
        curl_close($curl_handle);

        return $response;
    }

    /**
     * 使用 cURL 实现 HTTP POST 请求
     *
     * @param       string          $url, 请求地址
     * @param       string          $post_data, 请求的post数据，一般为经过urlencode 和用&处理后的字符串
     * @param       string          $host, 服务器 host 名, 默认为空(当一台机器有多个虚拟主机时需要指定 host)
     * @param       int             $timeout, 连接超时时间, 默认为2
     *
     * @return      sting/bool      $data, 为返回数据, 失败返回 false
     */
    public static function post($url, $post_data, $timeout = 30, $host = '', $header_append = array(), $failOnError = true)
    {
        self::clearError();
        # 只能是一维数组
        if (is_array($post_data)) {
            $data_len = count($post_data);
            $header = array();
        }else{
            $data_len = strlen($post_data);
            $header = array('Content-transfer-encoding: text', 'Content-Length: ' . $data_len);
        }

        if (!empty($header_append))
        {
            foreach ($header_append as $v)
            {
                $header[] = $v;
            }
        }
        if (!empty($host))
        {
            $header[] = 'Host: ' . $host;
        }

        $curl_handle = curl_init();

        // 连接超时
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, $timeout);
        // 执行超时
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 300);
        // HTTP返回错误时, 函数直接返回错误
        curl_setopt($curl_handle, CURLOPT_FAILONERROR, $failOnError);
        // 允许重定向
        curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
        // 允许重定向的最大次数
        curl_setopt($curl_handle, CURLOPT_MAXREDIRS, 2);
        // ssl验证host
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        // 返回为字符串
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        // 设置HTTP头
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $header);
        // 指定请求地址
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        //设置为post方式
        curl_setopt($curl_handle, CURLOPT_POST, TRUE);
        //post 参数
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_data);
        // 执行请求
        $response = curl_exec($curl_handle);
        if ($response === false)
        {
            self::$errCode = 10616;
            self::$errMsg = 'cURL errno: ' . curl_errno($curl_handle) . '; error: ' . curl_error($curl_handle);
            // 关闭连接
            curl_close($curl_handle);

            return false;
        }
        // 关闭连接
        curl_close($curl_handle);

        return $response;
    }

    /**
     * 请求后，记录请求相应日志
     * @return [type] [description]
     */
    public function afterRequest()
    {
        
    }
    /**
     * 传入post数组，转换成字符串,数组不转也可以
     * @param  [type] $arr post参数数组
     * @return [type]      [description]
     */
    public static function postData($arr)
    {
        $result = '';
        foreach ($arr as $k => $v) {
            $result.= "{$k}=" . urlencode($v) . "&";
        }
        return $result;
    }
}