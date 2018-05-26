<?php

namespace frontend\controllers;

use app\models\Movies;
use common\models\Friendship;
use common\models\Notifications;
use common\models\User;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use common\models\Actions;
use yii\helpers\ArrayHelper;
use common\models\Countrylanguage;
use common\models\City;
use app\models\UploadForm;
use common\models\Translations;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $successUrl = 'Success';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['auth'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
//        return $this->renderPartial('/mails/signup-success');
//        return $this->render('/actions/user-heading', ['action' => Actions::findOne(83)]);
        $searchModel = new Actions();
        $actions = $searchModel->search([]);
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
                'cities' => $cities,
                'languages' => $languages,
                'actions' => $actions,
                'pages' => $pages,
            ]);
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->request->isAjax) {
                return json_encode(null);
            } else {
                return $this->goBack();
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return json_encode($model->getErrors());
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['contactEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    Yii::$app->mailer->compose('/mails/signup-success')
                        ->setFrom([Yii::$app->params['supportEmail'] => 'SkipIT'])
                        ->setTo($user->email)
                        ->setSubject('Welcome to SkipIT')
                        ->send();
                    if (Yii::$app->request->isAjax) {
                        return json_encode(null);
                    } else {
                        return $this->goHome();
                    }
                }
            } else {
                return json_encode($model->getErrors());
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return json_encode($model->getErrors());
            } else {
                return $this->render('signup', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLanguage($language = 'en-US')
    {
        if (!Yii::$app->user->isGuest) {
            /* @var $currentUser User */
            $currentUser = Yii::$app->user->getIdentity();
            if ($currentUser->language != $language) {
                $currentUser->language = $language;
                $currentUser->save();
            }
        }
        Yii::$app->session['language'] = $language;
        // redirect to previews page
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionNavbar()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('/layouts/navbar_header');
        }
        return $this->redirect('/');
    }

    public function actionNotifications()
    {
        if (Yii::$app->request->isAjax) {
            $result['friend_requests'] = [
                'count' => Friendship::find()->where(['user_to' => Yii::$app->user->id, 'status' => Friendship::STATUS_REQUESTED])->count(),
                'html' => $this->renderPartial('/layouts/navbar_header_friend_requests'),
            ];
            $result['notifications'] = [
                'count' => Notifications::find()->where(['user_id' => Yii::$app->user->id])->count(),
                'html' => $this->renderPartial('/layouts/navbar_header_notifications'),
            ];

            return json_encode($result);
        }
        return $this->redirect('/');
    }

    public function actionPrivacyPolicy()
    {
        return $this->render('privacy-policy');
    }

    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        $params = array();
//        var_dump($attributes);die();

        switch (strtolower($client->getTitle())) {
            case 'google':
                $params['SignupForm'] = [
                    'username' => $attributes['emails'][0]['value'],
                    'email' => $attributes['emails'][0]['value'],
                    'password' => Yii::$app->getSecurity()->generateRandomString(12),
                    'firstname' => $attributes['name']['givenName'],
                    'lastname' => $attributes['name']['familyName'],
                ];
                $user = User::find()->where(['email' => $params['SignupForm']['email']])->one();
                break;
            case 'twitter':
                $params['SignupForm'] = [
                    'username' => $attributes['screen_name'],
                    'email' => $attributes['screen_name'] . '@skipit.ro',
                    'password' => Yii::$app->getSecurity()->generateRandomString(12),
                    'firstname' => $attributes['name'],
                    'lastname' => '',
                ];
                $user = User::find()->where(['email' => $params['SignupForm']['email']])->one();
                break;
            case 'linkedin':
                $params['SignupForm'] = [
                    'username' => substr($attributes['public-profile-url'], strrpos($attributes['public-profile-url'], '/') + 1),
                    'email' => $attributes['email'],
                    'password' => Yii::$app->getSecurity()->generateRandomString(12),
                    'firstname' => $attributes['first_name'],
                    'lastname' => $attributes['first_name'],
                ];
                $user = User::find()->where(['email' => $params['SignupForm']['email']])->one();
                break;
            default:
                break;
        }
        if (!empty($user)) {
            Yii::$app->user->login($user);
            if (Yii::$app->request->isAjax) {
                return json_encode(null);
            } else {
                return $this->goBack();
            }
        } else {
            $model = new SignupForm();
            if ($model->load($params)) {
                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        Yii::$app->mailer->compose('/mails/signup-success')
                            ->setFrom([Yii::$app->params['supportEmail'] => 'SkipIT'])
                            ->setTo($user->email)
                            ->setSubject('Welcome to SkipIT')
                            ->send();
                        if (Yii::$app->request->isAjax) {
                            return json_encode(null);
                        } else {
                            return $this->goHome();
                        }
                    }
                } else {
                    var_dump($model->getErrors());die();
                    return json_encode($model->getErrors());
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    return json_encode($model->getErrors());
                } else {
                    return $this->render('signup', [
                        'model' => $model,
                    ]);
                }
            }
        }
    }
}
