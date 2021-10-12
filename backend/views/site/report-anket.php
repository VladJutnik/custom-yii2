<?php


use phpnt\chartJS\ChartJs;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

    $data_h = [];
    $arrya1 = [];
    $arrya2 = [];
    for($i = 0; $i < count($fed_group); $i++){
        for($j = 0; $j < count($fed_reg_group); $j++){
            if($fed_group[$i]['federal_district_id'] == $fed_reg_group[$j]['federal_district_id']){
                $arrya1[$fed_group[$i]['federal_district_id']][$fed_reg_group[$j]['region_id']] = [];
            }

        }
    }

    $arrya2 = [];
    $i = 0;
    $table = '';
    foreach ($all_datas as $all_data):
        $table .= '<tr>';
        $table .= '<td>'.$all_data->federal_district_id.'</td>';
        $table .= '<td>'.$all_data->region_id.'</td>';
        $table .= '<td>'.$all_data->field1.'</td>';
        if($all_data->field2 === 1){
            $table .= '<td>'.$all_data->field2.'</td>';
        }else{
            $table .= '<td>-</td>';
        }
        if($all_data->field3 === 2){
            $table .= '<td>'.$all_data->field3.'</td>';
        }else{
            $table .= '<td>-</td>';
        }
        if($all_data->field4 === 3){
            $table .= '<td>'.$all_data->field4.'</td>';
        }else{
            $table .= '<td>-</td>';
        }
        $table .= '</tr>';
    endforeach;

?>
<div>
    <table class="table table-sm">
        <tr>
            <td>№</td>
            <td>Фед</td>
            <td>Реги</td>
        </tr>
        <?echo $table?>
    </table>
</div>
