<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Простой пример</h1>
    <br>
</p>

Yii 2 Advanced Project Template - демонстрационный вариант, вернее частичка моих возможностей в yii, часть кода взята с реального работающего проекта!

В программе auth_item: admin, admin_organizations, user_organizations

Шаблон, использован на основе bootstrap, со светло/темной темой, боковым navbar-ом, адаптивными страницами

## В данном примере реализованно:

1. [Разграничение прав доступа к методам](#Разграничение-прав-доступа-к-методам)
2. [Вызов модального окна](#Вызов-модального-окна)
2. [Компонентная модель](#Компонентная-модель)
2. [Чтение файла](#Чтение-файла)
3. Пример выгрузки данных в excel, pdf, word (backend/controllers/OrganizationsController)
4. Пример работы с профилем пользователя, загрузка фото, смена пароля, настройка интерфейса (backend/controllers/UsersController)
5. [Скрины](#Скрины)

## Разграничение прав доступа к методам

```
public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'onoff', 'exportk', 'export-excel'],
                        'allow' => true,
                        'roles' => ['admin', 'admin_organizations'],
                        'denyCallback' => function () {
                            Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                        //'roles' => ['@'], все зарегестрированные
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['user_organizations'],
                        'denyCallback' => function () {
                            Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    ],
                    [
                        'actions' => ['view', 'search', 'search-municipality', 'view-madal'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash("error", "У Вас нет доступа к этой страницы, пожалуйста, обратитесь к администратору!");
                    return $this->redirect(Yii::$app->request->referrer);
                }
            ],
        ];
    }
```

____
[:arrow_up:В данном примере реализованно](#В-данном-примере-реализованно)
___

## Вызов модального окна

```
'view-madal' => function ($url, $model, $key) {
    return Html::button('Модальное окно с выбором', [
        'data_id' => $model->id,
        'class' => 'btn btn-sm btn-warning btn-block',
        'onclick' => '
            $.get("view-madal?id=" + $(this).attr("data_id"), function(data){
            $("#showModal .modal-body").empty();
            $("#showModal .modal-body").append(data);
            $("#showModal").modal("show");
        });'
    ]);                   
},
```

____
[:arrow_up:В данном примере реализованно](#В-данном-примере-реализованно)
___

## Компонентная модель
![Пример работы](image/component.PNG)
```
 'myComponent' => [
            'class' => 'common\components\MyComponent',
 ],
```

____
[:arrow_up:В данном примере реализованно](#В-данном-примере-реализованно)
___
## Чтение файла
```
 public function actionLoading()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $model = new LoadingPatient();
        $organisation = Organization::find()->select(['id', 'title'])->all();
        $organization_title_item = ArrayHelper::map($organisation, 'id', 'title');
        $loads = LoadingPatient::find()->orderby(['create_at' => SORT_DESC])->limit(8)->all();
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()['LoadingPatient'];
            if ($_FILES) {
                $path = "list-patient-upl/"; //папака в которой лежит файл
                $extension = strtolower(substr(strrchr($_FILES['LoadingPatient']['name']['file'], '.'), 1));//узнали в каком формате файл пришел
                $file_name = $model->randomFileName($path, $extension); // сделали новое имя с проверкой есть ли такое имя в папке
                if(move_uploaded_file($_FILES['LoadingPatient']['tmp_name']['file'], $path.$file_name)){ // переместили из временной папки, в которую изначально загрулся файл в новую директорию с новым именем
                    if(($file_list = fopen($path.$file_name, 'r')) !== false){//ищем файл в директории
                        $j = 0;
                        $out = [];
                        while (($data = fgetcsv($file_list, 2000, ";")))//читаем фйал в директории
                        {
                            for($i = 0; $i<count($data); $i++){
                                $out[$j][$i] .= $data[$i];
                            }
                            $j++;
                        }
                        unset($out[0]);
                        $out_save = array_values($out);
                        for($i = 1; $i <= count($out_save); $i++){
                            $model2 = new ListPatients();
                            $model2->organization_id = $post['organization_id'];
                            $model2->fio = $out[$i][1];
                            $model2->order_type = 1;
                            $model2->address_overall = $out[$i][2];
                            $model2->street = $out[$i][3];
                            $model2->house = $out[$i][4];
                            $model2->department = $out[$i][5];
                            $model2->post_profession = $out[$i][6];
                            $model2->save(false);
                        }
                        $model3 = new LoadingPatient();
                        $model3->user_id = Yii::$app->user->identity->id;
                        $model3->organization_id = $post['organization_id'];
                        $model3->file_name = $file_name;
                        $model3->number_rows = count($out_save);
                        $model3->save(false);
                        $model = new LoadingPatient();
                        return $this->render('viev', [
                            'model' => $model,
                            'organization_title_item' => $organization_title_item,
                        ]);
                    }
                    else{
                        Yii::$app->session->setFlash('error', "Не удалось прочесть файл!");
                    }
                }
                else{
                    Yii::$app->session->setFlash('error', "Не удалось загрузить файл!");
                }
            }
            else{
                Yii::$app->session->setFlash('error', "Что то пошло не так!");
            }

        }
        return $this->render('create', [
            'model' => $model,
            'loads' => $loads,
            'organization_title_item' => $organization_title_item,
        ]);
    }
```

____
[:arrow_up:В данном примере реализованно](#В-данном-примере-реализованно)
___

## Скрины
>__Личный кабинет:__
>![Пример работы](image/main1.PNG)
>![Пример работы](image/main2.PNG)
>__Работа с организацией:__
>![Пример работы](image/organization1.PNG)
>![Пример работы](image/organization2.PNG)
>![Пример работы](image/organization3.PNG)

____
[:arrow_up:В данном примере реализованно](#В-данном-примере-реализованно)
___