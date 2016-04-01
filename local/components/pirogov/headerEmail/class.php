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
        global $APPLICATION;
        $filter = array(
            "IBLOCK_CODE" => 'header-emails',
            "ACTIVE" => "Y"
        );
        $currentUrl = $APPLICATION->GetCurDir();

        $this->arResult['TOP_EMAIL'] = $this->arParams['TOP_EMAIL'];
        $this->arResult['BOTTOM_EMAIL'] = $this->arParams['BOTTOM_EMAIL'];

        $rsElements = CIBlockElement::GetList(array(), $filter, false, false);
        $emails = [];
        while($obElement = $rsElements->GetNextElement()) {
            $props = $obElement->GetProperties();
            $emails[] = [
                'props' => $props,
                'top_email' => $props['TOP_EMAIL']['VALUE'],
                'bottom_email' => $props['BOTTOM_EMAIL']['VALUE'],
            ];
        }

        foreach($emails as $email) {
            if($currentUrl == $email['props']['URL']['VALUE'] ||
               ($email['props']['INHERIT']['VALUE'] == 'Y' && strpos($currentUrl, $email['props']['URL']['VALUE']) === 0)) {

                $this->arResult['TOP_EMAIL'] = $email['top_email'];
                $this->arResult['BOTTOM_EMAIL'] = $email['bottom_email'];
                break;
            }
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