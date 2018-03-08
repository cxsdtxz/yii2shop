<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name 菜单名称
 * @property int $parent_id 上级id
 * @property string $url 上级id
 * @property int $sourt 排序
 */
class Menu extends \yii\db\ActiveRecord
{
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'url', 'sourt'], 'required'],
            [['parent_id', 'sourt'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
            ['name','unique'],
            ['parent_id','validateParent','on'=>self::SCENARIO_EDIT]
        ];
    }

    /**
     * 验证父分类id
     * 上级分类不能修改到自己的下级
     */
    public function validateParent(){
        //如果是顶级分类,就判断修改后的父id是否是自己的下级
        $request = Yii::$app->request;
        $menu = Menu::findOne(['id'=>$request->get('id')]);
        $chidren = Menu::find()->where(['parent_id'=>$menu->id])->all();
        $items = [];
        foreach ($chidren as $child){
            $items[] = $child->id;
        }
        if($menu->parent_id == 0){
            if(in_array($this->parent_id,$items)){
                //添加错误信息
                return $this->addError('parent_id','上级分类不能修改为下级分类');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '上级菜单',
            'url' => '地址(路由)',
            'sourt' => '排序',
        ];
    }

    //获取所有菜单数据 组成数组
    public static function getMenus(){
        //获取所有菜单
        $menus = Menu::find()->all();
        $items[0] = "顶级菜单";
        foreach ($menus as $menu){
            $items[$menu->id] = $menu->name;
        }
        return $items;
    }

    //获取所有权限,权限名称为路由
    public static function getUrls(){
        $authManager = Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        $items[0] = "顶级菜单请选";
        foreach ($permissions as $permission){
            $items[$permission->name] = $permission->name;
        }
        return $items;
    }

    //拼导航栏的数组
    public static function getItems($menuItems){
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        //将所有顶级分类的id查出来
        foreach ($menus as $menu) {
            //找到每个顶级分类的下级有多少分类
            $children = Menu::find()->where(['parent_id'=>$menu->id])->all();
            $items = [];
            foreach ($children as $v){
                //拼接items,只添加有权限的二级菜单
                if(Yii::$app->user->can($v->url)){
                    $items[] = ['label'=>$v->name,'url'=>["{$v->url}"]];
                }
            }
            //只添加有子菜单的一级菜单
            if($items){
                $menuItems[] = ['label'=>$menu->name,'items'=>$items];
            }
        }
        return $menuItems;
    }
}
