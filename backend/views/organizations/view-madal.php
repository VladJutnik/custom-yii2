<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

$two_column = ['options' => ['class' => 'row mt-3'], 'labelOptions' => ['class' => 'col-6 col-form-label font-weight-bold']];
?>

<div class="user-create">
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <?php $form = ActiveForm::begin();?>
            <?/*= $form->field($model, 'start_date_medical_examination', $two_column)->textInput(['class' => 'form-control col-6'])->label('с');*/?><!--
            <?/*= $form->field($model, 'end_date_medical_examination', $two_column)->textInput(['class' => 'form-control col-6', 'value' => date('d.m.Y')])->label('по');*/?>
            --><?/*= $form->field($model, 'status_print', ['options' => ['class' => 'mt-3 font-weight-bold']])->checkbox(['style' => 'transform:scale(1.5);', 'labelOptions' => ['style' => 'font-size:18px;']]);*/?>

            <div class="form-group">
                <?= Html::submitButton('Показать', ['class' => 'btn mt-3 btn-success form-control']) ?>
            </div>

            <?php ActiveForm::end();?>

        </div>
    </div>
</div>