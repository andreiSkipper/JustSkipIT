<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Movies;

/**
 * MoviesSearch represents the model behind the search form about `app\models\Movies`.
 */
class MoviesSearch extends Movies
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'duration', 'year'], 'integer'],
            [['title', 'genre', 'director', 'release_date', 'stars', 'video', 'image'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Movies::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'duration' => $this->duration,
            'year' => $this->year,
            'release_date' => $this->release_date,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'genre', $this->genre])
            ->andFilterWhere(['like', 'director', $this->director])
            ->andFilterWhere(['like', 'stars', $this->stars])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'image', $this->image]);

//        $query->orFilterWhere(['like', 'id', $this->title])
//            ->orFilterWhere(['like', 'duration', $this->title])
//            ->orFilterWhere(['like', 'year', $this->title])
//            ->orFilterWhere(['like', 'release_date', $this->title])
//            ->orFilterWhere(['like', 'title', $this->title])
//            ->orFilterWhere(['like', 'genre', $this->genre])
//            ->orFilterWhere(['like', 'director', $this->director])
//            ->orFilterWhere(['like', 'stars', $this->stars])
//            ->orFilterWhere(['like', 'video', $this->video])
//            ->orFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
