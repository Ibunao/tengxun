<?php
namespace app\helpers;
use Yii;
use yii\base\Object;
use app\models\DevMessageModel;
use app\helpers\HttpHelper;
/**
 * 微信开发辅助类
 */
class WchatHelper extends Object
{
    public $postObj;
    public $fromUsername;
    public $toUsername;
    public $time;
    public $keyword;
    public $latitude;
    public $longitude;

    private $wxconfig;

    public function init()
    {
        $this->wxconfig = Yii::$app->params['wxconfig'];
    }

    /**
     * 微信验证服务器
     * @return [type] [description]
     */
    public function valid()
    {
        //接收随机字符串
        $echoStr = $_GET["echostr"];
        $token = $this->wxconfig['voidtoken'];
        //valid signature , option
        //进行用户数字签名验证
        if($this->checkSignature($token)){
            //如果成功，则返回接收到的随机字符串
            echo $echoStr;
            //退出
            exit;
        }
    }

    /**
     * 定义自动回复功能
     * @return [type] [description]
     */
    public function responseMsg()
    {
        //接收用户端发送过来的XML数据
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");


        if (!empty($postStr)){

            libxml_disable_entity_loader(true);
            // 通过simplexml进行xml解析
            $this->postObj = $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            // 手机端  发送方帐号（用户OpenID） 
            $this->fromUsername = $postObj->FromUserName;
            // 开发者微信号（公众号）
            $this->toUsername = $postObj->ToUserName;
            // 接收用户发送的关键词
            $this->keyword = trim($postObj->Content);

            // 时间戳
            $this->time = time();
            // 接收用户消息类型
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
     * 绑定开发者
     * name:level:project
     */
    private function text()
    {
        //判断用户发送关键词是否为空
        if(!empty( $this->keyword ))
        {
            // 存到开发通知表  
            $result = explode(':', $this->keyword);
            // 不开通中文符号了
            // if (count($result) === 1) {
            //     $result = explode('：', $this->keyword);
            // }
            if (count($result) === 3) {
                $name = $result[0];
                $level = $result[1];
                $project = $result[2];
                $model = new DevMessageModel;
                $model->name = $name;
                $model->level = $level;
                $model->project = $project;
                $model->openid = (string)$this->fromUsername;
                if ($model->save()) {
                    $this->sendText('开发者绑定成功');
                }else{
                    $this->sendText('开发者绑定失败'.json_encode($model->errors));
                }
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
        // 回复类型，text 代表文本类型
        $msgType = "text";
        // 格式化字符串
        $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $contentStr);
        // 把XML数据返回给手机端
        echo $resultStr;
    }
    /**
     * 微信-服务器验证token
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    private function checkSignature($token)
    {
        // you must define TOKEN by yourself
        //判断TOKEN密钥是否定义
        if (empty($token)) {
            //如果没有定义抛出异常
            throw new \Exception('TOKEN is not defined!');
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

    /**
     * 获取 token
     * @return [type] [description]
     */
    public function getToken($renew = false)
    {
        $access_token = Yii::$app->cache->get('wx_access_token1');
        if($renew || !$access_token){
            $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.
                $this->wxconfig['app_id'].'&secret='.$this->wxconfig['app_secret'];
            $json_data = HttpHelper::get($access_token_url);
            $json_data = json_decode($json_data,true);
            $access_token = $json_data['access_token'];
            Yii::$app->cache->set('wx_access_token1', $access_token, 7000);
        }
        return $access_token ;
    }
    /**
     * 发送模板消息
     * @param  [type] $openId     要发送用户的openid
     * @param  [type] $templateId 模板类型
     * @param  [type] $data       模板消息数据
     * @param  [type] $Snippet    摘要消息
     * @param  string $url        点击消息是的url
     * @return [type]             [description]
     */
    public function sendTemplateMsg($openId, $type, $data, $snippet = '',$url = '')
    {
        // 获取模板id
        $templateId = Yii::$app->params['wxconfig']['template'][$type];
        $token = $this->getToken();
        $wxurl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
        $arr = [
            'touser' => $openId,//用户open_id
            'template_id' => $templateId,
            'url' => $url,
            'data' => $data,
        ];
        $postJson = json_encode($arr);
        $res = HttpHelper::post($wxurl, $postJson);
        $res = json_decode($res);
        if ($res['errcode'] != 0) {
            $this->getToken(true);
        }
        // 保存发送的通知
        Yii::$app->db->createCommand()->insert('wx_message_log', [
                'openid' => $openId,
                'type' => $type,
                'snippet' => $snippet,
                'errcode' => $res['errcode'],
                'errmsg' => $res['errmsg'],
                'msgid' => $res['msgid']?:'',
            ])->execute();
        return $res;
    }
}
