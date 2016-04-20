<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main;
use Bitrix\Main\SystemException as SystemException;

class EventRegistationResultComponent extends CBitrixComponent {

    protected function checkModules() {
        if(!Main\Loader::includeModule('iblock')) {
            throw new Exception('iblock module not installed');
        }
        if(!isset($this->arParams['ID'])) {
            throw new HttpInvalidParamException('element not found');
        }
    }

    /**
     * Extract data from cache. No action by default.
     * @return bool
     */
    protected function extractDataFromCache() {
        if($this->arParams['CACHE_TYPE'] == 'N') {
            return false;
        }

        global $USER;

        return !($this->StartResultCache(false, array($USER->GetGroups())));
    }

    protected function putDataToCache() {
        $this->endResultCache();
    }

    protected function abortDataCache() {
        $this->AbortResultCache();
    }

    public function prepareData() {
        $filter = array(
            "ID" => $this->arParams['ID'],
            "ACTIVE" => "Y"
        );

        $rsElements = CIBlockElement::GetList(array(), $filter, false, false);
        if($obElement = $rsElements->GetNextElement()) {
            $this->arResult['FORM_RESULT'] = $obElement->GetFields();
            $this->arResult['FORM_RESULT']['PROPERTIES'] = $obElement->GetProperties();
        }

        $filter = array(
            "ID" => $this->arResult['FORM_RESULT']['PROPERTIES']['EVENT']['VALUE'],
            "ACTIVE" => "Y"
        );

        $rsElements = CIBlockElement::GetList(array(), $filter, false, false);
        if($obElement = $rsElements->GetNextElement()) {
            $this->arResult['ELEMENT'] = $obElement->GetFields();
            $this->arResult['ELEMENT']['PROPERTIES'] = $obElement->GetProperties();
            if(!empty($this->arResult['ELEMENT']['PROPERTIES']['ORGANIZER']['VALUE'])) {

                $arFilter = Array('ID' => $this->arResult['ELEMENT']['PROPERTIES']['ORGANIZER']['VALUE']);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array());

                while($ob = $res->GetNextElement()) {
                    $arFields = $ob->GetFields();
                    $arFields['props'] = $ob->GetProperties();
                    $this->arResult['ORGS'] = $arFields;
                }
            }
            if (!empty($this->arResult['FORM_RESULT']['PROPERTIES']['BARCODE']['VALUE'])) {
                $barcode = $this->arResult['FORM_RESULT']['PROPERTIES']['BARCODE']['VALUE'];
            }
            else {
                $barcode = $this->arResult['ELEMENT']['PROPERTIES']['BARCODE']['VALUE'];
            }
            $barcode = str_pad(substr($barcode, 0, 12), 12, "0", STR_PAD_LEFT);
            include_once __DIR__."/ean.php";
            $ean = new EAN13($barcode, 2);
            $this->arResult['BARCODE'] = $ean->getBase64();
        }
    }

    public function executeComponent() {
        try {
            $this->checkModules();

            if(!$this->extractDataFromCache()) {
                $this->prepareData();
                $this->includeComponentTemplate();
                $this->putDataToCache();
            }
        }
        catch(\Exception $e) {
            $this->abortDataCache();
            ShowError($e->getMessage());
        }
    }
}