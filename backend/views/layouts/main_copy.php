<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Alert;
use common\models\Organization;
use common\models\SelectOrgForm;
use yii\bootstrap4\ActiveForm;
use xtetis\bootstrap4glyphicons\assets\GlyphiconAsset;
GlyphiconAsset::register($this);

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
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
    <?php
    NavBar::begin([
        //'brandLabel' => Yii::$app->name,
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-dark main-color navbar-expand-lg p-0',
        ],
    ]);
    $my_organization = Organization::findOne(Yii::$app->user->identity->organization_id);
    $model_user = new \common\models\User();
    if (Yii::$app->user->isGuest) {
        $menuItems[] = [
            'label' => 'Врач-специалист',
            'url' => ['/doctors/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 main-color-2 p-1'],
        ];
        $menuItems[] = [
            'label' => 'Обследования',
            'url' => ['/research/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 main-color-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Обследования Основные',
            'url' => ['/research-basic/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 main-color-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Тип обследования',
            'url' => ['/type-research/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Цена услуги',
            'url' => ['/price-research/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Цена основных услуг',
            'url' => ['/price-research-basic/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Цена консуцльтаций врачей',
            'url' => ['/doctors-basic/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];

        $menuItems[] = [
            'label' => 'Факторы',
            'url' => ['/factors/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 main-color-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Противопоказания',
            'url' => ['/contraindications/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 main-color-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Типы вредных факторов',
            'url' => ['/type-factors/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Связь "Фактор - Противопоказания"',
            'url' => ['/factors-contraindications/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Связь "Фактор - Участие врачей-специалистов"',
            'url' => ['/factors-doctors/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];
        $menuItems[] = [
            'label' => 'Связь "Фактор - Обследования"',
            'url' => ['/factors-research/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];

        $menuItems[] = [
            'label' => 'Организации',
            'url' => ['/organizations/index'],
            'options' => ['class' => 'ml-4 mr-3 mt-2 mb-2 p-1']
        ];
       $logout = '';
    }
    if(Yii::$app->user->can('admin')){
        $menuItems = [
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items'=>[
                    ['label' => 'Накопительная ведомость', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет (охват питания)', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по коллективной оценке здоровья', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Индивидуальное меню', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об анкетировании', 'url' => ['#'], 'options' => ['class' => '']],
                ],
            ],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Настройка разделов базовой информации', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Базовая информация', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    //['label' => 'Общественный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                    //['label' => 'Производственный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                ]
            ],

            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) действующего цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период(упрощенная)', 'url' => ['menus-dishes/menus-period-disable'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],

                ]],
            ['label' => 'Администратор питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'База данных продуктов', 'url' => ['/products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['/dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'Редактирование продуктов', 'url' => ['/products'], 'options' => ['class' => '']],
                    ['label' => 'Редактирование блюд', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур(всех организаций)', 'url' => ['/settings/recipes-index-admin'], 'options' => ['class' => '']],
                    ['label' => 'Настройка коэффициентов брутто/нетто', 'url' => ['/brutto-netto-koef/index'], 'options' => ['class' => '']],
                    ['label' => 'Категории блюд', 'url' => ['/dishes-category/index'], 'options' => ['class' => '']],
                    ['label' => 'Категории продуктов', 'url' => ['/products-category/index'], 'options' => ['class' => '']],
                    ['label' => 'Подкатегории продуктов', 'url' => ['/products-subcategory/index'], 'options' => ['class' => '']],
                    ['label' => 'Нормативы по питанию', 'url' => ['/normativ-info/'], 'options' => ['class' => '']],
                    ['label' => 'Контроль ошибок', 'url' => ['/dishes-products/control'], 'options' => ['class' => '']],
                    ['label' => 'Общие настройки', 'url' => ['/settings-admin/index'], 'options' => ['class' => '']],
                    ['label' => 'Нормативы для прогнозной ведомости', 'url' => ['/normativ-prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'Аллергены', 'url' => ['/allergen/index'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Администратор анкетирования', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Анкета школьников', 'url' => ['/anket-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей школьников', 'url' => ['/anket-parents-school-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей дошкольников', 'url' => ['/anket-preschoolers/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета учителей', 'url' => ['/anket-teacher/create'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по школьникам', 'url' => ['/anket-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям школьников', 'url' => ['/anket-parents-school-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям дошкольников', 'url' => ['/anket-preschoolers/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по учителям', 'url' => ['/anket-teacher/report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Администратор программы', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Отчет по регистрациям (ПС "Оценка эффективности оздоровления детей")', 'url' => ['/users/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по регистрациям (ПС "Питание")', 'url' => ['/users/nutrition-report'], 'options' => ['class' => '']],
                    ['label' => 'Муниципальные образования', 'url' => ['/municipality/index'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Заявки на регистрацию', 'url' => ['users/request'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Управление', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Организации', 'url' => ['/organizations/'], 'options' => ['class' => '']],
                    ['label' => 'Пользователи', 'url' => ['/users/'], 'options' => ['class' => '']],
                    ['label' => 'Дети', 'url' => ['/users/'], 'options' => ['class' => '']],
                ]
            ],
        ];


        $logout = Html::begintag('div', ['class' => 'row'])
            . Html::begintag('div', ['class' => 'col-4'])
            . Html::beginForm(['#'], 'post')
            . //Html::submitInput('Регистрация работников учреждения', ['id' => 'registration_employee', 'class' => 'btn btn-outline-success'])
            Html::a('Регистрация работников учреждения', ['users/createuser'], ['class' => 'btn main-button-2-outline'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::tag('div', 'Пользователь:('. Yii::$app->user->identity->name.')', ['class' => 'col-6 text-right'])
            . Html::begintag('div', ['class' => 'col-2 text-right'])
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::endtag('div');
    }

    if(Yii::$app->user->can('school_director')){
        $menuItems = [
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items'=>[
                    ['label' => 'Накопительная ведомость', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет (охват питания)', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по коллективной оценке здоровья', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Индивидуальное меню', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об анкетировании', 'url' => ['#'], 'options' => ['class' => '']],
                ],
            ],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Настройка разделов базовой информации', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Базовая информация', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    //['label' => 'Общественный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                    //['label' => 'Производственный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) действующего цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                ]],
            /*['label' => 'Дефицитные состояния', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/
            /*['label' => 'Информация классного руководителя', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Настройки классов', 'url' => ['/configuration-classes'], 'options' => ['class' => 'm-5']],
                    //['label' => 'Ввод ежедневной информации', 'url' => ['/daily-informations'], 'options' => ['class' => '']],
                    //['label' => 'Отчет по ежедневной информации', 'url' => ['#'], 'options' => ['class' => '']],
                ]],*/
            ['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Добавление ребенка', 'url' => ['/kids/create'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Список детей по медицинским осмотрам', 'url' => ['/kids'], 'options' => ['class' => '']],
                ]],

            ['label' => 'Анкетирование', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Анкета школьников', 'url' => ['/anket-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей школьников', 'url' => ['/anket-parents-school-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей дошкольников', 'url' => ['/anket-preschoolers/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета учителей', 'url' => ['/anket-teacher/create'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по школьникам', 'url' => ['/anket-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям школьников', 'url' => ['/anket-parents-school-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям дошкольников', 'url' => ['/anket-preschoolers/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по учителям', 'url' => ['/anket-teacher/report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организаторы питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Список список организаций питания', 'url' => ['nutrition-applications/organizations'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Отправленные заявки', 'url' => ['nutrition-applications/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Полученные заявки', 'url' => ['nutrition-applications/receiving'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Меню от организатора питания', 'url' => ['nutrition-applications/send-menu'], 'options' => ['class' => 'm-5']],


                ]],


        ];
        $logout = Html::begintag('div', ['class' => 'row'])
            . Html::begintag('div', ['class' => 'col-4'])
            . Html::beginForm(['#'], 'post')
            . //Html::submitInput('Регистрация работников учреждения', ['id' => 'registration_employee', 'class' => 'btn btn-outline-success'])
            Html::a('Регистрация работников учреждения', ['users/createuser'], ['class' => 'btn main-button-2-outline'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::tag('div', 'Пользователь: ('. Yii::$app->user->identity->name.')', ['class' => 'col-6 text-right'])
            . Html::begintag('div', ['class' => 'col-2 text-right'])
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::endtag('div');
    }

    if(Yii::$app->user->can('camp_director')){
        $menuItems = [
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    //['label' => 'Настройка разделов базовой информации', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Планируемая информация (смены/дети)', 'url' => ['plan-inf-camp/create'], 'options' => ['class' => '']],
                    ['label' => 'Фактическая информация (смены/дети)', 'url' => ['fact-inf-camp/create'], 'options' => ['class' => '']],
                    ['label' => 'Акарицидные обработки (планируемые)', 'url' => ['acaricidal-plan/create'], 'options' => ['class' => '']],
                    ['label' => 'Акарицидные обработки (фактическая)', 'url' => ['acaricidal-fact/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) действующего цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                ]],
            ['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Добавление ребенка в отряд', 'url' => ['kids/kids-med-create'], 'options' => ['class' => '']],
                    ['label' => 'Добавление медицинской информации по детям', 'url' => ['kids/list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Список детей по отрядам', 'url' => ['kids/choice-list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Журнал регистрации амбулаторных больных (Форма №074/у)', 'url' => ['kids/ambulatory-cart-camp-index'], 'options' => ['class' => '']],
                    ['label' => 'Журнал изолятора (Форма №059/у)', 'url' => ['kids/isolator-cart-camp'], 'options' => ['class' => '']],
                    ['label' => 'Журнал оценки эффективности оздоровления', 'url' => ['medicals/report-journal-oeo'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Список детей по отрядам', 'url' => ['kids/choice-list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Индивидуальный отчет', 'url' => ['kids/individual-report-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Дети/Заезды', 'url' => ['fact-inf-camp/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об акарицидных обработках', 'url' => ['acaricidal-fact/report'], 'options' => ['class' => '']],
                    //['label' => 'Отчет в Роспотребнадзор', 'url' => ['medicals/report-rospotrebnadzor'], 'options' => ['class' => '']],
					['label' => 'Коллективный отчет по оценке эффективности оздоровления', 'url' => ['medicals/report-collective'], 'options' => ['class' => '']],
                ]
            ],
            /*['label' => 'Дефицитные состояния', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/

        ];
        if($my_organization->organizator_food == 0){
            $menuItems[1] =
                ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                    'items'=>[
                        ['label' => 'Прогнозная накопительная ведомость', 'url' => ['prognos-storage-org-food/create'], 'options' => ['class' => '']],
                    ]];
        }


        $logout = Html::begintag('div', ['class' => 'row'])
            . Html::begintag('div', ['class' => 'col-4'])
            . Html::beginForm(['#'], 'post')
            . Html::endForm()
            . Html::endtag('div')
            . Html::tag('div', 'Пользователь: '. Yii::$app->user->identity->name.'('.Yii::$app->user->identity->organization_id.')', ['class' => 'col-6 text-right'])
            . Html::begintag('div', ['class' => 'col-2 text-right'])
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::endtag('div');
    }
	
	if(Yii::$app->user->can('kindergarten_director')){
        $menuItems = [
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items'=>[
                    ['label' => 'Накопительная ведомость', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет (охват питания)', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по коллективной оценке здоровья', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Индивидуальное меню', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об анкетировании', 'url' => ['#'], 'options' => ['class' => '']],
                ],
            ],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Базовая информация', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    ['label' => 'Настройка разделов базовой информации', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    //['label' => 'Общественный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                    //['label' => 'Производственный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) действующего цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                ]],
            /*['label' => 'Дефицитные состояния', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/
            ['label' => 'Информация классного руководителя', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Настройки классов', 'url' => ['/configuration-classes'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Ввод ежедневной информации', 'url' => ['/daily-informations'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по ежедневной информации', 'url' => ['#'], 'options' => ['class' => '']],
                ]],
            ['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Добавление медицинского осмотра', 'url' => ['/kids/create'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Список детей по медицинским осмотрам', 'url' => ['/kids'], 'options' => ['class' => '']],
                ]],

            ['label' => 'Анкетирование', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Анкета школьников', 'url' => ['/anket-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей школьников', 'url' => ['/anket-parents-school-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей дошкольников', 'url' => ['/anket-preschoolers/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета учителей', 'url' => ['/anket-teacher/create'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по школьникам', 'url' => ['/anket-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям школьников', 'url' => ['/anket-parents-school-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям дошкольников', 'url' => ['/anket-preschoolers/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по учителям', 'url' => ['/anket-teacher/report'], 'options' => ['class' => '']],
                ]
            ],


        ];
        $logout = Html::begintag('div', ['class' => 'row'])
            . Html::begintag('div', ['class' => 'col-4'])
            . Html::beginForm(['#'], 'post')
            . //Html::submitInput('Регистрация работников учреждения', ['id' => 'registration_employee', 'class' => 'btn btn-outline-success'])
            Html::a('Регистрация работников учреждения', ['users/createuser'], ['class' => 'btn main-button-2-outline'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::tag('div', 'Пользователь: ('. Yii::$app->user->identity->name.')', ['class' => 'col-6 text-right'])
            . Html::begintag('div', ['class' => 'col-2 text-right'])
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::endtag('div');
    }

    if(Yii::$app->user->can('food_director')){
        $menuItems = [
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items'=>[
                    ['label' => 'Накопительная ведомость', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет (охват питания)', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по коллективной оценке здоровья', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Индивидуальное меню', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об анкетировании', 'url' => ['#'], 'options' => ['class' => '']],
                ],
            ],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) действующего цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                ]],
        ['label' => 'Образовательные организации', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
            'items'=>[
                ['label' => 'Список образовательных организаций', 'url' => ['nutrition-applications/organizations'], 'options' => ['class' => 'm-5']],
                ['label' => 'Отправленные заявки', 'url' => ['nutrition-applications/index'], 'options' => ['class' => 'm-5']],
                ['label' => 'Полученные заявки', 'url' => ['nutrition-applications/receiving'], 'options' => ['class' => 'm-5']],
                ['label' => 'Отправить меню в организацию', 'url' => ['nutrition-applications/send-menu'], 'options' => ['class' => 'm-5']],

            ]],
        ];
        $logout = Html::begintag('div', ['class' => 'row'])
            . Html::begintag('div', ['class' => 'col-4'])
            . Html::beginForm(['#'], 'post')
            . //Html::submitInput('Регистрация работников учреждения', ['id' => 'registration_employee', 'class' => 'btn btn-outline-success'])
            Html::a('Регистрация работников учреждения', ['users/createuser'], ['class' => 'btn main-button-2-outline'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::tag('div', 'Пользователь: ('. Yii::$app->user->identity->name.')', ['class' => 'col-6 text-right'])
            . Html::begintag('div', ['class' => 'col-2 text-right'])
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::endtag('div');
    }

    if(Yii::$app->user->can('rospotrebnadzor_camp')){
        $menuItems = [
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Планируемая информация (смены/дети)', 'url' => ['plan-inf-camp/create'], 'options' => ['class' => '']],
                    ['label' => 'Фактическая информация (смены/дети)', 'url' => ['fact-inf-camp/create'], 'options' => ['class' => '']],
                    ['label' => 'Акарицидные обработки (планируемые)', 'url' => ['acaricidal-plan/create'], 'options' => ['class' => '']],
                    ['label' => 'Акарицидные обработки (фактическая)', 'url' => ['acaricidal-fact/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    //['label' => 'Технологические карты', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                ]],
            ['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    //['label' => 'Добавление ребенка в отряд', 'url' => ['kids/kids-med-create'], 'options' => ['class' => '']],
                    //['label' => 'Добавление медицинской информации по детям', 'url' => ['kids/list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Список детей по отрядам', 'url' => ['kids/choice-list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Журнал регистрации амбулаторных больных (Форма №074/у)', 'url' => ['kids/ambulatory-cart-camp-index'], 'options' => ['class' => '']],
                    ['label' => 'Журнал изолятора (Форма №059/у)', 'url' => ['kids/isolator-cart-camp'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Список детей по отрядам', 'url' => ['kids/choice-list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Индивидуальный отчет', 'url' => ['kids/individual-report-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Дети/Заезды', 'url' => ['fact-inf-camp/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об акарицидных обработках', 'url' => ['acaricidal-fact/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет в Роспотребнадзор', 'url' => ['medicals/report-rospotrebnadzor'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Роспотребнадзор Ввод', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Контрольно-надзорные мероприятия', 'url' => ['/control-activities/create'], 'options' => ['class' => '']],
                    ['label' => 'Оценка недополученного оздоровительного эффекта', 'url' => ['/rpn-tests/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Роспотребнадзор Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Оценка соблюдения санитарного законодательства', 'url' => ['/rpn-tests/report-noe-one'], 'options' => ['class' => '']],
                    ['label' => 'Отчет КНМ по протоколам (по лагерям)', 'url' => ['/control-activities/report-protocol'], 'options' => ['class' => '']],
                    ['label' => 'Отчет КНМ по лаборатории (по лагерям)', 'url' => ['/control-activities/report-lab'], 'options' => ['class' => '']],
                    ['label' => 'Отчет КНМ среднее по количеству проб', 'url' => ['/control-activities/report-average-prob'], 'options' => ['class' => '']],
                    ['label' => 'Отчет КНМ сумма по количеству проб', 'url' => ['/control-activities/report-sum-prob'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по оценке недополученного оздоровитльного эффекта (по всем лагерям)', 'url' => ['/rpn-tests/report-noe'], 'options' => ['class' => '']],
                ]
            ],
        ];
    }
    if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr')){
        $menuItems = [
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    //['label' => 'Технологические карты', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                ]],
        ];
    }
    //повсем
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left main-color '],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
    <? if(Yii::$app->user->can('rospotrebnadzor_camp')){
        $session = Yii::$app->session;
        $organization_id = Yii::$app->user->identity->organization_id;
        //echo $organization_id;
        //сессия организация выборка
        $region_id = Organization::findOne($organization_id)->region_id;
        $organization = Organization::find()->where(['type_org' => [2, 3, 5], 'region_id' => $region_id])->all();
        $organization_items = ArrayHelper::map($organization, 'id', 'title');
        $model = new SelectOrgForm();
        $form = ActiveForm::begin([
            'action' => ['site/select-organization'],
        ]);
        echo Html::begintag('div', ['class' => 'row']);

        echo Html::begintag('div', ['class' => 'col-4']);
        $choose_organization_item = $form->field($model, 'organization')->dropDownList($organization_items, [
            'class' => 'form-control mt-3 ml-3', 'options' => [$session['organization_id'] => ['Selected' => true]]])->label(false);
        echo $choose_organization_item;
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-3 mt-3']);
        echo Html::submitButton('Выбрать организацию', ['class' => 'btn main-button-2-outline']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-4']);
        echo Html::tag('div', 'Пользователь: '. Yii::$app->user->identity->name.'('.Yii::$app->user->identity->organization_id.')', ['class' => 'text-right mt-4']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-1 mt-3']);
        echo Html::beginForm(['/site/logout'], 'post');

        echo Html::a(' Выход', ['/site/logout'],
            [
                'class' => 'btn main-button-2-outline',
            ]);

        echo Html::endForm();
        echo Html::endtag('div');
        echo Html::endtag('div');
        ActiveForm::end();
        /*if ($session->has('organization_id')){
            print_r($session['organization_id']) ;
        }*/
        //сессия организация
        //echo $session['organization_id'];
    }
    ?>
    <? if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr')){
        $session = Yii::$app->session;
        $organization_id = Yii::$app->user->identity->organization_id;
        //echo $organization_id;
        //сессия организация выборка
        $region_id = Organization::findOne($organization_id)->region_id;
        $organization = Organization::find()->where(['type_org' => [3, 4, 5], 'region_id' => $region_id])->all();
        $organization_items = ArrayHelper::map($organization, 'id', 'title');
        $model = new SelectOrgForm();
        $form = ActiveForm::begin([
            'action' => ['site/select-organization'],
        ]);
        echo Html::begintag('div', ['class' => 'row']);

        echo Html::begintag('div', ['class' => 'col-4']);
        $choose_organization_item = $form->field($model, 'organization')->dropDownList($organization_items, [
            'class' => 'form-control mt-3 ml-3', 'options' => [$session['organization_id'] => ['Selected' => true]]])->label(false);
        echo $choose_organization_item;
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-3 mt-3']);
        echo Html::submitButton('Выбрать организацию', ['class' => 'btn main-button-2-outline']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-4']);
        echo Html::tag('div', 'Пользователь: '. Yii::$app->user->identity->name.'('.Yii::$app->user->identity->organization_id.')', ['class' => 'text-right mt-4']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-1 mt-3']);
        echo Html::beginForm(['/site/logout'], 'post');

        echo Html::a(' Выход', ['/site/logout'],
            [
                'class' => 'btn main-button-2-outline',
            ]);

        echo Html::endForm();
        echo Html::endtag('div');
        echo Html::endtag('div');
        ActiveForm::end();
        /*if ($session->has('organization_id')){
            print_r($session['organization_id']) ;
        }*/
        //сессия организация
        //echo $session['organization_id'];
    }
    ?>
    <div class="container-fluid mt-3">
        <?php if( Yii::$app->session->hasFlash('success') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif;?>
        <?php if( Yii::$app->session->hasFlash('error') ): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif;?>
        <?= $logout ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer main-color">
        <p class="text-light ml-3 font-weight-bold">Разработчик: <a href="http://niig.su" class="text-light font-weight-normal">ФБУН "Новосибирский НИИ гигиены" Роспотребнадзора</a></p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>