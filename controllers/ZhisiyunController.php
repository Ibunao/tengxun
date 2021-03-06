<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use app\helpers\HttpHelper;

class ZhisiyunController extends Controller
{
	public $enableCsrfValidation = false;
	public function init()
	{
		parent::init();
		$this->layout = 'one';
		# 解绑debug前端页面显示
		if (class_exists('\yii\debug\Module')) {
		    Yii::$app->view->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
		}
	}
	
	public function actionClock()
	{
		$users = [
			'丁冉' => '597550941f62939e2913bb43',
			'凯哥' => '591951f535ba257f1af69451',
			'袁勃' => '591951f535ba257f1af69456',
		];
		$info = [
			'position' => '嘉定总部',
			'longitude' => 121.20988,
			"latitude" => 31.408887,
			"distance" => 67.59726443498181
		];
		return $this->render('clock', ['info' => $info, 'users' => $users]);
	}
	public function actionPinche()
	{
		$users = [
			'袁海燕' => '5b8c903b4323599534200b5c',
			'仲鲤' => '5938c0089924bfcf6890ac06',
			'王敏' => '591951f535ba257f1af69460',
		];
		$info = [
			'position' => '嘉定总部',
			'longitude' => 121.20988,
			"latitude" => 31.408887,
			"distance" => 67.59726443498181
		];
		return $this->render('clock', ['info' => $info, 'users' => $users]);
	}
	public function actionPinche1()
	{
		$users = [
			'袁海燕' => '5b8c903b4323599534200b5c',
			'仲鲤' => '5938c0089924bfcf6890ac06',
			'王敏' => '591951f535ba257f1af69460',
		];
		
		return $this->render('clock1', ['users' => $users]);
	}
	public function actionClock1()
	{
		$info = [
			'position' => '嘉定总部',
			'longitude' => 121.20988,
			"latitude" => 31.408887,
			"distance" => 67.59726443498181
		];

	}
	public function actionLogin()
	{
		$req = Yii::$app->request;
		$userId = $req->post('userId');
		if (empty($userId)) {
			$userId = $req->get('userId');
		}
		ob_start();
		passthru('python3 /www/pythontest/zhisiyun.py '.$userId);
		$output = ob_get_contents();
		ob_end_clean(); //Use this instead of ob_flush()
		echo($output);
		
		// $response = Yii::$app->response;
		// $response->format = Response::FORMAT_JSON;

		// $url = 'https://www.zhisiyun.com/wxapp/login_user';
		// $temp = HttpHelper::httpCurl($url, 'post', ['userid' => $userId]);
		// if ($temp == '登录成功') {
		// 	$url = 'https://www.zhisiyun.com/admin/clock_method/clockInOut';
		// 	$info = [
		// 		'position' => '嘉定总部',
		// 		'longitude' => 121.20988,
		// 		"latitude" => 31.408887,
		// 		"distance" => 67.59726443498181
		// 	];
		// 	$temp = HttpHelper::httpCurl($url, 'post', $info);
		// 	return [$temp];
		// }else{
		// 	return ['msg' => '登录失败'];
		// }
	}
}