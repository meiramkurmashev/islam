<?php

namespace app\models;

use yii\base\Model;
use Yii;

class CommentForm extends Model
{
	public $comment;

	public function rules()
	{
		return [
			[['comment'], 'required'],
			[['comment'], 'string', 'length'=> [3,250]],//валидация для нашего поля 'комментарий'
		];
	}

	public function saveComment($article_id)
	{
		$comment = new Comment;
		$comment->text = $this->comment;
		$comment->user_id = Yii::$app->user->id;
		$comment->article_id = $article_id;
		$comment->status = 0;//1-подтвержден, 0-ждет подтверджения
		$comment->date = date('Y-m-d');
		return $comment->save();
	}
}