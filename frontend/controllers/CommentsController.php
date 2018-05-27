<?php

namespace frontend\controllers;

use common\models\Notifications;
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
                if ($model->action->user_id != Yii::$app->user->identity->id) {
                    $notification = new Notifications();
                    $notification->user_id = $model->action->user_id;
                    $notification->model = json_encode($model->toArray());
                    $notification->type = Comments::tableName();
                    $notification->status = 'Added';
                    $notification->save();
                }
                if (Yii::$app->request->isAjax) {
                    $result = array();
                    $html = $this->renderPartial('/comments/comment-details', ['comment' => $model]);
                    $modalHtml = $this->renderPartial('/comments/comment-details', ['comment' => $model, 'modal' => true]);
                    $result['html'] = preg_replace('#\s+#', ' ', trim($html));
                    $result['modalHtml'] = preg_replace('#\s+#', ' ', trim($modalHtml));
                    $result['action'] = $model->action->toArray();
                    $result['comment'] = $model->toArray();

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

            if ($comment->action->user_id != Yii::$app->user->identity->id) {
                $notification = new Notifications();
                $notification->user_id = $comment->action->user_id;
                $notification->model = json_encode($comment->toArray());
                $notification->type = Comments::tableName();
                $notification->status = 'Removed';
                $notification->save();
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
