<?php

namespace frontend\models;

use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property Role[] $children
 * @property Role[] $parents
 */
class Role extends \yii\db\ActiveRecord
{
    public $permissions;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Role::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(Role::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    public function getAllPermissions()
    {
        $all=Role::findAll(['type'=>2]);
        $all=ArrayHelper::map($all,'name','name');
        // print_r($all);die();
        return $all;
    }

    public function getSelectedPermissions()
    {
        if($this->isNewRecord){
            return [];
        }

        $all=Authitemchild::findAll(['parent'=>$this->name]);
        $all=ArrayHelper::getColumn($all,'child');
        return $all;
    }

    public function Save ($runValidation = true, $attributeNames = NULL)
    {
        $auth=Yii::$app->authManager;

        // save role in authitem
        $role=$auth->createRole($this->name);
        $role->description=$this->description;
        $auth->add($role);

        // save permissions in authitemchild
        foreach ($_POST['permissions'] as $v) {
            $child=new Authitemchild;
            $child->parent=$role->name;
            $child->child=$v;
            $child->save();
        }

        return true;
    }

    public function update($runValidation = true, $attributeNames = NULL)
    {
        $child=new Authitemchild;
        $child->deleteAll(['parent'=>$this->name]);

        foreach ($_POST['permissions'] as $v) {
            $child=new Authitemchild;
            $child->parent=$this->name;
            $child->child=$v;
            $child->save();
        }
        // die();

        parent::update();
    }

}
