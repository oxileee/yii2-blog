<?php
use app\models\Article;
use app\models\Category;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var Article[] $articles
 * @var Article[] $popular
 * @var Article[] $recent
 * @var Category[] $categories
 * @var Pagination $pagination
 */
?>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($articles as $article) : ?>
                <article class="post">
                    <div class="post-thumb">
                        <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]) ?>"><img src="<?= $article->getImage() ?>" alt=""></a>
                        <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]) ?>" class="post-thumb-overlay text-center">
                            <div class="text-uppercase text-center">View Post</div>
                        </a>
                    </div>
                    <div class="post-content">
                        <header class="entry-header text-center text-uppercase">
                            <?php if ($article->category !== null) : ?>
                            <h6><a href="<?= Url::toRoute(['site/category', 'id' => $article->category->id]) ?>"><?= $article->category->title ?></a></h6>
                            <?php endif ?>
                            <h1 class="entry-title"><a href="<?= Url::toRoute(['site/view', 'id' => $article->id]) ?>"><?= $article->title ?></a></h1>
                        </header>
                        <div class="entry-content">
                            <p><?= $article->description ?></p>
                            <div class="btn-continue-reading text-center text-uppercase">
                                <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]) ?>" class="more-link">Continue Reading</a>
                            </div>
                        </div>
                        <div class="social-share">
                            <span class="social-share-title pull-left text-capitalize">By <a href="#"><?= $article->user->name ?></a> On <?= $article->getDate() ?></span>
                            <ul class="text-center pull-right">
                                <li><a class="fa" href="#"><i class="fa fa-eye"></i></a></li><?= (int) $article->viewed ?>
                            </ul>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
                <?php echo LinkPager::widget(['pagination' => $pagination]) ?>
            </div>
            <?= $this->render('/partials/sidebar', [
                'popular' => $popular,
                'recent' => $recent,
                'categories' => $categories,
            ]) ?>
        </div>
    </div>
</div>