<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

use yii\helpers\ArrayHelper;

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
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(/*из этой [article] таблицы-->*/Article::className(),['id'/*взять строку с ID ..*/=>/*...которое равно 'article_id' в 3й таблице*/'article_id'])
        ->viaTable('article_tag'/*название связующей [т.е. 3й] таблицы*/, ['tag_id'/*'tag_id' 3й таблицы равен ...*/=>'id'/*...id ЭТОЙ модели*/]);
    }



    public static function getAll()
    {
        return Tag::find()->all();
    }

    public static function getArticlesByTag($id)
    {
        $articlesByTag = Tag::findOne($id);
        $query = $articlesByTag->getArticles();
        $count= $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' =>2]);
        $articles=$query->offset($pagination->offset)
            ->limit($pagination->limit) //сколько всего статей брать из базы
            ->all();
        $data['articles']=$articles;
        $data['pagination']=$pagination;
        return $data;
    }


}
