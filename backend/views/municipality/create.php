<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Municipality */

$this->title = 'Добавление муниципального образования';
$this->params['breadcrumbs'][] = ['label' => 'Municipalities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="municipality-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
