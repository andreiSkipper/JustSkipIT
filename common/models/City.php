<?php

namespace common\models;

use Yii;
use common\models\Country;

/**
 * This is the model class for table "city".
 *
 * @property integer $ID
 * @property string $Name
 * @property string $CountryCode
 * @property string $District
 * @property integer $Population
 *
 * @property Country $countryCode
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Population'], 'integer'],
            [['Name'], 'string', 'max' => 35],
            [['CountryCode'], 'string', 'max' => 3],
            [['District'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Translations::translate('app', 'ID'),
            'Name' => Translations::translate('app', 'Name'),
            'CountryCode' => Translations::translate('app', 'Country Code'),
            'District' => Translations::translate('app', 'District'),
            'Population' => Translations::translate('app', 'Population'),
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
