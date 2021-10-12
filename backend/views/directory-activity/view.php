<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DirectoryActivity */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Directory Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mt-2']) ?>
            </div>
            <hr/>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'login_created',
                    'name',
                    'status_view',
                    'creat_at',
                ],
            ]) ?>
        </div>
    </div>
</div>

