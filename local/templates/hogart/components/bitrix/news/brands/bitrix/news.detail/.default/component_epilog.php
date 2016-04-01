<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.11.2015
 * Time: 15:40
 */
$names = [$arResult['NAME']];
foreach($arResult['PARENT_SECTIONS'] as $section){
    $names[] = $section['NAME'];
}

$APPLICATION->SetPageProperty('title', implode(" - ", $names));