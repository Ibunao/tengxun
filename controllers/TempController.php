<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\helpers\VarDumper;
use app\behaviors\TempAction;

class TempController extends Controller
{
	public $enableCsrfValidation = false;
	public function behaviors()
	{
		return [
			TempAction::className(),
		];	
	}
	public function actionIndex()
	{
		Yii::$app->cache->set('temp-params', ['get' => $_GET, 'post' => $_POST, 'input' => file_get_contents('php://input')]);
	}
	public function actionPrint()
	{
		// VarDumper::dump(Yii::$app->cache->get('temp-params'));
		echo Yii::$app->cache->get('temp-params')['input'];
	}

	public function beforeAction($action)
	{
		echo "beforeAction";
		return parent::beforeAction($action);
	}
	public function actionFilter()
	{
		echo "here";
		return;
	}
}