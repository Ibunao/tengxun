<?php
namespace app\behaviors;

use yii\base\ActionFilter;
/**
* 控制器过滤器
*/
class TempAction extends ActionFilter
{
	
	public function beforeAction($action)
	{
		echo "之前";
		return true;
	}

	public function afterAction($action, $result)
	{
		echo "之后";
		return $result;
	}
}