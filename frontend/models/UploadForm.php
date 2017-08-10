<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use common\models\Actions;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $id;
    public $filename;
    public $filepath;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($actionParams = [])
    {
        if ($this->validate()) {
            if (!is_dir('uploads/')) {// Tells whether the filename is a directory
                mkdir('uploads/');//Makes directory
            }

            if (!is_dir('uploads/profiles')) {// Tells whether the filename is a directory
                mkdir('uploads/profiles');//Makes directory
            }

            if (!is_dir('uploads/profiles/' . $this->id . '/')) {// Tells whether the filename is a directory{
                mkdir('uploads/profiles/' . $this->id . '/');//Makes directory
            }

            $this->filename = uniqid('', TRUE) . '.' . $this->imageFile->extension;
            $this->filepath = 'uploads/profiles/' . $this->id . '/';
            $this->imageFile->saveAs('uploads/profiles/' . $this->id . '/' . $this->filename);

            $action = new Actions();
            $action->user_id = Yii::$app->user->identity->id;
            $action->type = isset($actionParams['type']) ? $actionParams['type'] : $action->typeEnum['Photo'];
            $action->imagePath = $this->filepath . $this->filename;
            $action->privacy = $action->privacyEnum['Public'];
            if($action->type == 'Cover' OR $action->type == 'Avatar'){
                $action->save();
            }

            return true;
        } else {
            return false;
        }
    }
}

?>