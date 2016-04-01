<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.07.2015
 * Time: 20:22
 */
require_once("../bitrix/modules/main/include/prolog_before.php");
require_once("ParsingModel.php");
$parse = new ParsingModel();

//$parse->arr_params['ID_TehDoc'] = "ab3b7636-8f51-11e4-8887-003048b99ee9";
//$parse->arr_params['Del_Reg'] = false;
//$parse->arr_params['RegAll'] = true;
//
//$res = $parse->GetResultFunction("RegCategory");


//$parse->arr_params['ID_Item'] = "170f6de1-5e65-11de-a726-00155d0a1e01";
//$parse->arr_params['Del_Reg'] = false;
//
//$res = $parse->GetResultFunction("RegItem");
//echo "<pre>"; var_dump($res); echo "</pre>";
//$properties = CIBlockProperty::GetList(Array(),
//    Array("CODE" => $parse->code1C2codeBitrix('c2ffd985-9010-11e4-8887-003048b99ee9').'_min', "IBLOCK_ID" => 1));
//if ($f_min = $properties->GetNext()) {
//    echo 1111;
//}
//

// Регистрация раздела
//$parse->arr_params['ID_Category'] = "ab3b7636-8f51-11e4-8887-003048b99ee9";
//$parse->arr_params['ID_Portal'] = "HG";
//$parse->arr_params['Del_Reg'] = false;
//$res = $parse->GetResultFunction("RegCategory");

// получение списка разделов
//$res = $parse->GetResultFunction("CategoryGet");
//$parse->initPropname($res);

// регистрация брендов
//$parse->arr_params['ID_Portal'] = "HG";
//$parse->arr_params['ID_TehDoc'] = "8b8fc1f1-87aa-11e5-be5e-003048b99ee9";
//$parse->arr_params['RegAll'] = true;
//$parse->arr_params['Del_Reg'] = false;
//$res = $parse->GetResultFunction("RegTehDoc");
//echo "<pre>"; var_dump($res); echo "</pre>";

// получение брендов


// регистрация на удаление
//$parse->arr_params['ID_Portal'] = "HG";
//$parse->arr_params['ID_TehDoc'] = "d53520dd-1b3a-11e5-8ed9-003048b99ee9";
//$parse->GetResultFunction("DelTehDoc");

$parse->initTehDoc();
