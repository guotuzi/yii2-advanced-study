<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;    //使用Pjax 自动刷新， 这里没成，不知道是什么原因，暂时先放下了
use yii\bootstrap\Modal; //使用bootStrap 的 Pop up 弹出框效果
use yii\helpers\Url;     // Url 组件

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BranchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-index">

    <h1><?= Html::encode($this->title) ?></h1>

<!--    创建一个按钮，用于调modal的显示-->
    <p>
        <?= Html::button('Create Branches', [
            'value'=>Url::to('index.php?r=branches/create'),
            'class' => 'btn btn-success',
            'id'=>'modalButton'])
        ?>
    </p>

<!--Pop up 弹出层效果开始(还要在asset 中配置，js 文件在 web->js->main.js)
创建modal-->
    <?php
        Modal::begin([
            'header' => '<h4>Branches</h4>',
            'id' => 'modal',
            'size' => 'modal-lg',    //这是大号的意思，可以写小号（-sm）
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>
<!--Pop up 弹出层效果结束    -->



<!--    使用Pjax开始-->
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // 改变不同状态公司的背景颜色(这个用的是bootStrap 中的class属性)
        'rowOptions' => function($model) {
            if ($model->branch_status == 'inactive') {
                return ['class' => 'danger'];
            } else {
                return ['class' => 'success'];
            }
        },


        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 在此处给company_name加上一个搜索框
            [
                'attribute'=>'companies_company_id',      //搜索框的属性是 id,
                'value'=>'companiesCompany.company_name', //搜索框的值是 name
                //（来自于 model->Branches->getCompaniesCompany(), 是关联列表搜索到的结果)
            ],
            'branch_name',
            'branch_address',
            'branch_created_date',
            // 'branch_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
<!--    使用Pjax结束-->

</div>
