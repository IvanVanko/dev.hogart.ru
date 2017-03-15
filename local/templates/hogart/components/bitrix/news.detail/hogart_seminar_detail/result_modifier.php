<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

# Достаем организатора и лекторов этого семинара
# Организатор, это тоже лектор, но отдельным полем в Семинарах
$arFilter = Array("IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 40 : 9),
                  "ACTIVE" => "Y",
                  'ID' => array_merge($arResult['PROPERTIES']['org']['VALUE'], $arResult['PROPERTIES']['lecturer']['VALUE']));

				
$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC',
                                     'PROPERTY_lecturer.status' => 'ASC'), $arFilter, false, false, array());

while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arFields['props'] = $ob->GetProperties();

    if($arFields['ID'] == $arResult['PROPERTIES']['org']['VALUE']) {
        $arResult['MANAGER'] = $arFields;
    }
    if(in_array($arFields['ID'], $arResult['PROPERTIES']['lecturer']['VALUE'])) {
        $arResult['LECTURERS'][] = $arFields;
    }
}


# Для блока с навигацией между семинарами, около названия семинара
# Строится только для семинаров с корректным sem_start_date
# Без sem_start_date нельзя построить стабильную цепочку семинаров
# FIXME: Как найти следующую и предыдущую запись по SEM_START_DATE нормальрым способом через GetList я не нашел;
# FIXME: Способ через arNavStartParams дает странные результаты

$arResult['NEXT'] = false;
$arResult['PREV'] = false;
if (!empty($arResult['PROPERTIES']['sem_start_date']['VALUE'])) {
    $arSelect = Array("ID", "NAME", 'DETAIL_PAGE_URL');
    $arOrder = Array("PROPERTY_SEM_START_DATE" => "DESC");
    $arFilter = Array(
        "IBLOCK_ID" => $arResult['IBLOCK_ID'],
        "ACTIVE" => "Y",
        "!PROPERTY_SEM_START_DATE" => false,
    );

    $arSeminars = Array();
    $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement()) {
        $arSeminars[] = Array($ob->fields["ID"], $ob->fields["DETAIL_PAGE_URL"]);
    }

    for($i = 0; $i < sizeof($arSeminars); $i++) {
        if ($arSeminars[$i][0] == $arResult["ID"]) {

            if (!empty($arSeminars[$i - 1])) {
                $arResult['NEXT'] = $arSeminars[$i - 1][1];
            }
            if (!empty($arSeminars[$i + 1])) {
                $arResult['PREV'] = $arSeminars[$i + 1][1];
            }
        }
    }
}

# Определяем открыта ли еще регистрация, регистрация закрываетя за ДВА ЧАСА до начала
# Считаем от 'Дата начала семинара', или если есть 'Время начала' то от суммы
# 'Дата начала семинара' и 'Время начала'
# Если у семинара нет даты начала, то это ошибка и регистрация на него закрыта всегда
$arResult['SEM_IS_CLOSED'] = true;
if (!empty($arResult['PROPERTIES']['sem_start_date']['VALUE']) or !empty($arResult['PROPERTIES']['sem_end_date']['VALUE'])) {

    $sem_registration_close = 2;
    if (!empty($arResult['PROPERTIES']['sem_registration_close_time'])) {
        if (!empty($arResult['PROPERTIES']['sem_registration_close_time']['VALUE'])) {
            $sem_registration_close = (int) $arResult['PROPERTIES']['sem_registration_close_time']['VALUE'];
        } elseif (!empty($arResult['PROPERTIES']['sem_registration_close_time']['DEFAULT_VALUE'])) {
            $sem_registration_close = (int) $arResult['PROPERTIES']['sem_registration_close_time']['DEFAULT_VALUE'];
        }
    }

    if (!empty($arResult['PROPERTIES']['sem_end_date']['VALUE'])) {
        $sem_end_date = $arResult['PROPERTIES']['sem_end_date']['VALUE'];
    } else {
        $sem_end_date = $arResult['PROPERTIES']['sem_start_date']['VALUE'];
    }

    # Вообще эту бы проверку перенести в админку
    if (!empty($arResult['PROPERTIES']['time']['VALUE'])) {
        $sem_end_date_epoch = MakeTimeStamp(ConvertDateTime($sem_end_date, "DD.MM.YYYY") .
            " " . $arResult['PROPERTIES']['time']['VALUE'], "DD.MM.YYYY HH:MI");
    } else {
        $sem_end_date_epoch = MakeTimeStamp($sem_end_date, "DD.MM.YYYY HH:MI:SS");
    }

    if ($sem_end_date_epoch - time() >= $sem_registration_close * 3600) {
        $arResult['SEM_IS_CLOSED'] = false;
    }
}