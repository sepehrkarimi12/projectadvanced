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
        return $this->hasMany(Category::className(), ['id' => 'product_id'])
          ->viaTable(CategoryProduct::tableName(), ['category_id' => 'id']);
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
        $delete_all_old_selected_categories = CategoryProduct::deleteAll([
            'product_id' => $this->id,
        ]);

        // save permissions in authitemchild
        if (isset($_POST['categories'])){
            foreach ($_POST['categories'] as $v) {
                $category = new CategoryProduct;
                $category->product_id = $this->id;
                $category->category_id = $v;
                $category->save();
            }
        }
    }

}
