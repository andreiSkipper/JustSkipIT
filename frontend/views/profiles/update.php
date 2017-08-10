<?php

use yii\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use kartik\widgets\DatePicker;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MoviesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $profile \common\models\Profiles */
/* @var $cities array */
/* @var $languages array */

$this->title = 'Profile';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default text-center">
    <div class="panel-heading">
        Profile
    </div>

    <div class="panel-body">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="col-xs-12 no-padding">
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'firstname', [
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-user-circle-o'></i>"
                            ]
                        ]
                    ])->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert Firstname...'
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'lastname', [
                        'addon' => [
                            'prepend' => [
                                'content' => "<i class='fa fa-user-circle-o'></i>"
                            ]
                        ]
                    ])->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert Lastname...'
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
                            'placeholder' => 'Insert Nickname...'
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
                            'placeholder' => 'Insert Phone Number...',
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'birthday')->widget(DatePicker::classname(), [
                        'options' => [
                            'placeholder' => $profile->getAttributeLabel('birthday')
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
                            'placeholder' => 'Insert work...'
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
                            'placeholder' => $profile->getAttributeLabel('currentCity'),
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
                            'placeholder' => $profile->getAttributeLabel('birthCity'),
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
                            'placeholder' => $profile->getAttributeLabel('knownLanguages'),
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
                            'placeholder' => $profile->getAttributeLabel('sex'),
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
                            'placeholder' => $profile->getAttributeLabel('interestedIn'),
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
                            'placeholder' => $profile->getAttributeLabel('relationship'),
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
                        'placeholder' => 'Insert short url...'
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
                            'placeholder' => 'Insert Address...'
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
                            'placeholder' => 'Insert description...'
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
                        'maxFileSize' => 2800
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
                        'maxFileSize' => 2800
                    ]
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6">
                <?= Html::resetButton('Reset', ['class' => 'btn btn-primary edit-profile-reset col-xs-8 col-xs-offset-2']) ?>
            </div>

            <div class="col-md-6">
                <?= Html::submitButton('Update', ['class' => 'btn btn-danger edit-profile-save col-xs-8 col-xs-offset-2', 'data-method' => 'post']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
