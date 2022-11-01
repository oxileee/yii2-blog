<?php
use app\models\Article;
use app\models\Comment;
use app\models\CommentForm;
use yii\widgets\ActiveForm;

/**
 * @var Article $article
 * @var Comment $comment
 * @var CommentForm $commentForm
 */

if (!empty($comments)) : ?>
    <?php foreach ($comments as $comment) : ?>
        <div class="bottom-comment"><!--bottom comment-->
            <div class="comment-img">
                <a href="#"><img width="50" class="img-circle" src="<?= $comment->user->image ?>" alt=""></a>
            </div>
            <div class="comment-text">
                <a href="#" class="replay btn pull-right">Цитировать</a>
                <h5><a href="#"><?= $comment->user->name ?></a></h5>
                <p class="comment-date"><?= $comment->getDate() ?></p>
                <p class="para"><?= $comment->text ?></p>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>
<!-- end bottom comment-->
<?php if (!Yii::$app->user->isGuest) : ?>
    <div class="leave-comment"><!--leave comment-->
        <h4>Leave a reply</h4>
        <?php if (Yii::$app->session->getFlash('comment')) : ?>
            <div class="alert alert-success" role="alert">
                <?= Yii::$app->session->getFlash('comment') ?>
            </div>
        <?php endif ?>
        <?php $form = ActiveForm::begin([
            'action' => ['site/comment', 'id' => $article->id],
            'options' => ['class' => 'form-horizontal contact-form', 'role' => 'form'],
        ]) ?>
        <div class="form-group">
            <div class="col-md-12">
                <?= $form->field($commentForm, 'comment')
                    ->textarea(['class' => 'form-control', 'placeholder' => 'Write Message'])
                    ->label(false) ?>
            </div>
        </div>
        <button type="submit" class="btn send-btn">Post Comment</button>
        <?php ActiveForm::end() ?>
    </div><!--end leave comment-->
<?php endif ?>