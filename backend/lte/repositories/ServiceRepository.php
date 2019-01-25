<?php
namespace app\modules\lte\repositories;

use app\modules\package\models\Type;
use app\modules\productmaker\models\ProductMakerFactorType;
use Yii;

/**
 * This class has been used in ServiceController
 * Class ServiceRepository
 * @package app\modules\phone_package\repositories
 * @author Mehran
 */
class ServiceRepository
{
    /**
     * Returns name of class.
     *
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }

    public static function getProductmakerFactor($type)
    {
        $type = Type::find()->Where(['value' => $type])->One();
        $product_maker = ProductMakerFactorType::find()
            ->joinwith(['factor'])
            ->Where(['type_id' => $type->id])
            ->AndWhere(['reseller_id' => Yii::$app->user->identity->reseller_id])
            ->AndWhere(['is_deleted' => 0])
            ->All();
        if ($product_maker != array()) {
            foreach ($product_maker as $key_product => $val_product) {
                if ($val_product->factor[0]->cost == 'other') {
                    $cost = $val_product->factor[0]->is_optional ? '(اجباری)' : '(اختیاری)';
                    $factor_product_maker[$val_product->factor_id]['price'] = number_format($val_product->factor[0]->price) . 'ریال' . $cost;
                    $factor_product_maker[$val_product->factor_id]['original_price'] = $val_product->factor[0]->price;
                    $factor_product_maker[$val_product->factor_id]['is_optional'] = $val_product->factor[0]->is_optional;
                    $factor_product_maker[$val_product->factor_id]['id'] = $val_product->factor[0]->id;
                }
            }
        } else {
            $factor_product_maker = [];
        }
        return $factor_product_maker;
    }
}
