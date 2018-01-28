<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use common\models\Translations;

/**
 * This is the model class for table "actions".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $imagePath
 * @property string $description
 * @property string $location
 * @property string $privacy
 * @property string $fealing
 * @property string $tags
 * @property string $likes
 * @property integer $created_at
 * @property integer $updated_at
 */
class Actions extends \yii\db\ActiveRecord
{
    public $typeEnum = [
        'Photo' => 'Photo',
        'Link' => 'Link',
        'Status' => 'Status',
        'Album' => 'Album',
        'Video' => 'Video',
        'Other' => 'Other',
        'Avatar' => 'Avatar',
        'Cover' => 'Cover',
        'newAccount' => 'newAccount',
    ];

    public $privacyEnum = [
        'Public' => 'Public',
        'Friends of Friends' => 'Friends of Friends',
        'Friends' => 'Friends',
        'Only Me' => 'Only Me',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'actions';
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
            [['user_id', 'type'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['likes'], 'string'],
            [['type', 'imagePath', 'description', 'location', 'privacy', 'fealing', 'tags'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'imagePath' => Yii::t('app', 'Image Path'),
            'description' => Yii::t('app', 'Description'),
            'location' => Yii::t('app', 'Location'),
            'privacy' => Yii::t('app', 'Privacy'),
            'fealing' => Yii::t('app', 'Fealing'),
            'tags' => Yii::t('app', 'Tags'),
            'likes' => Yii::t('app', 'Likes'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->type) AND empty($this->imagePath)) {
                $this->type = 'Status';
            }
            return true;
        } else {
            return false;
        }
    }

    public function getTypes()
    {
        foreach ($this->typeEnum as $key => $type) {
            $this->typeEnum[$key] = Translations::translate('model', $type);
        }

        return $this->typeEnum;
    }

    public function getPrivacies()
    {
        foreach ($this->privacyEnum as $key => $privacy) {
            $this->privacyEnum[$key] = Translations::translate('model', $privacy);
        }

        return $this->privacyEnum;
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

    public function getActionTextByType($type)
    {
        switch ($type) {
            case 'Avatar':
                $text = Translations::translate('app', ' uploaded a new avatar photo.');
                break;
            case 'Cover':
                $text = Translations::translate('app', ' uploaded a new cover photo.');
                break;
            case 'newAccount':
                $text = Translations::translate('app', ' created his account.');
                break;
            case 'Photo':
                $text = Translations::translate('app', ' uploaded a new photo.');
                break;
            default:
                $text = Translations::translate('app', ' posted a new status.');
                break;
        }
        return $text;
    }

    public static function getNotificationAnimation($effectType = 'all')
    {
        $animations = [
            'in' => [
                // Bouncing Entrances
                'bounceIn' => 'bounceIn',
                'bounceInDown' => 'bounceInDown',
                'bounceInLeft' => 'bounceInLeft',
                'bounceInUp' => 'bounceInUp',
                // Fading Entrances
                'fadeIn' => 'fadeIn',
                'fadeInDown' => 'fadeInDown',
                'fadeInDownBig' => 'fadeInDownBig',
                'fadeInLeft' => 'fadeInLeft',
                'fadeInLeftBig' => 'fadeInLeftBig',
                'fadeInRightBig' => 'fadeInRightBig',
                'fadeInUp' => 'fadeInUp',
                'fadeInUpBig' => 'fadeInUpBig',
                // Flippers
                'flipInX' => 'flipInX',
                'flipInY' => 'flipInY',
                // Lightspeed
                'lightSpeedIn' => 'lightSpeedIn',
                // Rotating Entrances
                'rotateIn' => 'rotateIn',
                'rotateInDownLeft' => 'rotateInDownLeft',
                'rotateInDownRight' => 'rotateInDownRight',
                'rotateInUpLeft' => 'rotateInUpLeft',
                'rotateInUpRight' => 'rotateInUpRight',
                // Sliding Entrances
                'slideInUp' => 'slideInUp',
                'slideInDown' => 'slideInDown',
                'slideInLeft' => 'slideInLeft',
                'slideInRight' => 'slideInRight',
                // Zoom Entrances
                'zoomIn' => 'zoomIn',
                'zoomInDown' => 'zoomInDown',
                'zoomInLeft' => 'zoomInLeft',
                'zoomInRight' => 'zoomInRight',
                'zoomInUp' => 'zoomInUp',
                // Specials
                'jackInTheBox' => 'jackInTheBox',
                'rollIn' => 'rollIn',
            ],
            'out' => [
                // Bouncing Exits
                'bounceOut' => 'bounceOut',
                'bounceOutDown' => 'bounceOutDown',
                'bounceOutLeft' => 'bounceOutLeft',
                'bounceOutRight' => 'bounceOutRight',
                'bounceOutUp' => 'bounceOutUp',
                // Fading Exits
                'fadeOut' => 'fadeOut',
                'fadeOutDown' => 'fadeOutDown',
                'fadeOutDownBig' => 'fadeOutDownBig',
                'fadeOutLeft' => 'fadeOutLeft',
                'fadeOutLeftBig' => 'fadeOutLeftBig',
                'fadeOutRight' => 'fadeOutRight',
                'fadeOutRightBig' => 'fadeOutRightBig',
                'fadeOutUp' => 'fadeOutUp',
                'fadeOutUpBig' => 'fadeOutUpBig',
                // Flippers
                'flipOutX' => 'flipOutX',
                'flipOutY' => 'flipOutY',
                // Lightspeed
                'lightSpeedOut' => 'lightSpeedOut',
                // Rotating Exits
                'rotateOut' => 'rotateOut',
                'rotateOutDownLeft' => 'rotateOutDownLeft',
                'rotateOutDownRight' => 'rotateOutDownRight',
                'rotateOutUpLeft' => 'rotateOutUpLeft',
                'rotateOutUpRight' => 'rotateOutUpRight',
                // Sliding Exits
                'slideOutUp' => 'slideOutUp',
                'slideOutDown' => 'slideOutDown',
                'slideOutLeft' => 'slideOutLeft',
                'slideOutRight' => 'slideOutRight',
                // Zoom Exits
                'zoomOut' => 'zoomOut',
                'zoomOutDown' => 'zoomOutDown',
                'zoomOutLeft' => 'zoomOutLeft',
                'zoomOutRight' => 'zoomOutRight',
                'zoomOutUp' => 'zoomOutUp',
                // Specials
                'hinge' => 'hinge',
                'rollOut' => 'rollOut',
            ],
            'attention' => [
                'bounce' => 'bounce',
                'flash' => 'flash',
                'pulse' => 'pulse',
                'rubberBand' => 'rubberBand',
                'shake' => 'shake',
                'swing' => 'swing',
                'tada' => 'tada',
                'wobble' => 'wobble',
                'jello' => 'jello',
            ],
            'all' => [
                // Attention Seekers
                'bounce' => 'bounce',
                'flash' => 'flash',
                'pulse' => 'pulse',
                'rubberBand' => 'rubberBand',
                'shake' => 'shake',
                'swing' => 'swing',
                'tada' => 'tada',
                'wobble' => 'wobble',
                'jello' => 'jello',
                // Bouncing Entrances
                'bounceIn' => 'bounceIn',
                'bounceInDown' => 'bounceInDown',
                'bounceInLeft' => 'bounceInLeft',
                'bounceInUp' => 'bounceInUp',
                // Bouncing Exits
                'bounceOut' => 'bounceOut',
                'bounceOutDown' => 'bounceOutDown',
                'bounceOutLeft' => 'bounceOutLeft',
                'bounceOutRight' => 'bounceOutRight',
                'bounceOutUp' => 'bounceOutUp',
                // Fading Entrances
                'fadeIn' => 'fadeIn',
                'fadeInDown' => 'fadeInDown',
                'fadeInDownBig' => 'fadeInDownBig',
                'fadeInLeft' => 'fadeInLeft',
                'fadeInLeftBig' => 'fadeInLeftBig',
                'fadeInRightBig' => 'fadeInRightBig',
                'fadeInUp' => 'fadeInUp',
                'fadeInUpBig' => 'fadeInUpBig',
                // Fading Exits
                'fadeOut' => 'fadeOut',
                'fadeOutDown' => 'fadeOutDown',
                'fadeOutDownBig' => 'fadeOutDownBig',
                'fadeOutLeft' => 'fadeOutLeft',
                'fadeOutLeftBig' => 'fadeOutLeftBig',
                'fadeOutRight' => 'fadeOutRight',
                'fadeOutRightBig' => 'fadeOutRightBig',
                'fadeOutUp' => 'fadeOutUp',
                'fadeOutUpBig' => 'fadeOutUpBig',
                // Flippers
                'flip' => 'flip',
                'flipInX' => 'flipInX',
                'flipInY' => 'flipInY',
                'flipOutX' => 'flipOutX',
                'flipOutY' => 'flipOutY',
                // Lightspeed
                'lightSpeedIn' => 'lightSpeedIn',
                'lightSpeedOut' => 'lightSpeedOut',
                // Rotating Entrances
                'rotateIn' => 'rotateIn',
                'rotateInDownLeft' => 'rotateInDownLeft',
                'rotateInDownRight' => 'rotateInDownRight',
                'rotateInUpLeft' => 'rotateInUpLeft',
                'rotateInUpRight' => 'rotateInUpRight',
                // Rotating Exits
                'rotateOut' => 'rotateOut',
                'rotateOutDownLeft' => 'rotateOutDownLeft',
                'rotateOutDownRight' => 'rotateOutDownRight',
                'rotateOutUpLeft' => 'rotateOutUpLeft',
                'rotateOutUpRight' => 'rotateOutUpRight',
                // Sliding Entrances
                'slideInUp' => 'slideInUp',
                'slideInDown' => 'slideInDown',
                'slideInLeft' => 'slideInLeft',
                'slideInRight' => 'slideInRight',
                // Sliding Exits
                'slideOutUp' => 'slideOutUp',
                'slideOutDown' => 'slideOutDown',
                'slideOutLeft' => 'slideOutLeft',
                'slideOutRight' => 'slideOutRight',
                // Zoom Entrances
                'zoomIn' => 'zoomIn',
                'zoomInDown' => 'zoomInDown',
                'zoomInLeft' => 'zoomInLeft',
                'zoomInRight' => 'zoomInRight',
                'zoomInUp' => 'zoomInUp',
                // Zoom Exits
                'zoomOut' => 'zoomOut',
                'zoomOutDown' => 'zoomOutDown',
                'zoomOutLeft' => 'zoomOutLeft',
                'zoomOutRight' => 'zoomOutRight',
                'zoomOutUp' => 'zoomOutUp',
                // Specials
                'hinge' => 'hinge',
                'jackInTheBox' => 'jackInTheBox',
                'rollIn' => 'rollIn',
                'rollOut' => 'rollOut',
            ],
        ];

        return array_rand($animations[$effectType]);
    }
}
