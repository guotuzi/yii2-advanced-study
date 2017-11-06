<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Companies;
use backend\models\Branches;

/* @var $this yii\web\View */
/* @var $model backend\models\Departments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="departments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'companies_company_id')->dropDownList
    (    // company 的下拉框
        ArrayHelper::map(Companies::find()->all(), 'company_id', 'company_name'),
        // map() 作用应该是从 Branches::find()->all() 中， 按照 company_id 找出 company_name 值出来
        [
            'prompt' => 'Select Company',    //文本框中预先显示的文字
            // 上下联动下拉框
            'onchange' => '
                    $.post("index.php?r=branches/lists&id='.'"+$(this).val(), function( data) {
                       $( "select#departments-branches_branch_id").html( data );
                 });'
        ]); ?>


    <?= $form->field($model, 'branches_branch_id')->dropDownList
    (    // company 的下拉框
        ArrayHelper::map(Branches::find()->all(), 'branch_id', 'branch_name'),
        // map() 作用应该是从 Branches::find()->all() 中， 按照 branch_id 找出 branch_name 值出来
        [
            'prompt' => 'Select Branch',    //文本框中预先显示的文字
        ]); ?>




    <?= $form->field($model, 'department_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'department_status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => 'Status']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
