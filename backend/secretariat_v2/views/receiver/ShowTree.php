<?php
use yii\helpers\Url;
use app\assets\AppAsset;

$baseUrl = Url::home(true);

$this->title = 'نمایش افراد سازمان به صورت درختی';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);

?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="col-sm-8 col-sm-offset-2">
    <div class="portlet grey-silver box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i> <?= $this->title ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-xs-12">
                    <p class="p-a-1">نمایش افراد سازمان به صورت درختی</p>
                </div>
                <div class="col-xs-12">
                    <div id="tree_view" class="tree-demo"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">var resellers_data = <?php echo json_encode($organization) ?>;</script>
<?php
$resellerTreeview=<<<JS
$(function(){
    jQuery.ajax({
        url: baseUrl+"asset_admin/global/plugins/jstree/dist/jstree.min.js",
        dataType: "script",
        cache: true
    }).done(function() {

        $('#tree_view').jstree({
            "core" : {
                "themes" : {
                    "responsive": true
                },
                'data' :resellers_data  
                
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-user text-muted"
                },
                "file" : {
                    "icon" : "fa fa-user text-muted"
                },
            },
           
            "plugins": ["types"]
        });
        $('#tree_view').on('ready.jstree', function() {
            //$("#tree_view").jstree("open_all"); 
            $('#tree_view>ul>li>a>i').removeClass("fa-user text-muted").addClass("fa-bank text-success");
            $('#tree_view>ul>li>a').css({ fontSize: "16px", fontWeight: "bold" });
            $('#tree_view>ul>li').css({ paddingBottom: "6px" });
        });

        $("#tree_view").on("changed.jstree", function (e, data) {
            var id = data.selected[0];
            
            if (id.length < 11) {
                window.location = "#";
            }
        });
    });
});
JS;
$this->registerJS($resellerTreeview);
?>

