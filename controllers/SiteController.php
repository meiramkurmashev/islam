<?php

namespace app\controllers;

use app\models\Tag;
use app\models\ArticleTag;
use app\models\Article;
use app\models\Category;
use app\models\CommentForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $data = Article::getAll(3);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();

    return $this->render('index', [
         'articles' => $data['articles'],
         'pagination' => $data['pagination'],
         'popular' => $popular,
         'recent' => $recent,
         'categories' => $categories,

    ]);





    }

    /**
     * Login action.
     *
     * @return Response|string
     */

    public function actionView($id)
    {



        $article = Article::findOne($id);
        $categories = Category::getAll();
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $comments = $article->getArticleComments();
        $commentForm = new CommentForm();
        $article->viewedCounter();


        return $this->render('single',[
             'article' => $article,
             'popular' => $popular,
             'recent' => $recent,
             'categories' => $categories,
             'comments'=> $comments,
             'commentForm'=> $commentForm,

             ]);
    }

    public function actionCategory($id)
    {
        $data = Category::getArticlesByCategory($id);
        $categories = Category::getAll();
        $popular = Article::getPopular();
        $recent = Article::getRecent();



        return $this->render('category',[
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' => $recent,
            'categories' => $categories,

        ]);
    }

    public function actionTag($id)
    {
        // var_dump($articlesByTag->articles[0]['id']);die;
        //  var_dump($articlesByTag->articles);die;
        $data = Tag::getArticlesByTag($id);
        $categories = Category::getAll();
        $popular = Article::getPopular();
        $recent = Article::getRecent();



        return $this->render('tag',[
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' => $recent,
            'categories' => $categories,


        ]);
    }




    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionComment($id)
    {
        $model = new CommentForm();

        if (Yii::$app->request->isPost)
        {

            $model->load(Yii::$app->request->post());
            if($model->saveComment($id))
            {
                Yii::$app->getSession()->setFlash('comment', 'Your comment will be added soon!');
                return $this->redirect(['site/view','id'=>$id]);
            }
        }
    }


}
