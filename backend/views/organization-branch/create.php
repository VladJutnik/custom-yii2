<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrganizationBranch */

$this->title = 'Create Organization Branch';
$this->params['breadcrumbs'][] = ['label' => 'Organization Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-branch-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
