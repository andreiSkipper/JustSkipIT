<?php

namespace frontend\controllers;

use common\models\Actions;
use common\models\City;
use common\models\Translations;
use common\models\User;
use Yii;
use common\models\Profiles;
use yii\helpers\ArrayHelper;
use common\models\Countrylanguage;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\data\Pagination;

class ProfilesController extends \yii\web\Controller
{
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'update' => ['post'],
//                ],
//            ],
//        ];
//    }

    public function actionIndex()
    {
        $url = explode('/', Url::current());
        $username = explode('?', $url[count($url) - 1])[0];
        $user = User::findOne(['username' => $username]);
        if (empty($user)) {
            return $this->goHome();
        }
        $id = $user->id;
        $profile = Profiles::findOne($id);

        $searchModel = new Actions();
        $actions = $searchModel->search(['user_id' => $id]);
        $actions->pagination->pageSize = Yii::$app->params['pagesize'];
        $pages = new Pagination(['totalCount' => $actions->query->count(), 'pageSize' => Yii::$app->params['pagesize']]);

        if (Yii::$app->request->isAjax) {
            $actions = $actions->query->offset(Yii::$app->request->post('offset'))->limit($pages->limit)->all();

            $result = array();
            $result['html'] = '';
            $result['actions'] = count($actions);
            foreach ($actions as $action) {
                $html = $this->renderPartial('/actions/panel', ['action' => $action]);
                $result['html'] .= preg_replace('#\s+#', ' ', trim($html));
            }

            return json_encode($result);
        } else {
            $actions = $actions->query->offset($pages->offset)->limit($pages->limit)->all();

            $cities = ArrayHelper::map(City::find()->where(['CountryCode' => 'ROM'])->orderBy('Name')->all(), 'ID', 'Name');
            $languages = ArrayHelper::map(Countrylanguage::find()->orderBy('Language')->all(), 'Language', 'Language');

            return $this->render('index', [
                'profile' => $profile,
                'cities' => $cities,
                'languages' => $languages,
                'actions' => $actions,
                'pages' => $pages,
            ]);
        }
    }

    public function actionMyProfile()
    {
        $id = Yii::$app->user->identity->id;
        $profile = Profiles::findOne($id);

        $searchModel = new Actions();
        $actions = $searchModel->search(['user_id' => $id]);
        $actions->pagination->pageSize = Yii::$app->params['pagesize'];
        $pages = new Pagination(['totalCount' => $actions->query->count(), 'pageSize' => Yii::$app->params['pagesize']]);
        if (Yii::$app->request->isAjax) {
            $actions = $actions->query->offset(Yii::$app->request->post('offset'))->limit($pages->limit)->all();

            $result = array();
            $result['html'] = '';
            $result['actions'] = count($actions);
            foreach ($actions as $action) {
                $html = $this->renderPartial('/actions/panel', ['action' => $action]);
                $result['html'] .= preg_replace('#\s+#', ' ', trim($html));
            }

            return json_encode($result);
        } else {
            $actions = $actions->query->offset($pages->offset)->limit($pages->limit)->all();

            $cities = ArrayHelper::map(City::find()->where(['CountryCode' => 'ROM'])->orderBy('Name')->all(), 'ID', 'Name');
            $languages = ArrayHelper::map(Countrylanguage::find()->orderBy('Language')->all(), 'Language', 'Language');

            return $this->render('index', [
                'profile' => $profile,
                'cities' => $cities,
                'languages' => $languages,
                'actions' => $actions,
                'pages' => $pages,
            ]);
        }
    }

    public function actionUpdate()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $profile = Profiles::findOne(Yii::$app->user->identity->id);

        if (Yii::$app->request->isPost) {
            $profile->load($_POST);

            $profile->birthday = $profile->birthday ? date('Y-m-d', strtotime($profile->birthday)) : NULL;

            $uploadAvatar = new UploadForm();
            $uploadAvatar->imageFile = UploadedFile::getInstance($profile, 'avatar');
            if ($uploadAvatar->imageFile) {
                $uploadAvatar->id = $profile->user_id;
                $uploadAvatar->upload(['type' => 'Avatar']);
                $profile->avatar = $uploadAvatar->filepath . $uploadAvatar->filename;
            } else {
                $profile->avatar = $profile->oldAttributes['avatar'];
            }

            $uploadCover = new UploadForm();
            $uploadCover->imageFile = UploadedFile::getInstance($profile, 'cover');
            if ($uploadCover->imageFile) {
                $uploadCover->id = $profile->user_id;
                $uploadCover->upload(['type' => 'Cover']);
                $profile->cover = $uploadCover->filepath . $uploadCover->filename;
            } else {
                $profile->cover = $profile->oldAttributes['cover'];
            }

            if ($profile->validate()) {
                $profile->save();
                if (!Yii::$app->request->isAjax) {
                    $notification = [
                        'title' => Translations::translate('app', 'Success') . '!',
                        'text' => Translations::translate('app', 'Your profile has been updated.'),
                        'icon' => '',
                    ];
                    Yii::$app->session->setFlash('success', $notification);
                    return $this->redirect('profile');
                }
            } else {
                $text = '';
                foreach ($profile->getErrors() as $error) {
                    $text .= $error['0'] . '<br/>';
                }
                $notification = [
                    'title' => Translations::translate('app', 'Ops, something went wrong') . '!',
                    'text' => $text,
                    'icon' => '',
                ];
                Yii::$app->session->setFlash('error', $notification);
                return $this->redirect('profile');
            }
        }

        $cities = ArrayHelper::map(City::find()->where(['CountryCode' => 'ROM'])->orderBy('Name')->all(), 'ID', 'Name');
        $languages = ArrayHelper::map(Countrylanguage::find()->orderBy('Language')->all(), 'Language', 'Language');

        return $this->render('update', [
            'profile' => $profile,
            'cities' => $cities,
            'languages' => $languages,
        ]);
    }

    public function actionFindByUsername()
    {
        $url = explode('/', Url::current());
        $shortUrl = explode('?', $url[count($url) - 1])[0];
        $profile = Profiles::findOne(['shortUrl' => $shortUrl]);
        if (empty($profile)) {
            $user = User::findOne(['username' => $shortUrl]);
            if (empty($user)) {
                return $this->goHome();
            } else {
                $profile = Profiles::findOne($user->id);
            }
        }
        $searchModel = new Actions();
        $actions = $searchModel->search(['user_id' => $profile->user_id]);
        $actions->pagination->pageSize = Yii::$app->params['pagesize'];
        $pages = new Pagination(['totalCount' => $actions->query->count(), 'pageSize' => Yii::$app->params['pagesize']]);

        if (Yii::$app->request->isAjax) {
            $actions = $actions->query->offset(Yii::$app->request->post('offset'))->limit($pages->limit)->all();

            $result = array();
            $result['html'] = '';
            $result['actions'] = count($actions);
            foreach ($actions as $action) {
                $html = $this->renderPartial('/actions/panel', ['action' => $action]);
                $result['html'] .= preg_replace('#\s+#', ' ', trim($html));
            }

            return json_encode($result);
        } else {
            $actions = $actions->query->offset($pages->offset)->limit($pages->limit)->all();

            $cities = ArrayHelper::map(City::find()->where(['CountryCode' => 'ROM'])->orderBy('Name')->all(), 'ID', 'Name');
            $languages = ArrayHelper::map(Countrylanguage::find()->orderBy('Language')->all(), 'Language', 'Language');

            return $this->render('index', [
                'profile' => $profile,
                'cities' => $cities,
                'languages' => $languages,
                'actions' => $actions,
                'pages' => $pages,
            ]);
        }
    }
}
