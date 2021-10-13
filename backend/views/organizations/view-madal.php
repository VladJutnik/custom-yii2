<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

$two_column = ['options' => ['class' => 'row mt-3'], 'labelOptions' => ['class' => 'col-6 col-form-label font-weight-bold']];
?>

<div class="user-create">
    <div class="row justify-content-center mt-3">
        <div class="col-md-12 text-center">
            <?= Html::a('<span> Экспорт в Exceel</span>', ['export-excel?id=' . $id], [
                'class' => 'btn btn-success m-1 radius-30 px-5',
                'title' => Yii::t('yii', 'Напечатать'),
            ]);?>
            <br>
            <?= Html::a('<span> Экспорт в PDF</span>', ['exportk?id=' . $id], [
                'class' => 'btn btn-danger m-1 radius-30 px-5',
                'title' => Yii::t('yii', 'Напечатать'),
            ]);?>
            <br>
            <?= Html::a('<span> Экспорт в Word</span>', ['exportk?id=' . $id], [
                'class' => 'btn btn-info m-1 radius-30 px-5',
                'title' => Yii::t('yii', 'Напечатать'),
            ]);?>
        </div>
    </div>
</div>