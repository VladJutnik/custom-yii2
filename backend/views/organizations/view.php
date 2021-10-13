<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Organization */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
                <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы действительно хотите удалить организацию?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <hr/>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'title',
                    'address',
                    'phone',
                    'email',
                    'inn',
                ],
            ]) ?>
        </div>
    </div>
</div>