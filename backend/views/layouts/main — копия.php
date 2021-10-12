<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;

/*use yii\bootstrap4\Html;*/

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Alert;
use common\models\Organization;
use common\models\SelectOrgForm;
use yii\bootstrap4\ActiveForm;
use xtetis\bootstrap4glyphicons\assets\GlyphiconAsset;
use yii\helpers\Html;

GlyphiconAsset::register($this);

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <!--<meta charset="UTF-8">-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <!--    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/loadingio/ldLoader@v1.0.0/dist/ldld.min.css" >-->
        <!--    <script src="https://cdn.jsdelivr.net/gh/loadingio/ldLoader@v1.0.0/dist/ldld.min.js"></script>-->

        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">

        <?if (!Yii::$app->user->isGuest) {?>
            <nav class="navbar navbar-expand-lg main-color">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown white-li" style="display: block !important; position: relative;">
                        <a class="nav-link dropdown-toggle  white-a mr-1 mt-2 ml-4" href="#"
                           style="display: block !important; font-size: 1.2rem !important; font-family: serif !important; position: relative;" id="navbarDropdown"
                           role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Отчеты
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?= Html::a('Cписок работников для прохождения медицинского осмотра', ['/list-patients/report'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            <?= Html::a('Сводные данные по организациям', ['/organizations/report-svod'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            <?= Html::a('Сводные данные по организациям по анализам', ['/organizations/report-svod-analiziz'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            <?/*= Html::a('Сводные данные по пунктам приказа по организациям', ['/organizations/report-punkt'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) */?><!--
                            --><?= Html::a('Данные по проведенным осмотрам', ['/site/report-osm'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            <?= Html::a('Формирование списков по организациям', ['/list-patients/report-listing'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                        </div>
                    </li>

                    <?= Html::a('Личный кабинет', ['/site/personal-account'], ['style' => 'font-size: 1.2rem !important; font-family: serif !important;', 'class' => 'main-button-2-hover-orange p-2']) ?>

                    <li class="nav-item dropdown white-li">
                        <lable>
                            <a class="nav-link dropdown-toggle mt-2 mr-1 ml-1 white-a" href="#"
                               style="font-size: 1.2rem !important; font-family: serif !important;" id="navbarDropdown"
                               role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Работа с организациями
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?= Html::a('Список организаций (заключительный АКТ)', ['/organizations/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Список сотрудников по организациям', ['/list-patients/index?organization=0'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Список сотрудников по организациям расширенный', ['/list-patients/index-patient'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Полнота заполнения', ['/list-patients/index-short?organization=0'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Загрузка данных по пациентам', ['/loading-patient/create'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            </div>
                        </lable>
                    </li>

                    <li class="nav-item dropdown white-li">
                        <a class="dropdown-toggle  mt-2 mr-1 ml-1 white-a"
                           style="font-size: 1.2rem !important; font-family: serif !important;" type="button"
                           data-toggle="dropdown" href="#">Справочная информация<span
                                    class="sr-only">(current)</span></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item submenu"
                               style="font-size: 1.1rem !important; font-family: serif !important;" href="#">Справочник
                                общих данных</a>
                            <div class="dropdown-menu">
                                <?= Html::a('Обследования основные', ['/research-basic/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            </div>
                            <a class="dropdown-item submenu"
                               style="font-size: 1.1rem !important; font-family: serif !important;" href="#">Приказ 302н</a>
                            <div class="dropdown-menu">
                                <?= Html::a('Врачи-специалисты', ['/doctors/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Обследования', ['/research/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Типы обследований', ['/type-research/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Факторы', ['/factors/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Противопоказания', ['/contraindications/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Типы вредных факторов', ['/type-factors/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            </div>
                            <a class="dropdown-item submenu"
                               style="font-size: 1.1rem !important; font-family: serif !important;" href="#">Справочник приказа 302н</a>
                            <div class="dropdown-menu">
                                <?= Html::a('Связь "Фактор - Противопоказания"', ['/factors-contraindications/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Связь "Фактор - Участие врачей-специалистов"', ['/factors-doctors/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Связь "Фактор - Обследования"', ['/factors-research/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            </div>
                            <a class="dropdown-item submenu"
                               style="font-size: 1.1rem !important; font-family: serif !important;" href="#">Справочник стоимости услуг</a>
                            <div class="dropdown-menu">
                                <?= Html::a('Тип цены', ['/price-type/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Цена обследований', ['/price-research/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Цена основных обследований', ['/price-research-basic/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Цена консультаций врачей', ['/doctors-basic/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            </div>
                            <a class="dropdown-item submenu"
                               style="font-size: 1.1rem !important; font-family: serif !important;" href="#">Приказ 29н</a>
                            <div class="dropdown-menu">
                                <?= Html::a('Врачи-специалисты', ['/doctors/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Обследования', ['/research/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Типы обследований', ['/type-research/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Факторы', ['/kind-work2/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Противопоказания', ['/contraindications2/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Типы вредных факторов', ['/type-factors/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Проверка приказа', ['/kind-work2/report'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            </div>
                            <a class="dropdown-item submenu"
                               style="font-size: 1.1rem !important; font-family: serif !important;" href="#">Справочник приказа 29н</a>
                            <div class="dropdown-menu">
                                <?= Html::a('Связь "Фактор - Противопоказания"', ['/kind-work-contraindications2/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Связь "Фактор - Участие врачей-специалистов"', ['/kind-work-doctors2/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Связь "Фактор - Обследования"', ['/kind-work-research2/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            </div>
                        </div>
                    </li>

                    <?if(Yii::$app->user->can('admin')||Yii::$app->user->can('bookkeeper')||Yii::$app->user->can('gldoctor')){?>
                        <li class="nav-item dropdown white-li">
                            <lable>
                                <a class="nav-link dropdown-toggle white-a main-button-2-hover-orange p-2" href="#"
                                   style="font-size: 1.2rem !important; font-family: serif !important;" id="navbarDropdown"
                                   role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Расчеты стоимости
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <?= Html::a('Расчеты стоимости', ['/list-patients/report-listing-cost'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                    <?= Html::a('Общие расчеты стоимости', ['/organizations/index2'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                    <?= Html::a('Индивидуальный расчет', ['/list-patients/ind-cost'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                    </div>
                            </lable>
                        </li>
                    <?}?>
                    <?if(Yii::$app->user->can('admin') || Yii::$app->user->can('gldoctor') || Yii::$app->user->can('nurse') || Yii::$app->user->can('doctor')){?>
                        <?= Html::a('Результаты анализов', ['/organizations/index3'], ['style' => 'font-size: 1.2rem !important; font-family: serif !important;', 'class' => 'main-button-2-hover-orange p-2']) ?>
                    <?}?>
                    <?if(Yii::$app->user->can('admin')){?>
                        <li class="nav-item dropdown white-li">
                            <a class="nav-link dropdown-toggle  mt-2 mr-1 ml-1  white-a" href="#"
                               style="font-size: 1.2rem !important; font-family: serif !important;" id="navbarDropdown"
                               role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Управление
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?= Html::a('Настройки программы', ['/site/setings'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Пользователи', ['/users/index'], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                                <?= Html::a('Чат', ['/messages/create?id=' . Yii::$app->user->id], ['style' => 'font-size: 1.1rem !important; font-family: serif !important;', 'class' => 'dropdown-item']) ?>
                            </div>
                        </li>
                    <?}?>
                </ul>

                <form class="form-inline my-2 my-lg-0 text-center ">
                    <div style="color: #fff !important; font-size: 1.0rem !important; font-family: serif !important;">Пользователь:
                            (<?= Yii::$app->user->identity->name ?>)&nbsp;</div>
                    <?= Html::a('<span class="glyphicon glyphicon-log-in"></span> Выход', ['/site/logout'], ['style' => 'font-size: 0.9rem !important; font-family: serif !important;', 'class' => 'btn btn-sm btn-danger mr-2']) ?>
                </form>
            </div>
        </nav>
        <?}?>
        <div class="container-fluid mt-4">
            <div class="container mb-2 mt-2">
                <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <?php echo Yii::$app->session->getFlash('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <?php echo Yii::$app->session->getFlash('error'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <? /*= $logout */ ?>
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => '/'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <!--<footer class="footer main-color">
        <p class="text-light ml-3 font-weight-bold">Разработчик: <a href="http://niig.su"
                                                                    class="text-light font-weight-normal">ФБУН
                "Новосибирский НИИ гигиены" Роспотребнадзора</a></p>
    </footer>-->

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>