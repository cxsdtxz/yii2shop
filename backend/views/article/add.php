<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'article_category_id')->dropDownList($model->ArticleCategorys);
echo $form->field($model, 'sort')->textInput();
echo $form->field($content,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' => 'zh-cn', //中文为 zh-cn
    ]
]);
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();