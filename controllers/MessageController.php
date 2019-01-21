<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use app\helpers\WchatHelper;
use app\models\DevMessageModel;

class MessageController extends Controller
{
	public $enableCsrfValidation = false;
	public function init()
	{
		parent::init();
		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
	}
	/**
	 * 通过post或者get来获取参数
	 * @param  [type] $name    参数
	 * @param  string $default 默认值
	 * @return [type]          [description]
	 */
	public function getParam($name, $default = '')
	{
		$req = Yii::$app->request;
		return $req->post($name)?:($req->get($name)?:$default);
	}
	/**
	 * 自动回复，用来绑定开发者
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$helper = new WchatHelper;
		// 1.验证服务器
		// $helper->valid();
		// 2.自动回复
        $helper->responseMsg();
	}
	/**
	 * 发送开发者通知
	 * @return [type] [description]
	 */
	public function actionSendDev()
	{
        // 错误信息
        $info = $this->getParam('info', '');
        /**
         * 错误等级 这接收 2 和 3
         * 2 info
         * 3 error
         * 5 info & error
         */
        $level = $this->getParam('level', 2);
        $project = $this->getParam('project', '');
        $model = new DevMessageModel;
        $result = $model->sendErrorMessage($level, $info, $project);
	}
}