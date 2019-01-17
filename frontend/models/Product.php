<?php

namespace frontend\models;

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
}
