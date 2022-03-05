<?php

namespace app\controllers;


use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\web\Controller;


class AuthController extends Controller
{
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {//если пользователь не гость
            return $this->goHome();// то отправляем на главную
        }

        $model = new LoginForm(); //если все же пользователь является гостем тогда форма логина
        if ($model->load(Yii::$app->request->post()) && $model->login()) {// выводим поля : логин и пароль, проверяем на true'шность 2 этих условия
            return $this->goBack(); // и если вернуло true значит пользователь авторизован, и возвращаем его[пользователя] назад
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
      $model = new SignupForm();

      if(Yii::$app->request->isPost)
      {
        $model->load(Yii::$app->request->post());
        if($model->signup())
        {
          return $this->redirect(['auth/login']);
        }

      }

      return $this->render('signup', ['model'=>$model]);
    }

    public function actionLoginVk($uid, $first_name, $photo) // экшн для ВК
    {
      $user = new User();
      if($user->saveFromVk($uid, $first_name, $photo))
      {
        return $this->redirect(['site/index']);
      }
    }


}

