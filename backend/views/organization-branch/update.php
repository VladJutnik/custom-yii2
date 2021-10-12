<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrganizationBranch */

$this->title = 'Update Organization Branch: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Organization Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="organization-branch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
