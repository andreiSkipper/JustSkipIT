<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

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

    public function upload()
    {
        if ($this->validate()) {
            if (!is_dir('uploads/')) {// Tells whether the filename is a directory
                mkdir('uploads/');//Makes directory
            }

            if (!is_dir('uploads/' . $this->id . '/')) {// Tells whether the filename is a directory{
                mkdir('uploads/' . $this->id . '/');//Makes directory
            }

            $this->filename = uniqid('', TRUE) . '.' . $this->imageFile->extension;
            $this->filepath = 'uploads/' . $this->id . '/';
//            $this->imageFile->saveAs('../uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
//            $this->imageFile->saveAs('../uploads/' . $this->id . '/' . $this->filename . '.' . $this->imageFile->extension);
            $this->imageFile->saveAs('uploads/' . $this->id . '/' . $this->filename);
            return true;
        } else {
            return false;
        }
    }
}

?>