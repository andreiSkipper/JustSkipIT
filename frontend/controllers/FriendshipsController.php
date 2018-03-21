<?php

namespace frontend\controllers;

use common\models\Friendship;
use Yii;
use common\models\Comments;
use yii\helpers\Html;
use common\models\Translations;

class FriendshipsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new Friendship();
        $model->user_from = Yii::$app->user->identity->getId();
        $model->status = Friendship::STATUS_REQUESTED;
        if ($model->load($post)) {
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    $result = array();
                    $html = Html::button('<i class="fa fa-remove" style="font-size: 20px;"></i> ' . Translations::translate('app', 'Remove Friend Request'),
                        [
                            'class' => 'btn col-xs-12 profile-button',
                            'data-user_id' => $model->user_to,
                            'id' => 'remove-request',
                            'onclick' => "return removeFriendAJAX(this);",
                        ]);
                    $result['html'] = preg_replace('#\s+#', ' ', trim($html));

                    return json_encode($result);
                } else {
                    return $this->goHome();
                }
            } else {
                var_dump($model->getErrors());
                die();
            }
        } else {
            var_dump($model->getErrors());
            die();
        }
    }

    public function actionDelete($user_id)
    {
        $model = Friendship::find()
            ->where(['user_from' => $user_id, 'user_to' => Yii::$app->user->id])
            ->orWhere(['user_from' => Yii::$app->user->id, 'user_to' => $user_id])
            ->one();
        if (empty($model)) {
            return false;
        }
        $model->delete();

        $result = array();
        $html = Html::button('<i class="fa fa-user-plus" style="font-size: 20px;"></i> ' . Translations::translate('app', 'Add Friend'),
            [
                'class' => 'btn col-xs-12 profile-button',
                'data-user_id' => $user_id,
                'id' => 'add-friend',
                'onclick' => "return addFriendAJAX(this);",

            ]);
        $result['html'] = preg_replace('#\s+#', ' ', trim($html));

        return json_encode($result);
    }


    public function actionAccept($user_id)
    {
        $model = Friendship::find()
            ->where(['user_from' => $user_id, 'user_to' => Yii::$app->user->id])
            ->one();

        if (empty($model)){
            return false;
        }

        $model->status = Friendship::STATUS_ACCEPTED;
        $model->save();

        return true;
    }


    public function actionRefuse($user_id)
    {
        $model = Friendship::find()
            ->where(['user_from' => $user_id, 'user_to' => Yii::$app->user->id])
            ->one();

        if (empty($model)){
            return false;
        }

        $model->status = Friendship::STATUS_REFUSED;
        $model->save();

        return true;
    }
}
