<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DirectoryActivity */

$this->title = 'Добавление вида деятельности';
$this->params['breadcrumbs'][] = ['label' => 'Справочник видов деятельности', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
            </div>
            <hr/>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
