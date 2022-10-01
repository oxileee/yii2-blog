<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string|null $title
 *
 * @property ArticleTag[] $articleTags
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[ArticleTags]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['id' => 'article_id'])
            ->viaTable('article_tag', ['tag_id' => 'id']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getTitle()
    {
        return $this->hasOne(ArticleTag::class, ['id' => 'article_id'])
            ->viaTable('article_tag', ['tag_id' => 'id']);
    }

    public static function getArticlesByTag($id)
    {
        $query = Article::find()
            ->innerJoin('article_tag', 'article.id = article_tag.article_id')
            ->andWhere(['article_tag.tag_id' => $id]);

        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 6]);
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'articles' => $articles,
            'pagination' => $pagination,
        ];
    }
}
