<?php

namespace frontend\models;

use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $title
 * @property int $price
 * @property boolean is_deleted
 *
 * @property CategoryProduct[] $categoryProducts
 */
class Product extends \yii\db\ActiveRecord
{
    public $categories;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'price', ], 'required'],
            [['price'], 'integer'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'price' => Yii::t('app', 'Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getCategoryProducts()
    // {
    //     return $this->hasMany(CategoryProduct::className(), ['product_id' => 'id']);
    // }

    public function getCategories() {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
          ->viaTable(CategoryProduct::tableName(), ['product_id' => 'id']);
    }

    public function getAllCategories()
    {
        $all = Category::find()->all();
        return ArrayHelper::map($all, 'id', 'title');
    }

    public function getSelectedCategories()
    {
        if($this->isNewRecord)
            return [];

        $selected_categories = CategoryProduct::findAll(['product_id' => $this->id]);
        $selected_categories = ArrayHelper::getColumn($selected_categories, 'category_id');
        $categories = Category::findAll(['id' => $selected_categories]);
        return ArrayHelper::getColumn($categories, 'id');
    }

    public function afterSave($insert, $changedAttributes)
    {
        // echo "<pre>";
        // print_r($this);
        // die;
        $delete_all_old_selected_categories = CategoryProduct::deleteAll([
            'product_id' => $this->id,
        ]);

        // save categories in authitemchild
        if (isset($_POST['categories'])){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($_POST['categories'] as $v) {
                    $category = Category::findOne($v);
                    $this->link('categories', $category);
                }
                // die;
                $transaction->commit();
            }
            catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }

    // public function beforeDelete()
    // {
    //     $delete_all_old_selected_categories = CategoryProduct::deleteAll([
    //         'product_id' => $this->id,
    //     ]);
    //     return true;
    // }

    public static function find()
    {
        return parent::find()->where(['!=', 'is_deleted', 1]);
    }

    public function delete()
    {
        $model = self::findOne($this->id);
        return $model->updateAttributes(['is_deleted' => '1']);
    }

}
