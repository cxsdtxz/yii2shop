<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $is_deleted
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'article_category_id', 'sort', 'is_deleted'], 'required'],
            [['article_category_id', 'sort', 'is_deleted', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['intro'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
            'is_deleted' => '状态 0正常1删除',
            'create_time' => '创建时间',
        ];
    }

    public function getArticleCategorys(){
        //查询分类表的数据
        $articleCategorys = Articlecategory::find()->all();
        $arr = [];
        foreach($articleCategorys as $articleCategory){
            $arr[$articleCategory->id] = $articleCategory->name;
        }
        return $arr;
    }

    public function getArticleCategory(){
        return $this->hasOne(Articlecategory::className(),['id'=>'article_category_id']);
    }
}
