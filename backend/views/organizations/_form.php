<?php

use common\models\FederalDistrict;
use common\models\Region;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Organization */
/* @var $form yii\widgets\ActiveForm */

$district_null = array('' => 'Выберите федеральный округ ...');
$districs = FederalDistrict::find()->all();
$district_items = ArrayHelper::map($districs, 'id', 'name');
$district_items = ArrayHelper::merge($district_null, $district_items);

$region_null = array('' => 'Выберите регион ...');
$regions = Region::find()->where(['district_id' => '5'])->all();
$region_items = ArrayHelper::map($regions, 'id', 'name');
$region_items = ArrayHelper::merge($region_null, $region_items);

$municipality_null = array('' => 'Выберите муниципальное образование...');
$municipalities = \common\models\Municipality::find()->where(['region_id' => '48'])->all();
$municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
?>


<div class="directory-activity-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title', Yii::$app->myComponent->twoColumnName())->textInput(Yii::$app->myComponent->twoColumnInput()) ?>
    <?= $form->field($model, 'federal_district_id', Yii::$app->myComponent->twoColumnName())->dropDownList($district_items, [
        'class' => 'form-control col-4',
        //'options' => [$post['federal_district_id'] => ['Selected' => true]],
        'onchange' => '
            $.get("../organizations/search?id="+$(this).val(), function(data){
                $("select#organization-region_id").html(data);
                document.getElementById("organizations-region_id").disabled = false;
            });
            $.get("../organizations/search-municipality?id=0", function(data){
                $("select#organization-municipality_id").html(data);
                //document.getElementById("organizations-municipality_id").disabled = false;
            });'
    ]); ?>
    <?= $form->field($model, 'region_id', Yii::$app->myComponent->twoColumnName())->dropDownList($region_items, [
        'class' => 'form-control col-4',
        'onchange' => '
            $.get("../organizations/search-municipality?id="+$(this).val(), function(data){
                $("select#organization-municipality_id").html(data);
                //document.getElementById("organizations-municipality_id").disabled = false;
            });'
    ]); ?>
    <?= $form->field($model, 'municipality_id', Yii::$app->myComponent->twoColumnName())->dropDownList($municipality_items, ['prompt' => 'Выберите муниципальное образование...',  'class' => 'form-control col-4']); ?>
    <?= $form->field($model, 'address', Yii::$app->myComponent->twoColumnName())->textInput(Yii::$app->myComponent->twoColumnInput()) ?>
    <?= $form->field($model, 'phone', Yii::$app->myComponent->twoColumnName())->textInput(Yii::$app->myComponent->twoColumnInput()) ?>
    <?= $form->field($model, 'email', Yii::$app->myComponent->twoColumnName())->textInput(Yii::$app->myComponent->twoColumnInput()) ?>
    <?= $form->field($model, 'inn', Yii::$app->myComponent->twoColumnName())->textInput(Yii::$app->myComponent->twoColumnInput()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
