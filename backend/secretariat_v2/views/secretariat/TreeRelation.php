<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$baseUrl = Url::home(true);

$this->title = 'نمایش درختی';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = 'نامه ها';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="col-sm-12">
    <div class="portlet grey-silver box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i>نمایش درختی
            </div>
        </div>
        <div class="portlet-body">
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-badge">
                        <img class="timeline-badge-userpic" src="<?= $baseUrl ?>asset_admin/custom/img/new.svg" title="جدید" alt="جدید" />
                    </div>
                    <div class="timeline-body">
                        <div class="timeline-body-arrow"> </div>
                        <div class="timeline-body-head m-a-0">
                            <div class="row">
                                <div class="col-xs-6"><span class="text-success small bold"><?= $relation_tree->officeRelation->officeType->name ?></span></div>
                                <div class="col-xs-6 text-right"><span class="text-muted small"><?= $relation_tree->officeRelation->date ?> <i class="fa fa-calendar"></i></span></div>
                                <div class="col-xs-8">
                                    <span class="text-muted small">عنوان: <a href="<?= $baseUrl ?>secretariat_v2/secretariat/view-letter?id=<?= $relation_tree->officeRelation->id ?>" class="timeline-body-title font-blue-madison"><?= $relation_tree->officeRelation->title ?></a>
                                    </span>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <span class="text-muted small">
                                        <span class="timeline-body-title font-blue-madison pull-right">
                                        <?php
                                            if ($relation_tree->officeRelation->category->name == "ورودی") {
                                                echo $relation_tree->officeRelation->officeInputs->number;
                                            } else {
                                                echo $relation_tree->officeRelation->archive_number;
                                            }
                                        ?>
                                        </span> (شماره نامه)
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-body-content m-a-0">
                            <div class="text-muted small">توضیحات:</div>
                            <div class="font-grey-gallery"><?= $relation_tree->officeRelation->description ?></div>
                        </div>
                    </div>
                </div>

                 <div class="timeline-item">
                    <div class="timeline-badge">
                        <?php
                            $category_text = $relation_tree->office->category->name;
                            $category_image = '';
                            switch ($category_text) {
                                case 'عطف': $category_image = $baseUrl . 'asset_admin/custom/img/remind.svg'; break;
                                case 'جدید': $category_image = $baseUrl . 'asset_admin/custom/img/new.svg'; break;
                                case 'پیرو': $category_image = $baseUrl . 'asset_admin/custom/img/reply.svg'; break;
                                case 'بازگشتی': $category_image = $baseUrl . 'asset_admin/custom/img/return.svg'; break;
                                default: break;
                            }
                        ?>
                        <img class="timeline-badge-userpic" src="<?= $category_image ?>" title="<?= $relation_tree->office->category->name ?>" alt="<?= $relation_tree->office->category->name ?>" />
                    </div>
                    <div class="timeline-body">
                        <div class="timeline-body-arrow"> </div>
                        <div class="timeline-body-head m-a-0">
                            <div class="row">
                                <div class="col-xs-6"><span class="text-success small bold"><?= $relation_tree->office->officeType->name . ' - ' . $category_text ?></span></div>
                                <div class="col-xs-6 text-right"><span class="text-muted small"><?= $relation_tree->officeRelation->date ?> <i class="fa fa-calendar"></i></span></div>
                                <div class="col-xs-8">
                                    <span class="text-muted small">عنوان: <a href="<?= $baseUrl ?>secretariat_v2/secretariat/view-letter?id=<?= $relation_tree->office->id ?>" class="timeline-body-title font-blue-madison"><?= $relation_tree->office->title ?></a>
                                    </span>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <span class="text-muted small">
                                        <span class="timeline-body-title font-blue-madison pull-right">
                                        <?php
                                            if ($relation_tree->office->category->name == "ورودی") {
                                                echo $relation_tree->officeRelation->officeInputs->number;
                                            } else {
                                                echo $relation_tree->officeRelation->archive_number;
                                            }
                                        ?>
                                        </span> (شماره نامه)
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-body-content m-a-0">
                            <div class="text-muted small">توضیحات:</div>
                            <div class="font-grey-gallery"><?= $relation_tree->office->description ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
