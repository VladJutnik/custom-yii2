<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Авторизация в программном средстве';
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Vector CSS -->
    <link href="assets-custom/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
    <!--plugins-->
    <link href="assets-custom/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
    <link href="assets-custom/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"/>
    <link href="assets-custom/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
    <!-- loader-->
    <link href="assets-custom/css/pace.min.css" rel="stylesheet"/>
    <script src="assets-custom/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets-custom/css/bootstrap.min.css"/>
    <!-- Icons CSS -->
    <link rel="stylesheet" href="assets-custom/css/icons.css"/>
    <!-- App CSS -->
    <link rel="stylesheet" href="assets-custom/css/app.css"/>
    <link rel="stylesheet" href="assets-custom/css/dark-sidebar.css"/>
    <link rel="stylesheet" href="assets-custom/css/dark-theme.css"/>
</head>

<body>
<div class="container">
    <div class="card radius-15 mt-5">
        <div class="card-body p-5">
            <div class="card-title text-center"><i class='bx bxs-user-circle text-primary font-60'></i>
                <h3 class="mb-5 mt-3 text-primary">Аторизация в програмном средстве</h3>
            </div>
            <hr/>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="form-group">
                <label>Логин (Email ответственного лица)</label>
                <!--<input type="text" class="form-control form-control-lg radius-30" placeholder="Enter your Username" />-->
                <?= $form->field($model, 'login')->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg radius-30'])->label(false) ?>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <!-- <input type="password" class="form-control form-control-lg radius-30" placeholder="Enter your Password" />-->
                <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-lg radius-30'])->label(false) ?>
            </div>
            <div class="form-row align-items-center mt-3">
                <div class="form-group col-md-6">
                    <!-- <div class="custom-control custom-checkbox">
                         <input type="checkbox" class="custom-control-input" id="customCheck3">
                         <label class="custom-control-label" for="customCheck3">Remember Me</label>
                     </div>-->
                </div>
                <div class="form-group col-md-6 text-right"><a href="#" class="btn main-button-2-hover-orange"
                                                               data-target="#changePassword" data-toggle="modal">Восстановить
                        пароль</a></div>
                <!-- Button trigger modal -->
            </div>
            <?= Html::submitButton('<i class=\'bx bx-lock-alt\'></i> Войти в программу', ['class' => 'btn btn-primary px-5 radius-30 btn-block', 'name' => 'login-button']) ?>
            <?php ActiveForm::end(); ?>
            <hr/>
            <?= Html::submitButton('<i class=\'bx bx-bookmark mr-1\'></i> Регистрация', ['class' => 'btn btn-info m-1 px-5 radius-30 btn-block', 'name' => 'login-button']) ?>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-30">
            <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">	<span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-5">
                <h3 class="modal-title">Восстановление доступа</h3>

                <?php if (!Yii::$app->session->hasFlash('changePassword'))
                { ?>
                    <?php $change_form = ActiveForm::begin([]); ?>
                    <div class="form-group">
                        <label>Ваш Email</label>
                        <?= $change_form->field($change, 'email')->textInput(['class' => 'form-control form-control-lg radius-30'])->label(false) ?>
                    </div>

                    <div class="form-group">
                        <div class="text-center">
                            <?= Html::submitButton('Выслать пароль на почту', ['class' => 'btn btn-outline-warning m-1 px-5 radius-30']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                <?php }
                else
                { ?>
                    <div class="modal-body">
                        <div class="alert alert-success" role="alert">
                            <h4>Сообщение с паролем отправлено на Ваш Email</h4>
                            <p>Зайдите на почту и скопируйте Ваш новый пароль</p>
                        </div>
                    </div>
                <?php } ?>

                <?php if (Yii::$app->session->hasFlash('changeErrorPassword'))
                { ?>
                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert">
                            <h4>Пользователь с указанным Email не зарегистрирован</h4>
                            <p>Перейдите в раздел регистрациии</p>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>


<?php if (Yii::$app->session->hasFlash('changePassword'))
{ ?>
    <? $script = <<< JS
    $('#changePassword').modal('show');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>


<?php if (Yii::$app->session->hasFlash('changeErrorPassword'))
{ ?>
    <? $script = <<< JS
    $('#changePassword').modal('show');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>
<script src="assets-custom/js/jquery.min.js"></script>
<script src="assets-custom/js/popper.min.js"></script>
<script src="assets-custom/js/bootstrap.min.js"></script>
<!--plugins-->
<script src="assets-custom/plugins/simplebar/js/simplebar.min.js"></script>
<script src="assets-custom/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="assets-custom/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<!-- App JS -->
<script src="assets-custom/js/app.js"></script>
</body>

</html>
