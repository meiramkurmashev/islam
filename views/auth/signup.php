<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-comment mr0">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="site-login">
                <h1><?= Html::encode($this->title) ?></h1>

                <p>Please fill out the following fields to login:</p>

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                        'inputOptions' => ['class' => 'col-lg-3 form-control'],
                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
                ]); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email')->textInput() ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>



                    <div class="form-group">
                        <div class="offset-lg-1 col-lg-11">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    </div>

                <?php ActiveForm::end(); ?>

                <div class="offset-lg-1" style="color:#999;">
                    You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
                    To modify the username/password, please check out the code <code>app\models\User::$users</code>.
                </div>
            </div>
        </div>
    </div>
</div>

