<?php
namespace app\helpers;
use Yii;
use yii\base\Object;
/**
 * 微信开发辅助类
 */
class WchatHelper extends Object
{
    public function init()
    {
        
    }
    public $env = 'prod';
    public $wxconfigTest = [
        '66' => [//666公众号
            'app_id' => 'wx3b2443e8747a2e43',
            'app_secret' => 'd1dd7649fc17cbf79495951a3ed293d3',
            'voidtoken' => 'yuanbo',
        ],
        // '88' => [//888公众号
        //     'app_id' => 'wx5540adad05abc1cc',
        //     'app_secret' => 'e23aba3e1c0fe9d63667009b3c2aa09c',
        // ],
    ];
    public $wxconfig = [
        '66' => [//666公众号
            'app_id' => 'wx297d834cea4e3b54',
            'app_secret' => '846b532deb073c12ed08f94c04a4b9e6',
            'voidtoken' => 'Octmami54741425481',
        ],
        '88' => [//888公众号
            'app_id' => 'wx5540adad05abc1cc',
            'app_secret' => 'e23aba3e1c0fe9d63667009b3c2aa09c',
            'voidtoken' => 'Octmami54741425481',
        ],
    ];

    public $postObj;
    public $fromUsername;
    public $toUsername;
    public $time;
    public $keyword;
    public $latitude;
    public $longitude;


    public function __construct()
    {
        parent::__construct();
        if ($this->env == 'test') {
            $this->wxconfig = $this->wxconfigTest;
        }
    }


    //实现valid验证方法：实现对接微信公众平台
    public function valid666()
    {
        //接收随机字符串
        $echoStr = $_GET["echostr"];
        $token = $this->wxconfig['66']['voidtoken'];
        //valid signature , option
        //进行用户数字签名验证
        if($this->checkSignature($token)){
            //如果成功，则返回接收到的随机字符串
            echo $echoStr;
            //退出
            exit;
        }
    }

    //定义自动回复功能
    public function responseMsg666()
    {
        //get post data, May be due to the different environments
        //接收用户端发送过来的XML数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        // Yii::app()->cache->set('mendian', $postStr);
        //extract post data
        //判断XML数据是否为空
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            //通过simplexml进行xml解析
            $this->postObj = $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            //手机端  发送方帐号（一个OpenID） 
            $this->fromUsername = $postObj->FromUserName;
            // 获取openid
            // Yii::$app->cache->set('openid', (string)$this->fromUsername);
            //开发者微信号（公共号）
            $this->toUsername = $postObj->ToUserName;
            //接收用户发送的关键词
            $this->keyword = trim($postObj->Content);

            //时间戳
            $this->time = time();
            //接收用户消息类型
            $msgType = $postObj->MsgType;
            
            switch ($msgType) {
                //关注或取消关注是触发的事件消息类型
                case 'event':
                    $this->event();
                    break;
                //用户发送的文本信息类型
                case 'text':
                    $this->text();
                    break;
                //用户发送的是图片
                // case 'image':
                //     //回复内容
                //     $this->image($postObj);
                //     break;
                // // 用户发送的语音
                // case 'voice':
                //     $this->voice($postObj);
                //     break;
                // // 用户发送的视频
                // case 'video':
                //     $this->video($postObj);
                //     break;
                // // 用户发送的视频
                // case 'shortvideo':
                //     $this->shortvideo($postObj);
                //     break;
                // //用户发送的是位置
                // case 'location':
                //     $this->location();
                //     break;
                // case 'link':
                //     $this->link($postObj);
                //     break;
                default:
                    # code...
                    break;
            }
        }else {
            echo "";
            exit;
        }
    }
    /**
     * 获取目录
     * @return [type] [description]
     */
    public function getMenu666()
    {
        $token = (new WchatModel)->getToken666();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$token}";
        $res = (new WchatModel)->http_curl($url);
        Yii::app()->cache->set('menu666', $res);
        echo json_encode($res);
    }
    /**
     * 创建菜单
     */
    public function menu666()
    {
        $token = (new WchatModel)->getToken666();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
        //定义菜单   post请求参数
        $postArr = [
            'button'=>[
                
                // 第一个一级菜单
                
                // url跳转按钮
                [
                    'name'=>urlencode('畅销榜单'),
                    'type'=>'view',
                    'url'=>'https://m.octmami.com/event-977.html',
                ],

                //第二个一级菜单
                // 点击事件类型
                [
                    'name'=>urlencode('十月商城'),//这样防止转json中文会成\uxxx的形式
                    'type'=>'view',
                    'url'=>'https://m.octmami.com/',
                ],
                //第三个一级菜单
                [
                    'name'=>urlencode('我的十月'),
                    //定义子菜单
                    'sub_button'=>[
                        [
                            'name'=>urlencode('十月会员'),
                            'type'=>'view',
                            'url'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx297d834cea4e3b54&redirect_uri=https://m.octmami.com/get_weixin_code&response_type=code&scope=snsapi_base&state=#wechat_redirect',
                        ],
                        // url跳转按钮
                        [
                            'name'=>urlencode('店员中心'),
                            'type'=>'view',
                            'url'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx297d834cea4e3b54&redirect_uri=https://m.octmami.com/get_clerk_weixin_code&response_type=code&scope=snsapi_base&state=#wechat_redirect',
                        ],
                        [
                            'name'=>urlencode('防伪查询'),
                            'type'=>'view',
                            'url'=>'http://www.anjismart.com/m/symm/',
                        ],
                        [
                            'name'=>urlencode('全国门店'),
                            'type'=>'view',
                            'url'=>'https://m.octmami.com/wchatstore.html',
                        ],
                        [
                            'name'=>urlencode('积分换花生'),
                            'type'=>'view',
                            'url'=>'http://mp.weixin.qq.com/s/xZ1Uucu3YNedpho5YegtYg',
                        ],
                    ]
                ],
            ]
        ];
        $postJson = urldecode(json_encode($postArr));
        $res = (new WchatModel)->http_curl($url, 'post', 'json', $postJson);
        var_dump($res);
    }

    //实现valid验证方法：实现对接微信公众平台
    public function valid888()
    {
        //接收随机字符串
        $echoStr = $_GET["echostr"];
        $token = $this->wxconfig['88']['voidtoken'];
        //valid signature , option
        //进行用户数字签名验证
        if($this->checkSignature($token)){
            //如果成功，则返回接收到的随机字符串
            echo $echoStr;
            //退出
            exit;
        }
    }

    //定义自动回复功能
    public function responseMsg888()
    {
        //get post data, May be due to the different environments
        //接收用户端发送过来的XML数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        // Yii::app()->cache->set('mendian', $postStr);
        //extract post data
        //判断XML数据是否为空
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            //通过simplexml进行xml解析
            $this->postObj = $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            //手机端  发送方帐号（一个OpenID） 
            $this->fromUsername = $postObj->FromUserName;
            // 获取openid
            // Yii::$app->cache->set('openid', (string)$this->fromUsername);
            //开发者微信号（公共号）
            $this->toUsername = $postObj->ToUserName;
            //接收用户发送的关键词
            $this->keyword = trim($postObj->Content);
            //时间戳
            $this->time = time();
            //接收用户消息类型
            $msgType = $postObj->MsgType;
            
            switch ($msgType) {
                //关注或取消关注是触发的事件消息类型
                case 'event':
                    $this->event();
                    break;
                //用户发送的文本信息类型
                case 'text':
                    $this->text();
                    break;
                default:
                    # code...
                    break;
            }
        }else {
            echo "";
            exit;
        }
    }
    /**
     * 事件消息
     * @return [type] [description]
     */
    private function event()
    {

    }
    /**
     * 用户发送文本消息
     */
    private function text()
    {
        //判断用户发送关键词是否为空
        if(!empty( $this->keyword ))
        {
            // 存到开发通知表  
            $result = explode(':', $this->keyword);
            if (count($result) === 1) {
                $result = explode('：', $this->keyword);
            }
            if (count($result) === 3) {
                $name = $result[0];
                $level = $result[1];
                $project = $result[2];
                // 添加/更新数据  
                $model = new WchatModel();
                $model->saveDev($name, $level, $project, (String)$this->fromUsername);
                $this->sendText('开发者绑定成功');
            }

        }else{
            echo "Input something...";
        }
        
    }
    /**
     * 发送文本消息
     * @param  string $contentStr 要发送的内容
     */
    private function sendText($contentStr)
    {
        //文本发送模板
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>"; 
        //回复类型，如果为“text”，代表文本类型
        $msgType = "text";
        //格式化字符串
        $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $contentStr);
        //把XML数据返回给手机端
        echo $resultStr;
    }
    //定义checkSignature
    private function checkSignature($token)
    {
        // you must define TOKEN by yourself
        //判断TOKEN密钥是否定义
        if (empty($token)) {
            //如果没有定义抛出异常
            throw new Exception('TOKEN is not defined!');
        }
        //接收微信加密签名
        $signature = $_GET["signature"];
        //接收时间戳
        $timestamp = $_GET["timestamp"];
        //接收随机数
        $nonce = $_GET["nonce"];
        //把相关参数组装为数组（密钥、时间戳、随机数）
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        //通过字典法进行排序
        sort($tmpArr, SORT_STRING);
        //把排序后的数组转化字符串
        $tmpStr = implode( $tmpArr );
        //通过哈希算法对字符串进行加密操作
        $tmpStr = sha1( $tmpStr );
        
        //与加密签名进行对比
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
