<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property string $name 收货人
 * @property string $province 省
 * @property string $city 城市
 * @property string $county 县
 * @property string $address 详细地址
 * @property string $tel 手机号
 * @property string $default 0不设为默认地址 1设为默认地址
 */
class Address extends \yii\db\ActiveRecord
{
    public $def;
    public $address_id;
    const SCENARIO_EDIT = 'edit';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province', 'city', 'county', 'address', 'tel'], 'required'],
            [['name'], 'string', 'max' => 40],
            [['province', 'city', 'county'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
            ['def','safe'],
            ['address_id','safe','on'=>self::SCENARIO_EDIT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'province' => '省',
            'city' => '城市',
            'county' => '县',
            'address' => '详细地址',
            'tel' => '手机号',
            'default' => '0不设为默认地址 1设为默认地址',
        ];
    }
}
