<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'old_password')->passwordInput()->label('旧密码');
echo $form->field($model,'new_password')->passwordInput()->label('新密码');
echo $form->field($model,'re_password')->passwordInput()->label('确认密码');
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();