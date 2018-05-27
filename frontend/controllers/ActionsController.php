<?php

namespace frontend\controllers;

use common\models\Translations;
use Yii;
use common\models\Actions;
use common\models\Profiles;
use common\models\User;
use app\models\UploadForm;
use yii\web\UploadedFile;

class ActionsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new Actions();
        $model->user_id = Yii::$app->user->identity->getId();
        if ($model->load($post)) {
            $image = new UploadForm();
            $image->imageFile = UploadedFile::getInstance($model, 'imagePath');
            if ($image->imageFile) {
                $image->id = $model->user_id;
                $image->upload(['type' => 'Photo']);
                $model->imagePath = $image->filepath . $image->filename;
            }
            if (empty($model->imagePath)) {
                $model->type = 'Status';
            } else {
                $model->type = 'Photo';
            }
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    $result = array();
                    $html = $this->renderPartial('/actions/panel', ['action' => $model]);
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
        $action = Actions::findOne($id);
        if (empty($action)) {
            return false;
        }
        if ($action->imagePath) {
            if ($action->type == 'Cover' OR $action->type == 'Avatar') {
                $profile = Profiles::findOne($action->user_id);
                if ($action->imagePath == $profile->cover) {
                    $profile->cover = NULL;
                    $profile->save();
                } elseif ($action->imagePath == $profile->avatar) {
                    $profile->avatar = NULL;
                    $profile->save();
                }
            }
            file_exists($action->imagePath) ? unlink($action->imagePath) : '';
        }
        $action->delete();
        return json_encode($action);
    }

    public function actionEdit($id)
    {
        $action = Actions::findOne($id);

        return $this->render('edit', [
            'action' => $action,
        ]);
    }

    public function actionView($id)
    {
        $action = Actions::findOne($id);
        if (empty($action)) {
            $notification = [
                'title' => Translations::translate('app', 'Error') . '!',
                'text' => Translations::translate('app', 'The post you are trying to access has been deleted.'),
                'icon' => '',
            ];
            Yii::$app->session->setFlash('success', $notification);
            return $this->goHome();
        }

        return $this->render('panel', ['action' => $action]);
    }
}
