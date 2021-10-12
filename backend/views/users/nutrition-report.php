<?php

use common\models\Kids;
use common\models\Menus;
use common\models\Municipality;
use common\models\TypeLager;
use common\models\TypeOrganization;
use common\models\User;
use common\models\FederalDistrict;
use common\models\Region;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use common\models\Organization;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-nutrition-report-form container"><h2 align="center">Отчет по регистрациям (ПС "Питание")</h2>
    <?php
    $form = ActiveForm::begin();
    $federal_district = FederalDistrict::find()->all();
    $federal_district_item = ArrayHelper::map($federal_district, 'id', 'name');
    $type_org = TypeOrganization::find()->all();
    $type_org_item = ArrayHelper::map($type_org, 'id', 'name');
    //не пришли с контроллера
    if (empty($district_for_district) && empty($region_for_district) && empty($municipality_for_region)) {
        $district_for_district = 0;
        $region_for_district = 0;
        $municipality_for_region = 0;
    }
    if (empty($type_org_key)) {
        $type_org_key = 0;
    }
    $two_column = [
        'options' => ['class' => 'row mt-3'],
        'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
    ];
    $federal_district_item['0'] = 'Все';
    $region_item['0'] = 'Все';
    $municipality_item['0'] = 'Все';
    $type_org_item['0'] = 'Все';
    //удаляем лишнее из выборки:
    $unset_organization = [1, 2, 7, 8];
    for ($i = 0; $i < count($unset_organization); $i++) {
        unset($type_org_item[$unset_organization[$i]]);
    }
    //рисуем форму:
    echo $form
        ->field($model, 'federal_district_id', $two_column)
        ->dropDownList($federal_district_item,
            [
                'options' => [$district_for_district => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    echo $form
        ->field($model, 'region_id', $two_column)
        ->dropDownList($region_item,
            [
                'options' => [$region_for_district => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    echo $form
        ->field($model, 'municipality_id', $two_column)
        ->dropDownList($municipality_item,
            [
                'options' => [$municipality_for_region => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    echo $form
        ->field($model, 'type_org', $two_column)
        ->dropDownList($type_org_item,
            [
                'options' => [$type_org_key => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    ?>
    <div class="form-group row">
        <?= Html::submitButton('Показать', ['class' => 'btn btn-success form-control col-12 mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<? if (!empty($districts)) //нажали кнопку показать
{
    ?>
    <table class="table table-bordered table-sm table-responsive">
        <thead>
        <tr>
            <th class="text-center" rowspan="2" colspan="1">№</th>
            <th class="text-center" rowspan="2" colspan="1">Федеральный округ</th>
            <th class="text-center" rowspan="2" colspan="1">Субъект Федерации</th>
            <th class="text-center" rowspan="2" colspan="1">Муниципальное образование</th>
            <th class="text-center" rowspan="2" colspan="1">Наименование учреждения</th>
            <th class="text-center" rowspan="2" colspan="1">Тип организации</th>
            <th class="text-center" rowspan="1" colspan="2">Настройки программы</th>
            <th class="text-center" rowspan="2" colspan="1">Внесено меню</th>
            <th class="text-center" rowspan="2" colspan="3">Внесено детей</th>
        </tr>
        <tr>
            <th class="text-center" rowspan="1" colspan="1">Организатор питания</th>
            <th class="text-center" rowspan="1" colspan="1">Мед. обслуживание</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $array_org = array();
        $array_org[] = array();
        $i = 0;
        $j = 0;
        $k = 0;
        $type_org_key = ($type_org_key == '0' ? ('') : $type_org_key);
        $municipality_for_region = ($municipality_for_region == '0' ? ('') : $municipality_for_region);
        $cur_district = 0;
        $prev_district = 0;
        //print_r($type_org_key);
        $menus_all_count = 0;
        $menu_all_count = 0;
        $kids_all_count = 0;
        $kid_all_count = 0;
        foreach ($districts as $district) {
            $cur_district = $district->id;
            if ($region_for_district == 0) {
                $regions = Region::find()
                    ->select('id')
                    ->where(['district_id' => $district->id])
                    ->all();//получили все регионы по всем округам
            }
            else {
                $regions = Region::find()
                    ->select('id')
                    ->where(['district_id' => $district->id, 'id' => $region_for_district])
                    ->all();//получили регион
            }
            if ($regions) {
                $count_organizations_all = 0;
                $prev_region = 0;
                $cur_region = 0;
                $menus_district_count = 0;
                $menu_district_count = 0;
                $kids_district_count = 0;
                $kid_district_count = 0;
                foreach ($regions as $region)//цикл по регионам
                {
                    $cur_region = $region->id;
                    $organizations = Organization::find()
                        ->where(['region_id' => $region->id])
                        ->andWhere(['not in', 'type_org', $unset_organization])
                        ->andFilterWhere(['type_org' => $type_org_key])
                        ->andFilterWhere(['municipality_id' => $municipality_for_region])
                        ->all();
                    $count_organizations = Organization::find()
                        ->where(['region_id' => $region->id])
                        ->andWhere(['not in', 'type_org', $unset_organization])
                        ->andFilterWhere(['type_org' => $type_org_key])
                        ->andFilterWhere(['municipality_id' => $municipality_for_region])
                        ->count();
                    $count_organizations_all += $count_organizations;
                    //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                    $menus_region_count = 0;
                    $menu_region_count = 0;
                    $kids_region_count = 0;
                    $kid_region_count = 0;
                    foreach ($organizations as $organization) {
                        //$user_id = User::find()->select(['id'])->where(['organization_id' => $organization->id]);
                        //print_r($user_id);
                        //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                        $array_org[$i][0] = $organization->federal_district_id;
                        $array_org[$j][0] = $organization->federal_district_id;
                        $array_org[$k][0] = $organization->federal_district_id;
                        $array_org[$i][1] = $organization->region_id;
                        $array_org[$j][1] = $organization->region_id;
                        $array_org[$i][3] = $organization->title;
                        $array_org[$i][2] = Municipality::findOne($organization->municipality_id)->name;
                        $array_org[$i][4] = $organization->get_type_org($organization->type_org);
                        $array_org[$i][7] = $organization->organizator_food;
                        $array_org[$i][8] = $organization->medic_service_programm;

                        $table = '<tr>';
                        $table .= '<td>' . ($i + 1) . '</td>';
                        $table .= '<td>' . $model->get_district($array_org[$i][0]) . '</td>';
                        $table .= '<td>' . $model->get_region($array_org[$i][1]) . '</td>';
                        $table .= '<td class="text-center">' . $array_org[$i][2] . '</td>';
                        $table .= '<td class="text-center">' . (isset($array_org[$i][3]) ? $array_org[$i][3] : '-') . '</td>';
                        $table .= '<td class="text-center">' . (isset($array_org[$i][4]) ? $array_org[$i][4] : '-') . '</td>';
                        //$table .= '<td class="text-center">' . (isset($array_org[$i][5]) ? $array_org[$i][5] : '-') . '</td>';
                        $table .= '<td class="text-center">' . ($array_org[$i][7] == '0' ? 'Отсутствует' : 'Присутствует') . '</td>';
                        $table .= '<td class="text-center">' . ($array_org[$i][8] == '0' ? 'Полный' : 'Краткий') . '</td>';
                        $menus_organization_count = Menus::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                            ->count();
                        $menus_region_count+=$menus_organization_count;
                        $menu_region_count += Menus::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                            ->groupBy('organization_id')
                            ->count();
                        $table .= '<td class="text-center">'.$menus_organization_count.'</td>';
                        $kids_organization_count = Kids::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id])
                            ->count();
                        $kids_region_count+=$kids_organization_count;
                        $kid_region_count += Kids::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id])
                            ->groupBy('organization_id')
                            ->count();
                        $table .= '<td colspan="3" class="text-center">'.$kids_organization_count.'</td>';
                        $table .= '</tr>';
                        echo $table;
                        $i++;
                    }
                    //итоги по региону
                    $menus_district_count+=$menus_region_count;
                    $menu_district_count+=$menu_region_count;
                    $kids_district_count+=$kids_region_count;
                    $kid_district_count+=$kid_region_count;
                    if ($count_organizations) {
                        $table = '<tr class="main-color-3">';
                        $table .= '<td>' . $model->get_district($district->id) . '</td>';
                        $table .= '<td>' . $model->get_region($region) . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">Количество организаций всего:</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $count_organizations . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">Количество организаций внесших меню:</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menu_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">Внесено меню всего:</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">Количество организаций внесших детей:</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $kid_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">Внесено детей всего:</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $kids_region_count . '</td>';
                        $table .= '</tr>';
                    }
                    else {
                        $table = '<tr class="main-color-3">';
                        $table .= '<td>' . $model->get_district($district->id) . '</td>';
                        $table .= '<td>' . $model->get_region($region) . '</td>';
                        $table .= '<td colspan="10" class="text-center align-middle">Не найдено организаций в регионе</td>';
                        $table .= '</tr>';
                    }
                    echo $table;
                }
                $menus_all_count+=$menus_district_count;
                $menu_all_count+=$menu_district_count;
                $kids_all_count+=$kids_district_count;
                $kid_all_count+=$kid_district_count;
                //итоги по округу
                //if ($prev_district != $cur_district) {
                $table = '<tr class="main-color">';
                $table .= '<td colspan="2">' . $model->get_district($district->id) . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">Количество организаций всего:</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $count_organizations_all . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">Количество организаций внесших меню:</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $menu_district_count . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">Внесено меню всего:</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $menus_district_count . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">Количество организаций внесших детей:</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $kid_district_count . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">Внесено детей всего:</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $kids_district_count . '</td>';
                $table .= '</tr>';
                //}
                //('prev_district: ' . $prev_district . ' ------------- ');
                //print_r('cur_district: ' . $cur_district . '<br>');
                echo $table;
            }
            else {
                //нули по округам
                $table = '<tr class="main-color">';
                $table .= '<td colspan="2">' . $model->get_district($array_org[$k][0]) . '</td>';
                $table .= '<td colspan="7" class="text-center align-middle">0</td>';
                $table .= '</tr>';
                echo $table;
            }
        }
        $table = '<tr class="main-color-2">';
        $table .= '<td colspan="3">Итого организаций: </td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . (($i == 0) ? 0 : count($array_org)) . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">Итого организаций внесших меню:</td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . $menu_all_count . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">Итого внесено меню:</td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_all_count . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">Итого организаций внесших детей:</td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . $kid_all_count . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">Итого внесено детей:</td>';
        $table .= '<td colspan="2" class="text-center align-middle">' . $kids_all_count . '</td>';
        $table .= '</tr>';
        echo $table;
        ?>

        </tbody>
    </table>
    <?
}
?>
<?
//print_r('$district_for_district: ' . $district_for_district . '<br>');
//print_r('$region_for_district: ' . $region_for_district . '<br>');
//print_r('$municipality_for_region: ' . $municipality_for_region . '<br>');
//print_r('$type_lager_key: ' . $type_lager_key . '<br>');

$script = <<< JS
$('#organization-federal_district_id').change(function() {
    var value = $('#organization-federal_district_id option:selected').val();
    $.ajax({
         url: "../organizations/search",
              type: "GET",      // тип запроса
              data: { // действия
                  'id': value
              },
              // Данные пришли
              success: function( data ) {
                    $("#organization-region_id").empty();
                    $("#organization-region_id").append(data);
                    $("#organization-municipality_id").empty();
                    $('#organization-municipality_id').append('<option value="0">Все</option>');
              },
              error: function(err) {
                 console.log(err);
              }
         })
});
$('#organization-region_id').change(function() {
    var value1 = $('#organization-region_id option:selected').val();
    $.ajax({
         url: "../organizations/search-municipality",
              type: "GET",      // тип запроса
              data: { // действия
                  'id': value1
              },
              // Данные пришли
              success: function( data1 ) {
                  $("#organization-municipality_id").empty();
                  $("#organization-municipality_id").append(data1);
              },
              error: function(err) {
                 console.log(err);
              }
         })
});
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>




