<?php

namespace frontend\controllers;

use Yii;
use common\models\Comments;
use common\models\Profiles;
use common\models\User;
use app\models\UploadForm;
use yii\web\UploadedFile;

class CommentsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new Comments();
        $model->user_id = Yii::$app->user->identity->getId();
        if ($model->load($post)) {
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    $result = array();
                    $html = $this->renderPartial('/comments/comment-details', ['comment' => $model]);
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

    public function actionDelete($id)
    {
        $comment = Comments::findOne($id);
        if (empty($comment)) {
            return false;
        }
        if ($comment->delete()) {
            /* @var $comment Comments */
            /* @var $reply Comments */
            foreach ($comment->replies as $reply) {
                $reply->delete();
            }
        }

        return json_encode($comment);
    }

    public function actionEdit($id)
    {
        $comment = Comments::findOne($id);

        return $this->render('edit', [
            'action' => $comment,
        ]);
    }

}
