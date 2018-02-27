<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "articlecategory".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $is_deleted
 */
class Articlecategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articlecategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'sort', 'is_deleted'], 'required'],
            [['sort', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['intro'], 'string', 'max' => 255],
            ['name','unique']
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
            'sort' => '排序',
            'is_deleted' => '状态 0正常1删除',
        ];
    }
}
