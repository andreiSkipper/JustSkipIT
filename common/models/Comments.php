<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use common\models\Translations;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property integer $reply_id
 * @property integer $user_id
 * @property integer $action_id
 * @property string $content
 * @property boolean $status
 * @property string $location
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
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
            [['user_id', 'action_id', 'content'], 'required'],
            [['user_id', 'action_id'], 'integer'],
            [['content'], 'string', 'max' => 255],
            [['reply_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Translations::translate('app', 'ID'),
            'reply_id' => Translations::translate('app', 'Reply to'),
            'user_id' => Translations::translate('app', 'User'),
            'action_id' => Translations::translate('app', 'Action'),
            'content' => Translations::translate('app', 'Content'),
            'status' => Translations::translate('app', 'Status'),
            'location' => Translations::translate('app', 'Location'),
            'ip' => Translations::translate('app', 'IP'),
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
    * get action
    */
    public function getAction()
    {
        return $this->hasOne(Actions::className(), ['id' => 'action_id']);
    }

    /*
    * get replies
    */
    public function getReplies()
    {
        return $this->hasMany(Comments::className(), ['reply_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }

    public function search($params)
    {
        // get models
        $commentsTable = Comments::tableName();
        $query = Comments::find();
        // create data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["id", "reply_id", "user_id", "action_id", "content", "status", "location", "ip", "created_at", "updated_at"];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc' => [$addSortAttribute => SORT_ASC],
                'desc' => [$addSortAttribute => SORT_DESC],
            ];
        }

        $query->orderBy(['created_at' => SORT_ASC]);

        if (count($params) == 0) {
            return $dataProvider;
        } else {
            Comments::load($params);
        }

        $query->andFilterWhere([
            "{$commentsTable}.id" => $this->id,
        ]);

        $query->andFilterWhere([
            "{$commentsTable}.reply_id" => $params['reply_id'],
        ]);

        $query->andFilterWhere([
            "{$commentsTable}.user_id" => $params['user_id'],
        ]);

        $query->andFilterWhere([
            "{$commentsTable}.ip" => $params['ip'],
        ]);

        // get like
        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        if (isset($params['sort'])) {
            if (substr($params['sort'], 0, 1) == ' - ') {
                $query->orderBy(substr($params['sort'], 1, strlen($params['sort']) - 1) . ' DESC');
            } else {
                $query->orderBy($params['sort'] . ' ASC');
            }
        }
        return $dataProvider;
    }
}
