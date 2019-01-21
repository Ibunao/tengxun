<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use app\helpers\HttpHelper;

class WechatController extends Controller
{
    public function actionInfo()
    {
    	$result = HttpHelper::httpCurl('https://m.octmami.com/wechat/info');
    	echo json_encode($result);
    }
}