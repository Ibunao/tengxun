<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;

class MessageController extends Controller
{
	public $enableCsrfValidation = false;
	public function init()
	{
		parent::init();
		$response = Yii::$app->response;
		$response->format = Response::FORMAT_JSON;
	}
	
}