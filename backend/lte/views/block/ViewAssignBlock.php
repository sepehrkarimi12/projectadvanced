<?php
use yii\helpers\Url;
$baseUrl = Url::home(true);

$this->title = 'نمایش رنج شماره';
$this->params['breadcrumbs'][] = 'بلوک ساز';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="row">
    <div class="col-xs-12">
        <a class="icon-btn">
            <i class="fa fa-mobile text-success"></i>
            <div><span class="text-success">فعال</span></div>
        </a>
        <a class="icon-btn">
            <i class="fa fa-mobile text-danger"></i>
            <div><span class="text-danger">غیر فعال</span></div>
        </a>
        <a class="icon-btn">
            <i class="fa fa-ban text-muted"></i>
            <div><span class="text-muted">مسدود</span></div>
        </a>
        <a class="icon-btn">
            <i class="fa fa-mobile text-warning"></i>
            <div><span class="text-warning">معلق</span></div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <p>لیست بلوک شماره انتخاب شده.</p>
        <?php
        foreach ($lte_numbers as $key => $lte_number) {

            $class = "";
            $icon = "mobile";

            if ($lte_number['status'] == 'فعال') {
                $color = 'text-success';
                $icon_color = "text-success";
                $class = "critical-delete";
                $attr = 'href="'.$baseUrl.'manage/customer/user-actions/'.$lte_number['buyer_id'].'"  title="مشاهده پروفایل"';
            } else if($lte_number['status'] == 'غیر فعال') {
                $color = 'text-danger';
                $icon_color = "text-danger";
                $attr = 'href="javascript: void(0)"  title="غیر فعال" ';
            } else if($lte_number['status'] == 'مسدود') {
                $icon = "fa fa-ban ";
                $color = 'text-muted';
                $icon_color = "text-muted";
                $attr = 'href="'.$baseUrl.'manage/customer/user-actions/'.$lte_number['buyer_id'].'"  title="مشاهده پروفایل"';
            } else if($lte_number['status'] == 'معلق') {
                $color = 'text-danger';
                $icon_color = "text-danger";
                $class = "critical-delete";
                $attr = 'href="'.$baseUrl.'manage/customer/user-actions/'.$lte_number['buyer_id'].'"  title="مشاهده پروفایل"';
            }
            echo '<a class="icon-btn '.$class.' '.$color.'" '.$attr.' >
		        <i class="fa fa-'. $icon .' '.$icon_color.'"></i>
		        <div><span class="'.$color.'">'.$lte_number['number'].'</span></div>
		    </a>';
        }
        ?>
    </div>
</div>