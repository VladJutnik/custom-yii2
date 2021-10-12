<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\bootstrap4\Html;

$this->title = $name;
?>
<div class="site-error">
    <div class="error-404 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="card radius-15 shadow-none bg-transparent">
                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="card-body">
                            <h1><?= Html::encode($this->title) ?></h1>
                            <h2 class="font-weight-bold display-4">Ошибка</h2>
                            <div class="alert alert-danger">
                                <?= nl2br(Html::encode($message)) ?>
                            </div>
                            <p>
                                Вышеуказанная ошибка произошла, когда веб-сервер обрабатывал ваш запрос.
                            </p>
                            <p>
                                Пожалуйста, свяжитесь с нами, если вы считаете, что это ошибка сервера. Спасибо.
                            </p>
                        </div>
                    </div>
                    <!--<div class="col-lg-6">
                        <img src="assets/images/errors-images/404-error.png" class="card-img" alt="">
                    </div>-->
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
</div>
