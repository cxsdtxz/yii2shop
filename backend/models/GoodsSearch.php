<?php
namespace backend\models;


use yii\base\Model;

class GoodsSearch extends Model {

    public $name;
    public $sn;
    public $price_min;
    public $price_max;

    public function rules()
    {
        return [
            [['name','sn','price_min','price_max'],'safe']
        ];
    }


}