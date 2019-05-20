<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use app\helpers\HttpHelper;
/**
 * 智思云打卡
 */
class DakaController extends Controller
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

	

}