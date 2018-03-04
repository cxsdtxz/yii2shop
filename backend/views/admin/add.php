<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
if ($model->getIsNewRecord()){
    echo $form->field($model,'password_hash')->passwordInput();
    echo $form->field($model,'confirm')->passwordInput();
}
echo $form->field($model,'email')->textInput();
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();