<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = 'Doodhvale Login';
//$this->params['breadcrumbs'][] = $this->title;
?>
<body class="login-page">

   <div class="login-box">
      <!--div class="login-logo">
        <a href="<?php //echo Yii::$app->getUrlManager()->createUrl(['site/index']);?>"><b>DrCloud</b>EMR</a>
      </div><!-- /.login-logo -->
	  <div id="logo-lockup" style="text-align:center;"> <img src= "<?= Yii::$app->request->baseUrl ?>/admin-lte/dist/img/doodhvale.png" style="height:130px;width:100%; margin-bottom:10px;" alt="Logo" />
				</div>
  
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

            <?php $form = ActiveForm::begin([ 	'id' => 'login-form',
										'validateOnSubmit'=>true,
									]); ?>
			<div class="form-group">
                <?= $form->field($model, 'username', [
					'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]
					])->textInput(['placeholder' =>'User Name']) ?>
				</div>
			<div class="form-group">
                <?= $form->field($model, 'password', [
					'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-lock"></i>']]])->passwordInput(['placeholder' =>'Password']) ?>
				 </div>          
             <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                 <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
			  <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div><!-- /.col -->
          </div>
		
            <?php ActiveForm::end(); ?>
      <div class="social-auth-links text-center">
          <!--p>- OR -</p -->
		 <?php /* yii\authclient\widgets\AuthChoice::widget([
									 'baseAuthUrl' => ['site/auth']
								]) */ ?>
        </div><!-- /.social-auth-links -->
		<?php //echo Html::a('I forgot my password', ['site/request-password-reset']) ?>
		<?php //echo Html::a('Register a new membership', ['site/signup']) ?>
      </div><!-- /.login-box-body -->
	    </div><!-- /.login-box -->

    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>