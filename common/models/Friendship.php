<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * This is the model class for table "friendships".
 *
 * @property integer $ID
 * @property integer $user_from
 * @property integer $user_to
 * @property string $status
 *
 * @property Country $countryCode
 */
class Friendship extends \yii\db\ActiveRecord
{
    CONST STATUS_REQUESTED = 'Requested';
    CONST STATUS_ACCEPTED = 'Accepted';
    CONST STATUS_REFUSED = 'Refused';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'friendships';
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
            [['user_from', 'user_to'], 'required'],
            [['user_from', 'user_to'], 'integer'],
            [['status'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Translations::translate('app', 'ID'),
            'user_from' => Translations::translate('app', 'From user'),
            'user_to' => Translations::translate('app', 'To user'),
            'status' => Translations::translate('app', 'Status'),
        ];
    }

    /*
    * get user from
    */
    public function getUserFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'user_from']);
    }

    /*
    * get user from
    */
    public function getUserTo()
    {
        return $this->hasOne(User::className(), ['id' => 'user_to']);
    }

    public static function searchForFriendship($user_id)
    {
        return Friendship::find()->where(['user_from' => $user_id, 'user_to' => Yii::$app->user->id])->orWhere(['user_from' => Yii::$app->user->id, 'user_to' => $user_id])->one();
    }

    public static function getFriendRequestsForCurrent()
    {
        $friendships['Requests'] = Friendship::find()->where(['user_to' => Yii::$app->user->id, 'status' => Friendship::STATUS_REQUESTED])->all();
        $friendships['Requested'] = Friendship::find()->where(['user_from' => Yii::$app->user->id, 'status' => Friendship::STATUS_REQUESTED])->all();

        return $friendships;
    }

    public function search($params)
    {
        // get models
        $friendshipsTable = Friendship::tableName();
        $query = Friendship::find();
        // create data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["id", "user_from", "user_to", "status", "created_at", "updated_at"];
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
            Friendship::load($params);
        }

        $query->andFilterWhere([
            "{$friendshipsTable}.id" => $this->id,
        ]);

        $query->andFilterWhere([
            "{$friendshipsTable}.user_from" => $params['user_from'],
        ]);

        $query->andFilterWhere([
            "{$friendshipsTable}.user_to" => $params['user_to'],
        ]);

        $query->andFilterWhere([
            "{$friendshipsTable}.status" => $params['status'],
        ]);

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
