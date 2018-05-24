<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use common\models\User;
use common\models\Translations;

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
            [['firstname'], 'required'],
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
            'user_id' => Translations::translate('app', 'User ID'),
            'firstname' => Translations::translate('app', 'First Name'),
            'lastname' => Translations::translate('app', 'Last Name'),
            'nickname' => Translations::translate('app', 'Nick'),
            'avatar' => Translations::translate('app', 'Avatar'),
            'cover' => Translations::translate('app', 'Cover'),
            'address' => Translations::translate('app', 'Address'),
            'phoneNumber' => Translations::translate('app', 'Phone Number'),
            'currentCity' => Translations::translate('app', 'Current City'),
            'birthCity' => Translations::translate('app', 'Birth City'),
            'work' => Translations::translate('app', 'Work'),
            'birthday' => Translations::translate('app', 'Birthday'),
            'sex' => Translations::translate('app', 'Sex'),
            'interestedIn' => Translations::translate('app', 'Interested In'),
            'knownLanguages' => Translations::translate('app', 'Known Languages'),
            'relationship' => Translations::translate('app', 'Relationship Status'),
            'description' => Translations::translate('app', 'Description'),
            'shortUrl' => Translations::translate('app', 'Short Url'),
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
