<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use common\models\FederalDistrict;
use common\models\Region;
use common\models\TypeOrganization;

$this->title = 'Добавление пользователя';
$this->params['breadcrumbs'][] = $this->title;

$subjects1 = [];
$items_heredity = [
    'доктор' => 'Доктор',
    'медсестра' => 'Медсестра/медбрат',
    'глврач' => 'Гл врач / Зам ГЛ. врача',
    'бухгал' => 'Бухгалер',
    'админ' => 'Админ',
    'обучающийся' => 'Обучающийся',
];
$items_heredity2 = [
    '' => '',
    'профпатолог' => 'Профпатолог',
    'терапевт' => 'Терапевт',
    'невролог' => 'Невролог',
    'офтальмолог' => 'Офтальмолог',
    'отоларинголог' => 'Отоларинголог',
    'нарколог' => 'Нарколог',
    'психиатр' => 'Психиатр',
    'хирург' => 'Хирург',
    'стоматолог' => 'Стоматолог',
    'дерматовенеролог' => 'Дерматовенеролог',
    'гиниколог' => 'Гинеколог',
    'физио' => 'Врач ФД',
];
//print_r($model)
?>

   <div class="site-signup m-4">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Пожалуйста, заполните следующие поля:</p>

        <div class="row">
            <div class="col-12">
                <?php /*$form = ActiveForm::begin(['id' => 'form-signup']); */?>
                <?php $form = ActiveForm::begin();?>

                <?php
                    $two_column = ['options' => ['class' => 'row mt-3'], 'labelOptions' => ['class' => 'col-3 col-form-label font-weight-bold']];
                ?>

                <?= $form->field($model, 'name', $two_column)->textInput(['autocomplete' => 'off', 'class' => 'form-control col-4']) ?>

                <?= $form->field($model, 'login', $two_column)->textInput(['autocomplete' => 'off', 'class' => 'form-control col-4']) ?>

                <?= $form->field($model, 'type', $two_column)->dropDownList($items_heredity, ['autocomplete' => 'off', 'class' => 'form-control col-4']) ?>

                <?= $form->field($model, 'post', $two_column)->dropDownList($items_heredity2, ['autocomplete' => 'off', 'class' => 'form-control col-4']) ?>

                <?= $form->field($model, 'email', $two_column)->textInput(['autocomplete' => 'off', 'class' => 'form-control col-4']) ?>

                <?= $form->field($model, 'password', $two_column)->passwordInput(['autocomplete' => 'off', 'class' => 'form-control col-4']) ?>
               <div class="row">
                   <div class="col-3"></div>
                   <div class="col-4 mt-2"><label><input type="checkbox" id="passwordd" class="password-checkbox"> Показать пароль</label></div>

               </div>

                <div class="form-group">
                    <?= Html::submitButton('Зарегестрировать', ['class' => 'btn main-button-3 col-7 mt-3', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php
$js = <<< JS


    $('#passwordd').on('click', function(){
        /*console.log(123123)*/
        if ($(this).is(':checked')){
            $('#signupform-password').attr('type', 'text');
        } else {
            $('#signupform-password').attr('type', 'password');
        }
    }); 


	//document.getElementById("btn").disabled = false;
    $('#signupform-region_id').attr('disabled', 'true');
    $('#signupform-municipality').attr('disabled', 'true');
     
    /*  var field = $('#signupform-type_org');
    field.on('change', function () {
           if (field.val() !== "1" ) {
               $('.field-signupform-type_lager_id').hide();
               $('.field-signupform-type_lager_id').val('0');
           }
           else{
              $('.field-signupform-type_lager_id').show();
           }
    });
    field.trigger('change'); */
    
    var field = $('#signupform-type');
    field.on('change', function () {
           if (field.val() !== "доктор" ) {
               $('.field-signupform-post').hide();
           }
           else{
              $('.field-signupform-post').show();
           }
    });
    field.trigger('change');

    
JS;
$this->registerJs($js, \yii\web\View::POS_READY);