<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;

class TestController extends Controller
{
	public $enableCsrfValidation = false;
	public function init()
	{
		parent::init();
		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
	}
	/**
	 * 返回请求的参数
	 * @return [type] [description]
	 */
	public function actionRequest()
	{
		setcookie("TestCookie", 'test');
		$data = [
			'server' => $_SERVER,
			'get' => $_GET,
			'post' => $_POST,
			'cookie' => $_COOKIE,
			'rowBody' => file_get_contents('php://input'),
			'file' => $_FILES,
		];
		// var_dump($data);
		return $data;
	}
	/**
	 * 获取请求头
	 * @return [type] [description]
	 */
	public function actionHeader()
	{
		return Yii::$app->request->getHeaders();
	}
}