<?php

namespace app\models;

use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $discription
 * @property string|null $content
 * @property string|null $date
 * @property string|null $image
 * @property int|null $viewed
 * @property int|null $user_id
 * @property int|null $status
 * @property int|null $category_id
 *
 * @property ArticleTag[] $articleTags
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**   [['title','description','content'], 'required'],
           [['title', 'description','content'],'string'],
          + [['date'], 'date', 'format'=>'php:Y-m-d'],
           +[['date'], 'default', 'value' => date('Y-m-d')],
           +[['title'],'string', 'max'=>255]
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discription', 'content'], 'string'],
            [['title','discription', 'content'], 'required'],
            [['date'], 'date', 'format'=>'php:Y-m-d'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['viewed', 'user_id', 'status', 'category_id'], 'integer'],
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
            'discription' => 'Discription',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }

    public function saveImage($filename)
    {
        $this->image = $filename;
        return $this->save(false);
    }

    public function deleteImage()
    {
        $imageUploadModel = new ImageUpload();
        $imageUploadModel->deleteCurrentImage($this->image);

    }

    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete();
    }

    public function getImage()
    {
            return ($this->image)? '/uploads/'. $this->image : '/no-image.png';
    }


    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);
        if($category != null)
        {
            $this->link('category', $category);
            return true;
        }
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(),['id'=>'tag_id'])
        ->viaTable('article_tag', ['article_id'=>'id']);
    }

    public function getSelectedTags()
    {
        $selectedIds = $this->getTags()->select('id')->asArray()->all();
        return ArrayHelper::getColumn($selectedIds,'id');
    }

    public function saveTags($tags)
    {
        if(is_array($tags))
        {
            $this->clearCurrentTags();
            foreach ($tags as $tag_id)
            {
                $tag = Tag::findOne($tag_id);
                $this->link('tags',$tag);
            }
        }
    }

    public function clearCurrentTags()
    {
         ArticleTag::deleteAll(['article_id'=>$this->id]);
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->date);
    }

    public static function getAll($pageSize = 5)
    {
         $query = Article::find()->orderBy('date desc');
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' =>$pageSize]);
        $articles=$query->offset($pagination->offset)
            ->limit($pagination->limit) //сколько всего статей брать из базы
            ->all();
        $data['articles']=$articles;
        $data['pagination']=$pagination;
        return $data;
    }

    public static function getPopular()
    {
        return Article::find()->orderBy('viewed desc')->limit(3)->all();
    }

    public static function getRecent()
    {
        return Article::find()->orderBy('date asc')->limit(4)->all();
    }

    public function saveArticle()
    {
        $this->user_id = Yii::$app->user->id;
        return $this->save();
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['article_id'=>'id']);//этот метод нужен для тэгов
    }

    public function getArticleComments()
    {
        return $this->getComments()->where(['status'=>1])->all();
    }



    public function getAuthor()
    {
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    public function viewedCounter()
    {
        $this->viewed += 1;
        return $this->save(false);
    }
}

