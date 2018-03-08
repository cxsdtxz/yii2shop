
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'verify')->widget(\yii\captcha\Captcha::class,[
    'captchaAction'=>'admin/captcha'
]);
echo $form->field($model,'auto_login')->checkbox();
echo '<button class="btn btn-primary" type="submit">登录</button>';
\yii\bootstrap\ActiveForm::end();