<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "movies".
 *
 * @property integer $id
 * @property string $title
 * @property integer $duration
 * @property integer $year
 * @property string $genre
 * @property string $plot
 * @property string $director
 * @property string $release_date
 * @property string $stars
 * @property string $video
 * @property string $image
 */
class Movies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'duration', 'year', 'genre', 'director', 'release_date', 'stars', 'video', 'image'], 'required'],
            [['duration', 'year'], 'integer'],
            [['release_date'], 'safe'],
            [['title', 'genre', 'director', 'stars', 'video', 'image', 'plot'], 'string', 'max' => 255]
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
            'duration' => 'Duration',
            'year' => 'Year',
            'genre' => 'Genre',
            'director' => 'Director',
            'release_date' => 'Release Date',
            'stars' => 'Stars',
            'video' => 'Video',
            'image' => 'Image',
            'plot' => 'Plot',
        ];
    }
}
