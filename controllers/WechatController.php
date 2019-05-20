<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use app\helpers\HttpHelper;
use yii\db\Query;

class WechatController extends Controller
{
    /**
     * 已关注用户静默获取openid
     * @return [type] [description]
     */
    public function actionGetOpenid()
    {
        $appId = Yii::$app->params['wxconfig']['app_id'];
        $state = 1; // 1 为正式
        // 回调地址
        $redirect_uri = urlencode('http://temp.wuxingxiangsheng.com/wechat/list');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
        header("Location:".$url);
    }
    /**
     * 获取添加的列表
     * @return [type] [description]
     */
    public function actionList()
    {
        // 获取openid
        $openid = (new WchatHelper)->getOpenid();
        if (!empty($openid)) {
        	$list = (new Query)->from('');
            $agent = (new AgentUserModel())->findOne(['openid' => $openid]);
            // 如果不是被拒绝
            if (!empty($agent) && $agent->status == 2) {
                // 把openid存入到cookie
                $cookies = Yii::$app->response->cookies;
                $cookie = new Cookie(['name' => 'agentId', 'value' => $agent->id]);
                $cookies->add($cookie);
                $this->redirect('/site/join-us');return;
            }
        }
        
        return $this->render('list', [
            'model' => $model,
        ]);
    }
}