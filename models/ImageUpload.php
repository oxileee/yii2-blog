<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

// не наследуется от ActiveRecord
// это значит что никакая таблица не будет связана с этой моделью
class ImageUpload extends Model
{

    public $image;

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg,png']
        ];
    }

    // метод выгрузки файла в директорию uploads
    // указываем тип класса принимаемого атрибута
    // в $currentImage принимаем имя файла из поля image в БД
    public function uploadFile(UploadedFile $file, $currentImage)
    {
        $this->image = $file;

        // условие с валидацией правил в rules
        if($this->validate()) {
            $this->deleteCurrentImage($currentImage);

            return $this->saveImage();
        }

        return false;
    }

    // получаю
    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    // с помощью uniqid добавляю к названию файла уникальный id
    // потом хеширую в md5
    // и привожу к нижнему регистру
    // из этого получаю уникальное имя файла
    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->image->baseName, true)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($currentImage)
    {
        // если файл с переданным названием существует
        if($this->fileExists($currentImage)) {
            // с помощью unlink удаляю картинку из хранилища с названием которое есть в БД
            unlink($this->getFolder() . $currentImage);
        }
    }

    public function fileExists($currentImage)
    {
        if (!empty($currentImage) && $currentImage !== null) {
            return file_exists($this->getFolder() . $currentImage);
        }
        return false;
    }

    public function saveImage()
    {
        $filename = $this->generateFilename();
        // в метод saveAs передаём куда сохранить файл
        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }
}