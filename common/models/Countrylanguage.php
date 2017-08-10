<?php

namespace common\models;

use Yii;
use common\models\Country;

/**
 * This is the model class for table "countrylanguage".
 *
 * @property string $CountryCode
 * @property string $Language
 * @property string $IsOfficial
 * @property double $Percentage
 *
 * @property Country $countryCode
 */
class Countrylanguage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countrylanguage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CountryCode', 'Language'], 'required'],
            [['IsOfficial'], 'string'],
            [['Percentage'], 'number'],
            [['CountryCode'], 'string', 'max' => 3],
            [['Language'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CountryCode' => Yii::t('app', 'Country Code'),
            'Language' => Yii::t('app', 'Language'),
            'IsOfficial' => Yii::t('app', 'Is Official'),
            'Percentage' => Yii::t('app', 'Percentage'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryCode()
    {
        return $this->hasOne(Country::className(), ['Code' => 'CountryCode']);
    }
}
