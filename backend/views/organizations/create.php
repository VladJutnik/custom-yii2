<?php

use common\models\FederalDistrict;
use common\models\Region;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Organization */

$this->title = 'Добавление организации';

//$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container">
    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
            </div>
            <hr/>
            <div class="directory-activity-form">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>