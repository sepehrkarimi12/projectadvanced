<?php
use kartik\export\ExportMenu;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\Html;
use app\modules\secretariat_v2\models\Secretariat;

$baseUrl = Url::home(true);

$this->title = 'نمایش نامه';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="col-xs-12 p-a-0">
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet blue box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bookmark"></i> اطلاعات نامه
                    </div>
                </div>
                <div class="portlet-body">

                    <?php if ($office_main->officeType->name == "ورودی"): ?>
                        <div class="row">
                            <div class="col-md-12 m-b-1">
                                <span class="text-muted">عنوان:</span>
                                <span class="h4">
                                <?= $office_main->title ?>
                            </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">گروه بندی:</span>
                                <span class="h4 m-b-1">
                                <?= $category ?>
                            </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">شماره بایگانی:</span>
                                <span class="text-info bold h4 ltr" dir='rtl'>
                                    <?= isset($office_main->officeInputs) ? $office_main->officeInputs->number : null ?>
                                </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">شماره:</span>
                                <span class="h4"><?= $office_main->archive_number ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">تاریخ ثبت:</span>
                                <span>
                                <?= \app\models\Intldate::get()->timestampToPersian($office_main->created_at); ?>
                            </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">پیوست:</span>

                                    <?= isset($office_main->officeAttachments) ? '<span class="text-success"><i class="fa fa-check"></i> دارد</span>' : '<span class="text-danger"><i class="fa fa-close"></i> ندارد</span>' ?>
                                </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">نوع:</span>
                                <span class="bold">
                                </span>
                                <?= $office_main->officeType->name == "ورودی" ? '<span class="text-success"><i class="fa fa-level-down"></i> '. $office_main->officeType->name .'</span>' : '<span class="text-danger"><i class="fa fa-level-down"></i> '. $office_main->officeType->name .'</span>' ?>
                            </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">سطح دسترسی:</span>
                                <span class="">
                                    <?= isset($office_main->access_level) ? $office_main->access_level->name : null ?>
                                </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">اولویت:</span>
                                <span class="">
                                    <?= isset($office_main->priority) ? $office_main->priority->name : null ?>
                                </span>
                            </div>
                        </div>

                    <?php elseif ($office_main->officeType->name == "خروجی"): ?>
                        <div class="row">
                            <div class="col-md-12 m-b-1">
                                <span class="text-muted">عنوان:</span>
                                <span class="h4">
                                <?= $office_main->title ?>
                            </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">گروه بندی:</span>
                                <span class="h4 m-b-1">
                                <?= $category ?>
                            </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">شماره بایگانی:</span>
                                <span class="text-info bold h4 ltr" dir='rtl'><?= $office_main->archive_number ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">تاریخ ثبت:</span>
                                <span>
                                <?= \app\models\Intldate::get()->timestampToPersian($office_main->created_at); ?>
                            </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">پیوست:</span>w
                                    <?= isset($office_main->officeAttachments) ? '<span class="text-success"><i class="fa fa-check"></i> دارد</span>' : '<span class="text-danger"><i class="fa fa-close"></i> ندارد</span>' ?>
                            </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">نوع:</span>
                                <span class="bold">
                                <?= $office_main->officeType->name == "ورودی" ? '<span class="text-success"><i class="fa fa-level-down"></i> '. $office_main->officeType->name .'</span>' : '<span class="text-danger"><i class="fa fa-level-down"></i> '. $office_main->officeType->name .'</span>' ?>
                            </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">سطح دسترسی:</span>
                                <span class="">
                                    <?= isset($office_main->access_level) ? $office_main->access_level->name : null ?>
                            </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">اولویت:</span>
                                <span class="">
                                    <?= isset($office_main->priority) ? $office_main->priority->name : null ?>
                            </span>
                            </div>
                            <div class="col-md-4 m-b-1">
                                <span class="text-muted">رونوشت:</span>
                                <span class="bold">
                                <?php
                                $html = '';
                                if ($transcripts != null) {
                                    foreach ($transcripts as $key => $transcript) {
                                        $html .= '<li>' . $transcript . '</li>';
                                    }
                                } else {
                                    $html .= '<span class="text-danger"><i class="fa fa-close"></i> ندارد</span>';
                                }
                                echo $html;
                                ?>
                            </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 m-b-1">
                                <span class="text-muted">گیرنده:</span>
                                <span class="bold">
                                <?= isset($office_main->officeOutputs->receiver->name) ? $office_main->officeOutputs->receiver->name . ' ' . $office_main->officeOutputs->receiver->family : "" ?>
                                    <span class="text-muted">سمت</span>
                                    <?= isset($office_main->officeOutputs->receiver->occuaption_name) ? $office_main->officeOutputs->receiver->occuaption_name : "" ?>
                                    <span class="text-muted">از واحد</span>
                                    <?= isset($office_main->officeOutputs->receiver->unit_name) ? $office_main->officeOutputs->receiver->unit_name : "" ?>
                                    <span class="text-muted">شرکت</span>
                                    <?= isset($office_main->officeOutputs->receiver->company_name) ? $office_main->officeOutputs->receiver->company_name : "" ?>
                            </span>
                            </div>
                            <div class="col-md-12 m-b-1">
                                <span class="text-muted">فرسنده:</span>
                                <span class="bold">
                                <?= isset($office_main->sender->name) ? $office_main->sender->name . ' ' . $office_main->sender->family : "" ?>
                                    <span class="text-muted">سمت</span>
                                    <?= isset($office_main->sender->occuaption_name) ? $office_main->sender->occuaption_name : "" ?>
                                    <span class="text-muted">از واحد</span>
                                    <?= isset($office_main->sender->unit_name) ? $office_main->sender->unit_name : "" ?>
                                    <span class="text-muted">شرکت</span>
                                    <?= isset($office_main->sender->company_name) ? $office_main->sender->company_name : "" ?>
                            </span>
                            </div>
                            <div class="col-md-12 m-b-1">
                                <span class="text-muted">اقدام کننده:</span>
                                <span class="bold">
                                <?= isset($office_main->officeOutputs->actionator->name) ? $office_main->officeOutputs->actionator->name . ' ' . $office_main->officeOutputs->actionator->family : "" ?>
                                    <span class="text-muted">سمت</span>
                                    <?= isset($office_main->officeOutputs->actionator->occuaption_name) ? $office_main->officeOutputs->actionator->occuaption_name : "" ?>
                                    <span class="text-muted">از واحد</span>
                                    <?= isset($office_main->officeOutputs->actionator->unit_name) ? $office_main->officeOutputs->actionator->unit_name : "" ?>
                                    <span class="text-muted">شرکت</span>
                                    <?= isset($office_main->officeOutputs->actionator->company_name) ? $office_main->officeOutputs->actionator->company_name : "" ?>
                            </span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-xs-12 m-b-1">

                            <?php if(Yii::$app->user->can('SecretariatAssignLetterPerm')): ?>
                                <a href="<?= $baseUrl ?>secretariat_v2/assign/assign-letter?office_id=<?= $office_main->id ?>" class="icon-btn p-a-05 login-as-user-btn font-grey-gallery">
                                    <i class="fa fa-reply-all icon"></i>
                                    <div class="title"> ارجاع به شخص </div>
                                </a>
                            <?php endif; ?>
                            <?php if(Yii::$app->user->can('SecretariatAddLetterPerm') && $office_main->officePhotos == null): ?>
                                <a href="<?= $baseUrl ?>secretariat_v2/secretariat/show-output-letter-code?secretariat_id=<?= $office_main->id ?>" class="icon-btn p-a-05 login-as-user-btn font-green-jungle ">
                                    <i class="fa fa-image icon"></i>
                                    <div class="title"> افزودن تصویر </div>
                                </a>
                            <?php endif; ?>
                            <?php if(Yii::$app->user->can('SecretariatUpdateletterPerm')): ?>
                                <a href="<?= $baseUrl ?>secretariat_v2/secretariat/update-letter?id=<?= $office_main->id ?>" class="icon-btn p-a-05 login-as-user-btn font-blue">
                                    <i class="fa fa-pencil icon"></i>
                                    <div class="title"> ویرایش نامه </div>
                                </a>
                            <?php endif; ?>
                            <?php if(Yii::$app->user->can('SecretariatUpdateLetterFolderPerm')): ?>
                                <a href="<?= $baseUrl ?>secretariat_v2/secretariat/update-letter-folder?office_id=<?= $office_main->id ?>" class="icon-btn p-a-05 login-as-user-btn font-yellow">
                                    <i class="fa fa-folder icon"></i>
                                    <div class="title"> ویرایش پوشه </div>
                                </a>
                            <?php endif; ?>
                            <?php if(Yii::$app->user->can('SecretariatTreeRelationPerm')): ?>
                                <a href="<?= $baseUrl ?>secretariat_v2/secretariat/tree-relation?id=<?= $office_main->id ?>" class="icon-btn p-a-05 login-as-user-btn font-green-jungle">
                                    <i class="fa fa-tree icon"></i>
                                    <div class="title"> لیست درختی </div>
                                </a>
                            <?php endif; ?>

                            <?php if(Yii::$app->user->can('SecretariatDeleteLetterPerm')): ?>
                                <a href="<?= $baseUrl ?>/secretariat_v2/secretariat/list-letters?id=<?= $office_main->id ?>&status=delete" class=" icon-btn p-a-05 login-as-user-btn font-red critical-delete" data-title="حذف نامه" data-message="آیا از انجام عملیات حذف اطمینان دارید؟ در صورت انجام عملیات حذف، امکان بازیابی وجود ندارد.">
                                    <i class="fa fa-trash-o icon"></i>
                                    <div class="title"> حذف </div>
                                </a>
                            <?php endif; ?>

                            <?php /*if(Yii::$app->user->can('SecretariatRenderlastLetterPerm') || Yii::$app->user->can('SecretariatArchiveLetterPerm')): */?>
                                <a href="#" class="icon-btn p-a-05 login-as-user-btn font-yellow-gold" data-toggle="modal" data-target="#temp-connect-modal">
                                    <i class="fa fa-exchange" ></i>
                                    <div> تعیین وضعیت </div>
                                </a>
                            <?php/* endif; */?>

                            <?php /*if(Yii::$app->user->can('SecretariatV2ChangeLetterCategoryPerm')): */?>
                                <a href="<?= $baseUrl ?>secretariat_v2/secretariat/change-letter-category?office_id=<?= $office_main->id ?>" class="icon-btn p-a-05 login-as-user-btn font-blue">
                                    <i class="fa fa-exchange icon"></i>
                                    <div class="title"> تغییر دسته بندی </div>
                                </a>
                            <?php/* endif; */?>
                        </div>
                    </div>


                    <?php if ($office_main->officeType->name == 'خروجی'): ?>
                        <?php if ($office_main->officeOutputs->content == null): ?>
                            <hr>
                            <h4 class="text-info"><i class="fa fa-image"></i> تصویر نامه</h4>
                            <?= $this->render('@app/views/global/Carousel.php', ['files' => $office_main->officePhotos]); ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <hr>
                    <h4 class="text-info"><i class="fa fa-paperclip"></i> پیوست نامه</h4>
                    <?= $this->render('@app/views/global/Carousel.php', ['files' => $office_main->officeAttachments]); ?>

                    <?php if ($office_main->officePhotos == null): ?>
                        <hr>
                        <h4 class="text-info"><i class="fa fa-file-o"></i> متن نامه</h4>
                        <div class="row p-a-1" style="">
                            <div class="col-xs-12 download">
                                <?php if ($office_main->officeType->name == 'خروجی'): ?>
                                    <?php if ($office_main->officeOutputs->content != null): ?>

                                        <?=  $office_main->officeOutputs->content ?>

                                        <?php if ($office_main->officeOutputs->is_signed  == 0): ?>
                                            <div class="col-xs-12 col-md-12 text-center pt-2">
                                                <a href="<?= $baseUrl ?>secretariat_v2/secretariat/confirmation-letter?id=<?= $office_main->id ?>" class="btn btn-md m-b-1 btn-primary critical-delete" data-title="تایید نامه" data-message="آیا از تایید نامه اطمینان دارید؟  ">
                                                    <i class="fa fa-check"></i>
                                                    </span class="h4">تایید نامه </span>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="col-xs-12 col-md-12 text-center pt-2">
                                                <a href="<?= $baseUrl ?>secretariat_v2/secretariat/download-letter?id=<?= $office_main->id ?>" class="btn btn-md m-b-1 btn-primary">
                                                    <i class="fa fa-download"></i>
                                                    </span class="h4"> دریافت نامه </span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <div class="text-danger">نامه فاقد متن است.</div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>
                    <h4 class="text-info"><i class="fa fa-reply-all"></i> ارجاعات</h4>
                    <div class="row " style="">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-sm">
                                    <thead class="c3 fc0">
                                    <tr>
                                        <th style="width:40px">ردیف</th>
                                        <th>نام گیرنده</th>
                                        <th>ارجاع دهنده</th>
                                        <th>پاراف</th>
                                        <th style="width:150px">تاریخ ثبت</th>
                                    </tr>
                                    </thead>
                                    <tbody id="usage-reports-list">
                                    <?php if ($assigns['assign_tree'] != null): ?>
                                        <?php if ($assigns['assign_tree']['assigns'] != null): ?>
                                            <tr><td colspan="5"><h5 class="text-info">مسیر ارجاع</h5></td></tr>
                                            <?php foreach ($assign_tree['assigns'] as $i => $assign): ?>
                                                <tr>
                                                    <td><?= $i+1 ?></td>
                                                    <td><?= $assign->user->profile->name .' '. $assign->user->profile->family ?></td>
                                                    <td>
                                                        <?= $assign->parent != null ? $assign->parent->profile->name .' '. $assign->parent->profile->family : "<span class='text-danger'>ندارد</span>";
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= $assign->paraph != null ? $assign->paraph : "<span class='text-danger'>ندارد</span>";
                                                        ?>
                                                    </td>
                                                    <td><?= $assign->created_at ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif;?>
                                        <?php if ($assigns['assign_tree']['childs'] != null): ?>
                                            <tr><td colspan="5"><h5 class="text-info">ارجاعات انجام شده</h5></td></tr>
                                            <?php foreach ($assigns['assign_tree']['childs'] as $z => $childs): ?>
                                                <tr>
                                                    <td><?= $z+1 ?></td>
                                                    <td><?= $childs->user->profile->name .' '. $childs->user->profile->family ?></td>
                                                    <td>
                                                        <?= $childs->parent != null ? $childs->parent->profile->name .' '. $childs->parent->profile->family : "<span class='text-danger'>ندارد</span>";
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= $childs->paraph != null ? $childs->paraph : "<span class='text-danger'>ندارد</span>";
                                                        ?>
                                                    </td>
                                                    <td><?= $childs->created_at ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif;?>
                                    <?php else: ?>
                                        <?= '<tr><td colspan="5" class="text-danger">نامه فاقد ارجاع به شما است.</td></tr>'; ?>
                                    <?php endif;?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="col-xs-12">
                            <?php if (Yii::$app->user->can('SecretariatViewAssignsLogPerm')): ?>
                                <h5 class="text-muted"> لاگ  ارجاعات</h5>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover table-sm">
                                        <thead class="c3 fc0">
                                        <tr>
                                            <th style="width:40px">ردیف</th>
                                            <th style="width:200px">نام گیرنده</th>
                                            <th style="width:200px">ارجاع دهنده</th>
                                            <th>پاراف</th>
                                            <th style="width:150px">تاریخ ثبت</th>
                                        </tr>
                                        </thead>
                                        <tbody id="usage-reports-list">
                                        <?php if ($assigns['assign_logs'] != null): ?>
                                            <?php foreach ($assigns['assign_logs'] as $j => $assign_log): ?>
                                                <tr>
                                                    <td><?= $j+1 ?></td>
                                                    <td><?= $assign_log->user->profile->name .' '. $assign_log->user->profile->family ?></td>
                                                    <td>
                                                        <?= $assign_log->parent != null ? $assign_log->parent->profile->name .' '. $assign_log->parent->profile->family : "<span class='text-danger'>ندارد</span>";
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= $assign_log->paraph != null ? $assign_log->paraph : "<span class='text-danger'>ندارد</span>";
                                                        ?>
                                                    </td>
                                                    <td><?= $assign_log->created_at ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?= '<tr><td colspan="5">نامه فاقد ارجاع میباشد.</td></tr>'; ?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="temp-connect-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title bold">تعیین وضعیت</h4>
            </div>
            <div class="modal-body center-block" style="overflow: auto">
                <p>لطفا وضعیت نامه را تعیین کنید.</p>
                <div class="row col-md-offset-0">
                    <?php
                        $convert_letter = null;
                        $parent_letter = null;
                        ?>
                    <?php if ($convert_letter == null && $parent_letter == null): ?>
                        <?php if(Yii::$app->user->can('SecretariatRenderlastLetterPerm')): ?>
                            <div class="col-sm-6">
                                <a href="<?= $baseUrl ?>secretariat_v2/secretariat/render-last-letter?id=<?= $office_main->id ?>" class="btn btn-block red m-b-1">
                                    <i class="fa fa-exchange"></i>
                                    تبدیل نامه به خروجی
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(Yii::$app->user->can('SecretariatArchiveLetterPerm')): ?>
                        <div class="col-sm-6">
                            <a href="<?= $baseUrl ?>secretariat_v2/secretariat/archive-letter?id=<?= $office_main->id ?>" class="btn btn-block green m-b-1">
                                <i class="fa fa-exchange"></i>
                                بایگانی نامه
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
