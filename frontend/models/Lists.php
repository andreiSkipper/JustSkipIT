<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lists".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $ddl
 * @property string $created_at
 * @property string $updated_at
 */
class Lists extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lists';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'created_at', 'updated_at'], 'required'],
            [['title', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'ddl' => 'DDL',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
