<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'logo')->textInput();
echo $form->field($model,'goods_category_id')->textInput();
echo $form->field($model,'brand_id')->textInput();
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([
    1=>'在售',0=>'下架'
]);
echo $form->field($model,'sort')->textInput();
echo '<button type="submit" class="btn btn-primary">提交</button>';


//name	varchar(20)	商品名称
//sn	varchar(20)	货号
//logo	varchar(255)	LOGO图片
//goods_category_id	int	商品分类id
//brand_id	int	品牌分类
//market_price	decimal(10,2)	市场价格
//shop_price	decimal(10, 2)	商品价格
//stock	int	库存
//is_on_sale	int(1)	是否在售(1在售 0下架)
//status	inter(1)	状态(1正常 0回收站)
//sort	int()	排序
//create_time	int()	添加时间
//view_times	int()	浏览次数
\yii\bootstrap\ActiveForm::end();