<?php

namespace common\models;

use Yii;
use common\models\Countrylanguage;

/**
 * This is the model class for table "country".
 *
 * @property string $Code
 * @property string $Name
 * @property string $Continent
 * @property string $Region
 * @property double $SurfaceArea
 * @property integer $IndepYear
 * @property integer $Population
 * @property double $LifeExpectancy
 * @property double $GNP
 * @property double $GNPOld
 * @property string $LocalName
 * @property string $GovernmentForm
 * @property string $HeadOfState
 * @property integer $Capital
 * @property string $Code2
 *
 * @property City[] $cities
 * @property Countrylanguage[] $countrylanguages
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code'], 'required'],
            [['Continent'], 'string'],
            [['SurfaceArea', 'LifeExpectancy', 'GNP', 'GNPOld'], 'number'],
            [['IndepYear', 'Population', 'Capital'], 'integer'],
            [['Code'], 'string', 'max' => 3],
            [['Name'], 'string', 'max' => 52],
            [['Region'], 'string', 'max' => 26],
            [['LocalName', 'GovernmentForm'], 'string', 'max' => 45],
            [['HeadOfState'], 'string', 'max' => 60],
            [['Code2'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Code' => Translations::translate('app', 'Code'),
            'Name' => Translations::translate('app', 'Name'),
            'Continent' => Translations::translate('app', 'Continent'),
            'Region' => Translations::translate('app', 'Region'),
            'SurfaceArea' => Translations::translate('app', 'Surface Area'),
            'IndepYear' => Translations::translate('app', 'Indep Year'),
            'Population' => Translations::translate('app', 'Population'),
            'LifeExpectancy' => Translations::translate('app', 'Life Expectancy'),
            'GNP' => Translations::translate('app', 'Gnp'),
            'GNPOld' => Translations::translate('app', 'Gnpold'),
            'LocalName' => Translations::translate('app', 'Local Name'),
            'GovernmentForm' => Translations::translate('app', 'Government Form'),
            'HeadOfState' => Translations::translate('app', 'Head Of State'),
            'Capital' => Translations::translate('app', 'Capital'),
            'Code2' => Translations::translate('app', 'Code2'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['CountryCode' => 'Code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountrylanguages()
    {
        return $this->hasMany(Countrylanguage::className(), ['CountryCode' => 'Code']);
    }
}
