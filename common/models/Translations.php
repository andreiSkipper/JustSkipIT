<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use Statickidz\GoogleTranslate;

/**
 * This is the model class for table "translations".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 * @property string $en
 * @property string $ro
 * @property integer $created_at
 * @property integer $updated_at
 */
class Translations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'translations';
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
            [['en', 'ro'], 'required'],
            [['en', 'ro'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['category'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Translations::translate('app', 'id'),
            'category' => Translations::translate('app', 'Category'),
            'en-US' => Translations::translate('app', 'English'),
            'ro-RO' => Translations::translate('app', 'Romanian'),
            'created_at' => Translations::translate('app', 'Created'),
            'updated_at' => Translations::translate('app', 'Updated'),
        ];
    }

    public function search($params)
    {
        // get models
        $actionsTable = Actions::tableName();
        $query = Actions::find();
        // create data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["id", "user_id", "type", "description", "location", "privacy", "created_at", "updated_at"];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc' => [$addSortAttribute => SORT_ASC],
                'desc' => [$addSortAttribute => SORT_DESC],
            ];
        }

        $query->orderBy(['created_at' => SORT_DESC]);

        if (count($params) == 0) {
            return $dataProvider;
        } else {
            Actions::load($params);
        }

        $query->andFilterWhere([
            "{$actionsTable}.id" => $this->id,
        ]);

        if (isset($params['user_id'])) {
            $query->andFilterWhere([
                "{$actionsTable}.user_id" => $params['user_id'],
            ]);
        }

        // get like
        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'privacy', $this->privacy])
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

    public static function translate($category, $message)
    {
        if (!Yii::$app->user->isGuest) {
            /* @var $currentUser User */
            $currentUser = Yii::$app->user->getIdentity();
            Yii::$app->session['language'] = $currentUser->language;
        }

        switch (Yii::$app->session['language']) {
            case 'en-US':
                return $message;
                break;
            case 'ro-RO':
                $translation = Translations::find()->where(['category' => $category, 'message' => $message])->one();

                if (empty($translation)) {
                    $translation = new Translations();
                    $translation->category = $category;
                    $translation->message = $message;
                    $translation->en = $message;
                    $translation->ro = GoogleTranslate::translate('en', 'ro', $message);
                    $translation->save();
                }

                if ($translation->en == $translation->ro) {
                    $translation->ro = GoogleTranslate::translate('en', 'ro', $message);
                    $translation->save();
                }

                return $translation->ro;
                break;
            default:
                return $message;
                break;
        }
    }
}
