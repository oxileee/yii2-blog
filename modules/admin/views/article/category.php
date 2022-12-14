<?php

use app\models\Article;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var app\models\Article $model */
/* @var yii\widgets\ActiveForm $form */
/* @var Article $selectedCategoryId */
/* @var array $categories */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::dropDownList('category', $selectedCategoryId, $categories, ['class' => 'form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
