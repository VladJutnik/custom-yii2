<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\PasswordChange */
$this->title = 'Смена пароля';
?>
    <div class="user-profile-page">
        <div class="card radius-15">
            <div class="card-body">
                <div class="tab-content mt-3">
                    <div class="card shadow-none border mb-0 radius-15">
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12 col-lg-5 border-right">
                                        <?= $this->render('menu') ?>
                                    </div>
                                    <div class="col-12 col-lg-7">
                                        <div class="card-title">
                                            <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
                                        </div>
                                        <hr/>
                                        <?php $form = ActiveForm::begin(['id' => 'change-password']); ?>

                                        <?= $form->field($model, 'password_old', Yii::$app->myComponent->twoColumnName())->passwordInput(['maxlength' => true, 'class' => 'form-control col-sm-12 col-md-12 col-lg-6 col-xl-6']) ?>

                                        <?= $form->field($model, 'password_new', Yii::$app->myComponent->twoColumnName())->passwordInput(['maxlength' => true, 'class' => 'form-control col-sm-12 col-md-12 col-lg-6 col-xl-6']) ?>

                                        <?= $form->field($model, 'password_repeat', Yii::$app->myComponent->twoColumnName())->passwordInput(['maxlength' => true, 'class' => 'form-control col-sm-12 col-md-12 col-lg-6 col-xl-6']) ?>

                                        <div class="form-group">
                                            <?= Html::submitButton('Сохранить', ['id' => 'clickButton', 'class' => 'btn btn-outline-primary mt-3 px-5 radius-30 btn-block', 'name' => 'change-password-button']) ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$js = <<< JS

/*
    const form  = document.getElementById('change-password');
    
    const button = document.getElementById('clickButton')
    button.addEventListener('submit', clickButtonExpectation)
    function clickButtonExpectation(event){
          console.log(1111)
          event.target.disabled = true
          event.target.innerHTML = '<span class="lni lni-spinner lni-spin"></span> Пожалуйста, подождите...'
    }
    const button = document.getElementById('clickButton')
    
    document.getElementById('clickButton').addEventListener('submit', clickButtonExpectation)
    /!*button.addEventListener('click', ()=>{
        console.log(button)
        button.innerText('<span ></span> Пожалуйста, подождите...');
    })*!/
    function clickButtonExpectation(event){
        event.target.disabled = true
        event.target.innerHTML = '<span class="lni lni-spinner lni-spin"></span> Пожалуйста, подождите...'
    }
	$('form').on('beforeSubmit', function(){
        var form = $(this);
        var submit = form.find(':submit');
        submit.html('<span class="fa fa-spin fa-spinner"></span> Пожалуйста, подождите...');
        submit.prop('disabled', true);
    });*/

JS;
$this->registerJs($js, View::POS_READY);