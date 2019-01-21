<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\helpers\WchatHelper;
/**
 * This is the model class for table "wx_message_for_dev".
 *
 * @property string $id
 * @property integer $level
 * @property string $name
 * @property string $openid
 * @property string $project
 * @property integer $created_at
 * @property integer $updated_at
 */
class DevMessageModel extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_message_for_dev';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'name', 'openid', 'project'], 'required'],
            [['level', 'created_at', 'updated_at'], 'integer'],
            [['name', 'openid', 'project'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Level',
            'name' => 'Name',
            'openid' => 'Openid',
            'project' => 'Project',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    /**
     * 向开发者发送模板消息
     * @param  [type] $level   [description]
     * @param  [type] $info    [description]
     * @param  [type] $project [description]
     * @return [type]          [description]
     */
    public function sendErrorMessage($level, $info, $project)
    {
        $lev = 'null';
        if ($level == 2) {
            $lev = 'info';
        }else ($level == 3) {
            $lev = 'error';
        }

        $temp = self::find()
            ->select('openid')
            ->where(['project' => $project, 'level' => [5, $level]])
            ->asArray()
            ->all();
        if (empty($temp)) {
            return;
        }
        $data = [];
        $data['first'] = ['value'=> $project.'项目报警', 'color'=>"#173177"];
        // 系统名称
        $data['keyword1'] = ['value'=> $project, 'color'=>"#173177"];
        // 报警时间
        $data['keyword2'] = ['value'=> date("Y-m-d H:i:s"), 'color'=>"#173177"];
        // 报警级别
        $data['keyword3'] = ['value'=> $lev, 'color'=>"#173177"];
        // 报警信息
        $data['remark'] = ['value'=> $info, 'color'=>"#173177"];
        // 模板类型，可以获取模板id
        $type = 'dev';
        $wchat = new WchatHelper;
        foreach ($temp as $key => $item) {
            $openId = $item['openid'];
            $result = $wchat->sendTemplateMsg($openId, $type, $data, $info);
        }
    }
}
