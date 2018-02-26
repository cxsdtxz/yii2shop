<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $is_deleted
 */
class Brand extends \yii\db\ActiveRecord
{
    public $imgFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sort'], 'required'],
            [['name', 'sort'], 'unique'],
            [['sort', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['intro'], 'string', 'max' => 255],
            ['imgFile','file','extensions'=>['png','jpg']]
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
            'logo' => 'logo',
            'sort' => '排序',
            'is_deleted' => '状态(0正常,1删除)',
        ];
    }
}