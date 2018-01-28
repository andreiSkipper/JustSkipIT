<?php

use common\models\Actions;
use yii\widgets\LinkPager;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\select2\Select2;
use kartik\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use common\models\Translations;

/* @var $this yii\web\View */
/* @var $actions array */
/* @var $pages string */

$this->title = Translations::translate('app', 'Home');
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <?php if (!Yii::$app->user->isGuest) { ?>
            <?php $form = ActiveForm::begin(['id' => 'add-post-form', 'action' => ['/add-post'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="panel panel-default action-new">
                <!--            <div class="panel-heading action-header"></div>-->
                <div class="panel-body ">
                    <div class="col-xs-12">
                        <?php
                        $newAction = new Actions();
                        $newAction->privacy = 'Public';
                        ?>
                        <?= $form->field($newAction, 'description')->textarea(
                            [
                                'maxlength' => true,
                                'placeholder' => Translations::translate('app', 'What are you thinking about?'),
                                'style' => 'resize: vertical;min-height: 50px'
                            ])->label(false);
                        ?>
                    </div>
                    <div class="col-xs-12">
                        <?= $form->field($newAction, 'imagePath')->widget(FileInput::classname(), [
                            'options' => [
                                'accept' => 'image/*',
                                'multiple' => true
                            ],
                            'pluginOptions' => [
                                'showPreview' => true,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'browseLabel' => Translations::translate('app', 'Browse') . ' ...',
                            ]
                        ])->label(false) ?>
                    </div>
                    <div class="col-xs-12 no-padding">
                        <div class="col-xs-6">
                            <?= $form->field($newAction, 'privacy')->widget(Select2::classname(),
                                [
                                    'name' => 'Actions[type]',
                                    'data' => $newAction->getPrivacies(),
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'hideSearch' => true,
                                    'options' => [
                                        'placeholder' => Translations::translate('app', 'Privacy'),
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                    'addon' => [
                                        'prepend' => [
                                            'content' => Html::icon('globe')
                                        ],
                                    ]
                                ])->label(false) ?>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <?= Html::submitButton('<i class="fa fa-pencil-square-o"></i> ' . Translations::translate('app', 'Post'),
                                    [
                                        'class' => 'btn btn-orange col-xs-12',
                                        'name' => 'action-edit-button',
                                        'onclick' => "
//                                                        var formData = $('#add-post-form').serializeArray().reduce(function(obj, item) {
//                                                                    obj[item.name.replace('Actions[', '')] = item.value;
//                                                                    return obj;
//                                                                }, {});
//                                                        formData.imagePath = $('#actions-imagepath')[0].files[0];
//                                                        $.ajax({
//                                                           url:'" . Url::to(['/add-post']) . "',
//                                                           type: 'POST',
//                                                           data: {
//                                                               'Actions': formData,
//                                                           },
//                                                           success: function(response) {
//                                                                result = JSON.parse(response);
//                                                                if (result.html.length != 0) {
//                                                                    $('#actions-group').prepend(result.html).fadeIn(10000);
//                                                                }                                                                    
//                                                                $('#add-post-form').reset;
//                                                           },
//                                                           error: function (request, status, error) {
//                                                                window.alert(error);
//                                                           }
//                                                       });                                                
                                        ",
                                    ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--            <div class="panel-footer"></div>-->
            </div>
            <?php ActiveForm::end(); ?>
        <?php } ?>
        <div id="actions-group">
            <?php
            foreach ($actions as $action) {
                echo $this->render('/actions/panel', ['action' => $action]);
            } ?>
        </div>

        <p id="loading" class="text-center" data-offset="<?= count($actions) ?>" data-total="<?= $pages->totalCount ?>"
           style="display: none;">
            <span class="fa fa-spinner fa-pulse fa-4x"></span>
        </p>
    </div>
</div>

<script>
    $(document).ready(function () {
        var win = $(window);

        // Each time the user scrolls
        win.scroll(function () {
            // End of the document reached?
            var loading = $('#loading');
            if ($(document).height() - win.height() == win.scrollTop() && !loading.is(":visible")) {
                var offset = loading.data('offset');
                var total = loading.data('total');

                if (offset < total) {
                    loading.show();
                    $.ajax({
                        url: '',
                        type: 'POST',
                        data: {
                            offset: offset
                        },
                        success: function (response) {
                            result = JSON.parse(response);
                            if (result.html.length != 0) {
                                $("#actions-group").append(result.html).fadeIn(10000);
                                loading.data('offset', offset + result.actions);
                            }
                            loading.hide();
                        },
                        error: function (request, status, error) {
                            window.alert(error);
                        }
                    });
                }
            }
        });
    });
</script>

<?php

$this->registerJs("
var notice = new PNotify({
    title: '" . Translations::translate('app', 'Welcome to') . "',
    text: '" . Html::img('@web/uploads/logos/JustSkipIT_logo1.png', ['style' => 'width: 100%']) . "',
    type: 'info',
    addclass: 'custom',
    icon: 'fa fa-globe faa-bounce animated',
    buttons: {
        classes: {
            closer: 'fa fa-times-circle-o',
            pin_up: 'fa fa-pause-circle-o',
            pin_down: 'fa fa-play-circle-o'
        },
//        closer: false,
//        sticker: false
    },
//    hide: false,
    animate: {
        animate: true,
        in_class: '" . Actions::getNotificationAnimation('in') . "',
        out_class: '" . Actions::getNotificationAnimation('out') . "'
    }
});
//notice.get().click(function() {
//    notice.remove();
//});
", View::POS_END);
?>
