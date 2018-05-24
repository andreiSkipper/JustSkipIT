<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use common\models\City;
use common\models\Countrylanguage;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use kartik\widgets\DatePicker;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MoviesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $profile \common\models\Profiles */

$this->title = 'Profile';
$profile = Yii::$app->user->getIdentity()->profile;
$cities = ArrayHelper::map(City::find()->where(['CountryCode' => 'ROM'])->orderBy('Name')->all(), 'ID', 'Name');
$languages = ArrayHelper::map(Countrylanguage::find()->orderBy('Language')->all(), 'Language', 'Language');
?>

<?php
Modal::begin([
    'id' => 'profile-modal-' . $profile->user_id,
    'header' => 'Edit Your Profile',
    'size' => 'modal-lg',
]);
?>

    <div class="row">
        <?php $form = ActiveForm::begin(['action' => ['/edit-profile'], 'options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="col-xs-12 no-padding">
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'firstname', [
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-user-o'></i>"
                            ]
                        ]
                    ])->textInput(
                        [
                            'maxlength' => true,
//                            'placeholder' => \common\models\Translations::translate('app', 'Insert Firstname...')
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'lastname', [
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-user'></i>"
                            ]
                        ]
                    ])->textInput(
                        [
                            'maxlength' => true,
//                            'placeholder' => \common\models\Translations::translate('app', 'Insert Lastname...')
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'nickname', [
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-user-circle'></i>"
                            ]
                        ]
                    ])->textInput(
                        [
                            'maxlength' => true,
//                            'placeholder' => \common\models\Translations::translate('app', 'Insert Nickname...')
                        ]);
                    ?>
                </div>
            </div>
        </div>


        <div class="col-xs-12 no-padding">

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'phoneNumber', [
                        'addon' => [
                            'prepend' => [
                                'content' => Html::icon('phone')
                            ]
                        ]
                    ])->textInput(
                        [
                            'maxlength' => true,
//                            'placeholder' => \common\models\Translations::translate('app', 'Insert Phone Number...'),
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'birthday')->widget(DatePicker::classname(), [
                        'options' => [
//                            'placeholder' => $profile->getAttributeLabel('birthday')
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]) ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'work', [
                        'addon' => [
                            'prepend' => [
                                'content' => Html::icon('briefcase')
                            ]
                        ]
                    ])->textInput(
                        [
                            'maxlength' => true,
//                            'placeholder' => \common\models\Translations::translate('app', 'Insert work...')
                        ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="col-xs-12 no-padding">
            <div class="col-md-4">
                <?= $form->field($profile, 'currentCity')->widget(Select2::classname(),
                    [
                        'name' => 'Profiles[currentCity]',
                        'data' => $cities,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => false,
                        'options' => [
//                            'placeholder' => $profile->getAttributeLabel('currentCity'),
                            'placeholder' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-home'></i>"
                            ],
                        ]
                    ]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($profile, 'birthCity')->widget(Select2::classname(),
                    [
                        'name' => 'Profiles[birthCity]',
                        'data' => $cities,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => false,
                        'options' => [
//                            'placeholder' => $profile->getAttributeLabel('birthCity'),
                            'placeholder' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-home'></i>"
                            ],
                        ]
                    ]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($profile, 'knownLanguages')->widget(Select2::classname(),
                    [
                        'name' => 'Profiles[knownLanguages]',
                        'data' => $languages,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => false,
                        'options' => [
//                            'placeholder' => $profile->getAttributeLabel('knownLanguages'),
                            'placeholder' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-language'></i>"
                            ],
                        ]
                    ]) ?>
            </div>
        </div>

        <div class="col-xs-12 no-padding">
            <div class="col-md-4">
                <?= $form->field($profile, 'sex')->widget(Select2::classname(),
                    [
                        'name' => 'Profiles[sex]',
                        'data' => $profile->sexEnum,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'options' => [
//                            'placeholder' => $profile->getAttributeLabel('sex'),
                            'placeholder' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-transgender'></i>"
                            ],
                        ]
                    ]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($profile, 'interestedIn')->widget(Select2::classname(),
                    [
                        'name' => 'Profiles[interestedIn]',
                        'data' => $profile->sexEnum,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'options' => [
//                            'placeholder' => $profile->getAttributeLabel('interestedIn'),
                            'placeholder' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-hand-o-right'></i>"
                            ],
                        ]
                    ]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($profile, 'relationship')->widget(Select2::classname(),
                    [
                        'name' => 'Profiles[relationship]',
                        'data' => $profile->relationshipEnum,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'options' => [
//                            'placeholder' => $profile->getAttributeLabel('relationship'),
                            'placeholder' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-heartbeat'></i>"
                            ],
                        ]
                    ]) ?>
            </div>
        </div>

        <div class="col-md-4 col-md-offset-4">
            <div class="form-group">
                <?= $form->field($profile, 'shortUrl', [
                    'addon' => [
                        'prepend' => [
                            'content' => "<i class='fa fa-link'></i>"
                        ]
                    ]
                ])->textInput(
                    [
                        'maxlength' => true,
//                        'placeholder' => \common\models\Translations::translate('app', 'Insert short url...')
                    ]);
                ?>
            </div>
        </div>

        <div class="col-xs-12 no-padding">
            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($profile, 'address', [
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-address-card-o'></i>"
                            ]
                        ]
                    ])->textarea(
                        [
                            'maxlength' => true,
//                            'placeholder' => \common\models\Translations::translate('app', 'Insert Address...')
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($profile, 'description', [
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-file-text-o'></i>"
                            ]
                        ]
                    ])->textarea(
                        [
                            'maxlength' => true,
//                            'placeholder' => \common\models\Translations::translate('app', 'Insert description...')
                        ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-xs-12 no-padding form-group">
            <div class="col-md-6">
                <?= $form->field($profile, 'avatar')->widget(FileInput::classname(), [
                    'options' => [
                        'accept' => 'image/*',
                        'multiple' => false
                    ],
                    'pluginOptions' => [
                        'browseClass' => 'btn btn-success',
                        'showPreview' => true,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,

                        'initialPreview' => [
                            Url::base() . '/' . $profile->avatar,
                        ],
                        'initialPreviewAsData' => true,
//                        'initialCaption' => "The Moon and the Earth",
//                        'initialPreviewConfig' => [
//                            ['caption' => 'Moon.jpg', 'size' => '873727'],
//                        ],
                        'overwriteInitial' => true,
                        'maxFileSize' => 5000
                    ]
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($profile, 'cover')->widget(FileInput::classname(), [
                    'options' => [
                        'accept' => 'image/*',
                        'multiple' => false
                    ],
                    'pluginOptions' => [
                        'browseClass' => 'btn btn-success',
                        'showPreview' => true,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,

                        'initialPreview' => [
                            Url::base() . '/' . $profile->cover,
                        ],
                        'initialPreviewAsData' => true,
//                        'initialCaption' => "The Moon and the Earth",
//                        'initialPreviewConfig' => [
//                            ['caption' => 'Moon.jpg', 'size' => '873727'],
//                        ],
                        'overwriteInitial' => true,
                        'maxFileSize' => 5000
                    ]
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6">
                <?= Html::resetButton('Reset', ['class' => 'btn btn-primary edit-profile-reset col-xs-8 col-xs-offset-2']) ?>
            </div>

            <div class="col-md-6">
                <?= Html::submitButton('Update', ['class' => 'btn btn-danger edit-profile-save col-xs-8 col-xs-offset-2', 'data-method' => 'post', 'onclick' => "$('body .loading').addClass('active');",]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

<?php
Modal::end();
?>