<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use common\models\Translations;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $model
 * @property string $type
 * @property string $status
 * @property boolean $read
 * @property integer $created_at
 * @property integer $updated_at
 */
class Notifications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'required'],
            [['user_id'], 'integer'],
            [['model', 'type', 'status', 'read'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Translations::translate('app', 'ID'),
            'user_id' => Translations::translate('app', 'User'),
            'model' => Translations::translate('app', 'Model'),
            'type' => Translations::translate('app', 'Type'),
            'status' => Translations::translate('app', 'Status'),
            'created_at' => Translations::translate('app', 'Created'),
            'updated_at' => Translations::translate('app', 'Updated'),
        ];
    }

    /*
    * get user
    */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /*
    * get model
    */
    public function getModel()
    {
        if (empty($this->model)) {
            return $this->model;
        } else {
            return json_decode($this->model);
        }
    }

    public function getDescription()
    {
        switch ($this->type) {
            case Comments::tableName():
                $comment = Comments::find()->where(['id' => $this->getModel()->id])->one();
                $user = User::find()->where(['id' => $this->getModel()->user_id])->one();
                if ($this->status == 'Removed') {
                    return $user->fullName . ' ' . strtolower($this->status) . ' a comment from your post.';
                } else {
                    return $user->fullName . ' ' . strtolower($this->status) . ' comment "' . $comment->content . '" to your post.';
                }
                break;
            case Friendship::tableName():
                break;
        }
        return '';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }
}
