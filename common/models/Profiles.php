<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use common\models\User;

/**
 * This is the model class for table "Profiles".
 *
 * @property integer $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $nickname
 * @property string $avatar
 * @property string $cover
 * @property string $address
 * @property string $phoneNumber
 * @property string $currentCity
 * @property string $birthCity
 * @property string $work
 * @property string $birthday
 * @property string $sex
 * @property string $interestedIn
 * @property string $knownLanguages
 * @property string $relationship
 * @property string $description
 * @property string $shortUrl
 */
class Profiles extends \yii\db\ActiveRecord
{
    public $sexEnum = [
        '',
        'Male',
        'Female'
    ];

    public $relationshipEnum = [
        "",
        "Single",
        "In a relationship",
        "Engaged",
        "Married",
        "It's complicated",
        "In an open relationship",
        "Widowed",
        "Separated",
        "Divorced",
        "In a civil union",
        "In a domestic partnership",
    ];

    public $languagesEnum = [
        "",
        "EN",
        "RO",
        "ES",
        "IT",
        "DE",
        "IT",
        "RU",
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'required'],
            [['avatar', 'cover', 'address', 'currentCity', 'birthCity', 'birthday', 'sex', 'interestedIn', 'knownLanguages', 'relationship', 'description'], 'string', 'max' => 255],
            [['firstname', 'lastname', 'nickname', 'work'], 'string', 'max' => 15],
            ['phoneNumber', 'string', 'max' => 17],
            ['shortUrl', 'string', 'max' => 20, 'min' => 3],
            ['shortUrl', 'unique'],
            ['shortUrl', 'verifyUsernames'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'firstname' => Yii::t('app', 'First Name'),
            'lastname' => Yii::t('app', 'Last Name'),
            'nickname' => Yii::t('app', 'Nick'),
            'avatar' => Yii::t('app', 'Avatar'),
            'cover' => Yii::t('app', 'Cover'),
            'address' => Yii::t('app', 'Address'),
            'phoneNumber' => Yii::t('app', 'Phone Number'),
            'currentCity' => Yii::t('app', 'Current City'),
            'birthCity' => Yii::t('app', 'Birth City'),
            'work' => Yii::t('app', 'Work'),
            'birthday' => Yii::t('app', 'Birthday'),
            'sex' => Yii::t('app', 'Sex'),
            'interestedIn' => Yii::t('app', 'Interested In'),
            'knownLanguages' => Yii::t('app', 'Known Languages'),
            'relationship' => Yii::t('app', 'Relationship Status'),
            'description' => Yii::t('app', 'Description'),
            'shortUrl' => Yii::t('app', 'Short Url'),
        ];
    }

    public static function getProfileLinkByUserID($user_id)
    {
        $profile = Profiles::findOne($user_id);
        if ($profile->shortUrl) {
            return Url::to(['/' . $profile->shortUrl]);
        } else {
            $user = User::findOne($user_id);
//            return Url::to(['/user/' . $user->username]);
            return Url::to(['/' . $user->username]);
        }
    }

    function verifyUsernames($attribute, $params)
    {
        $shortUrl = $this->$attribute;
        if (!empty(User::findOne(['username' => $shortUrl]))) {
            $this->addError($attribute, 'Short URL already taken. Please select another one.');
        }
    }
}
