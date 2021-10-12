<?php

/* @var $this \yii\web\View */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $content string */

/*GlyphiconAsset::register($this);*/

AppAsset::register($this);

$user_settings = \common\models\UserSettings::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
//свернуть показать боковое меню $user_settings->pin_slider
//свернуть показать боковое меню $user_settings->topic
//свернуть показать боковое меню $user_settings->dark_slider
//свернуть показать боковое меню $user_settings->dark_icons
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="<?=$user_settings->topic?> <?=$user_settings->dark_slider?> <?=$user_settings->dark_icons?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<style>
    .btn{
        text-transform: none !important;
    }
</style>
<div class="wrapper <?= $user_settings->pin_slider?> ">
<?if (!Yii::$app->user->isGuest) {?>
    <!--sidebar-wrapper-->
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div class="">
                <img src="/images/logo.jpg" class="logo-icon-2" alt="" />
            </div>
            <div>
                <h6 class="logo-text">Примерная разработка проекта</h6>
            </div>
            <a href="javascript:;" class="toggle-btn ml-auto"> <i class="bx bx-menu"></i>
            </a>
        </div>
        <!--боковое меню-->
        <ul class="metismenu" id="menu">
            <li><?= Html::a('<div class="parent-icon icon-color-1"><i class="bx bx-home-alt"></i></div> <div class="menu-title">Главная</div>', ['/site/index']) ?></li>
            <li><?= Html::a('<div class="parent-icon icon-color-1"><i class="lni lni-database"></i></div> <div class="menu-title">Список организаций</div>', ['/organizations/index']) ?></li>
            <li><?= Html::a('<div class="parent-icon icon-color-1"><i class="lni lni-users"></i></div> <div class="menu-title">Пользователи</div>', ['/users/index']) ?></li>

            <!--<li class="menu-label text-center">СГХ</li>
            <li>
                <a href="emailbox.html">
                    <div class="parent-icon icon-color-2"><i class="lni lni-list"></i>
                    </div>
                    <div class="menu-title">Список пациентов</div>
                </a>
            </li>
            <li>
                <a href="emailbox.html">
                    <div class="parent-icon icon-color-2"><i class="lni lni-list"></i>
                    </div>
                    <div class="menu-title">Проф маршрут пациентов</div>
                </a>
            </li>
            <li class="menu-label text-center">Предприятия</li>
            <li>
                <a href="emailbox.html">
                    <div class="parent-icon icon-color-2"><i class="lni lni-list"></i>
                    </div>
                    <div class="menu-title">Список предприятий</div>
                </a>
            </li>
            <li class="menu-label text-center">Справочники</li>-->
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon icon-color-10"><i class="bx bx-spa"></i>
                    </div>
                    <div class="menu-title">Справочники</div>
                </a>
                <ul>
                    <li><?= Html::a('<i class="bx bx-right-arrow-alt"> </i>Справочник регионов', ['/regions/index']) ?></li>
                    <li><?= Html::a('<i class="bx bx-right-arrow-alt"> </i>Справочник муниципальных образований', ['/municipality/index']) ?></li>
                </ul>
            </li>
        </ul>
    </div>
    <header style="position: static !important;" class="top-header">
        <nav  class="navbar navbar-expand">

            <div class="right-topbar ml-auto">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown dropdown-lg">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="javascript:;" data-toggle="dropdown">	<span class="msg-count">1</span>
                            <i class="bx bx-comment-detail vertical-align-middle"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="javascript:;">
                                <div class="msg-header">
                                    <h6 class="msg-header-title">1 новое сообщение</h6>
                                    <p class="msg-header-subtitle">Чат</p>
                                </div>
                            </a>
                            <div class="header-message-list">
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="media align-items-center">
                                        <div class="user-online">
                                            <img src="https://via.placeholder.com/110x110" class="msg-avatar" alt="user avatar">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="msg-name">Daisy Anderson <span class="msg-time float-right">5 sec
													ago</span></h6>
                                            <p class="msg-info">The standard chunk of lorem</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <a href="javascript:;">
                                <div class="text-center msg-footer">View All Messages</div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-user-profile">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-toggle="dropdown">
                            <div class="media user-box align-items-center">
                                <div class="media-body user-info">
                                    <p class="user-name mb-0">Пользователь:</p>
                                    <p class="designattion mb-0"><?= Yii::$app->user->identity->name ?></p>
                                </div>
                                <img src="/image_user/<?=Yii::$app->user->identity->photo?>" class="user-img" alt="Ваш автар">
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <?= Html::a('<i class="bx bx-user"></i><span>Профиль</span>', ['/user-settings/profile'], ['class' => 'dropdown-item']) ?>
                            <?= Html::a('<i class="bx bx-cog"></i><span>Настройки</span>', ['/user-settings/settings'], ['class' => 'dropdown-item']) ?>
<!--
                            --><?/*= Html::a('Ссылка', ['/user-settings/settings'], [
                                'data' => [
                                    'method' => 'post',
                                    'params' => [
                                        'id' => Yii::$app->user->identity->id,
                                        'id2' => Yii::$app->user->identity->id,
                                        'param2' => 'value2',
                                    ],
                                ],
                            ]);*/?>

                        <div class="dropdown-divider mb-0"></div>
                            <?= Html::a('<i class="bx bx-power-off"></i><span>Выход</span> ', ['/site/logout'], ['class' => 'dropdown-item']) ?>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
<?}?>
    <!--ОСНОВНОЙ КОНТЕНТ-->
        <div class="page-content-wrapper">
            <div style="margin-top: -80px" class="page-content">
                <div class="container mb-3 mt-2">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('error'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?= $content ?>
            </div>
        </div>
    <!--start overlay-->
    <div class="overlay toggle-btn-mobile"></div>
    <!--end overlay-->
    <!--Кнопка на верх--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
</div>

<script>
    new PerfectScrollbar('.dashboard-social-list');
    new PerfectScrollbar('.dashboard-top-countries');
</script>
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>