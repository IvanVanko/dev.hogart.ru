<?
class HogartHelpers {
    public static function GetStaticContent($code)
    {
        static $cacheStatic = [];
        if (empty($cacheStatic[$code])) {
            $cacheStatic[$code] = CIBlockElement::GetList([], [
                "IBLOCK_CODE" => "STATIC_CONTENT",
                "IBLOCK_TYPE_ID" => "content",
                "CODE" => $code
            ])->Fetch();
        }
        return $cacheStatic[$code];
    }
    public static function ShowStaticContent($code, $prop = null)
    {
        return self::GetStaticContent($code)[$prop];
    }

    public static function rewriteBrandUrlToCatalog($brand_code, $catalog_section_code, $catalog_iblock_id) {
        $catalog_section_code = end(explode("/", $catalog_section_code));
        global $APPLICATION;
        if (!empty($brand_code) && !empty($catalog_section_code)) {
            $sections = BXHelper::getSections(array(), array("IBLOCK_ID" => $catalog_iblock_id,"CODE" => $catalog_section_code), array(), array("ID","CODE"), true, "CODE");
            if (!empty($sections['RESULT'])) {

                $section_id = $sections['RESULT'][$catalog_section_code]['ID'];

                $element_count = CIBlockElement::GetList(array(), array(
                    'SECTION_ID' => $section_id,
                    'PROPERTY_brand.CODE' => $brand_code,
                    'IBLOCK_ID' => $catalog_iblock_id,
                    'INCLUDE_SUBSECTIONS' => 'Y',
                    'ACTIVE' => 'Y'
                ), array());

                if (intval($element_count)) {
                    $upper_sections = BXHelper::getSectionPath($catalog_iblock_id, $section_id, array("CODE","ID"), true);
                    $section_codes = array();
                    if (!empty($upper_sections["RESULT"])) {
                        foreach ($upper_sections["RESULT"] as $arSection) {
                            $section_codes[] = $arSection["CODE"];
                        }
                    }
                    if (count($section_codes)) {
                        $section_path = implode("/",$section_codes);
                        $APPLICATION->SetCurPage("/catalog/".$section_path."/");
                        $APPLICATION->SetDirProperty("body_class","catalog_page");
                        CStorage::setVar($brand_code, "CATALOG_BRAND_CODE");
                        return true;
                    }
                }

            }
        }
        return false;
    }

    public static function getAdjacentProductPropertyHtml($elem_id, $show_properties, $hidden_properties, $exclude_props = array()) {

            if ($show_properties === $hidden_properties) {
                unset($hidden_properties);
            }

            $hidden_exist = false;
            if (!empty($show_properties)) {?>
                <div>
                <?foreach ($show_properties as $prop) {
                    if (!in_array($prop['CODE'], $exclude_props)) {?>
                        <dl>
                            <dt><?= $prop["NAME"] ?></dt>
                            <dd class="pr"><?= $prop["VALUE"] ?></dd>
                        </dl>
                    <?}?>
                <?}?>
                </div>
            <?}
            if (!empty($hidden_properties)) {?>
                <div class="collapse" id="show-adjacent-props<?=$elem_id?>">
                    <?foreach ($hidden_properties as $prop) {
                        if (!in_array($prop['CODE'], $exclude_props)) {
                            $hidden_exist = true;?>
                            <dl>
                                <dt><?= $prop["NAME"] ?></dt>
                                <dd class="pr"><?= $prop["VALUE"] ?></dd>
                            </dl>
                        <?}?>
                    <?}?>
                </div>

                <?if ($hidden_exist) {?>
                    <dl class="open-more-info" data-active-label="Скрыть" data-hidden-label="Все характеристики" data-collapse="#show-adjacent-props<?=$elem_id?>">
                        <dt><span>Все характеристики</span></dt>
                        <dd></dd>
                    </dl>
                <?}?>
            <?}
    }

    public static function rewriteBrandElementUrlToCatalog($catalog_iblock_id, $brand_iblock_id, $element_identifier_name, $property_code) {
        //метод необходим чтобы определить, что путь
        global $APPLICATION;
        $ar_exploded_url = explode("/", $APPLICATION->GetCurDir());
        $c = count($ar_exploded_url);
        if ($c > 6/*по длине URL определяем что путь был указан до элемента каталога*/) {
            $if_brand_code = $ar_exploded_url[$c-3];
            $elements = BXHelper::getElements(array(), array('IBLOCK_ID' => $brand_iblock_id, 'CODE' => $if_brand_code), false, false, array('ID','CODE'), true, 'CODE');
            $element_id = $elements['RESULT'][$if_brand_code]['ID'];
            if (intval($element_id)) {
                $catalo_element_code  = $ar_exploded_url[$c-2];
                //если такой бренд есть, то проверяем, установлен ли этот бренд у того элемента на которой мы пытаемся перейти
                $catalog_elements = BXHelper::getElements(array(), array('IBLOCK_ID' => $catalog_iblock_id, $element_identifier_name => $catalo_element_code, "PROPERTY_".$property_code => $element_id), false, false, array('ID','CODE'), true, 'CODE');
                if (count($catalog_elements['RESULT'])) {
                    //если мы определили что в URL присутствует код бренда, значит перереход был сделан в элемент со страницы бренда
                    array_splice($ar_exploded_url, $c-3, 1);
                    //запоминаем переменную с ключом CATALOG_BRAND_CODE
                    CStorage::setVar($if_brand_code, "CATALOG_BRAND_CODE");
                    //переписываем URL
                    $APPLICATION->SetCurPage(implode("/",$ar_exploded_url));
                    return true;
                }
                //в этом случае мы определили что URL был указан до элемента каталога + в URL есть код бренда
            } else {
                //в этом случае мы определили по длине URL чтоб путь был указан до элемента каталога, но бренд не указан. Отдаем 404
                return false;
            }
        } else {
            //в этом случае мы определили что путь был указан не до элемента каталога, а до раздела. Пропускаем такие случаи.
            return true;
        }
    }

    public static function setBrandRequestFilter($brand_code, $brand_iblock_id, $catalog_iblock_id, $property_code) {
        if (!empty($brand_code)) {
            global $_GET;
            $elements = BXHelper::getElements(array(), array("IBLOCK_ID" => $brand_iblock_id, "CODE" => $brand_code), false, false, array("ID","CODE"), true, 'CODE');
            $properties = BXHelper::getProperties(array(), array("IBLOCK_ID" => $catalog_iblock_id, "CODE" => $property_code), array("ID", "CODE"), "CODE");
            $element_id = $elements['RESULT'][$brand_code]['ID'];
            $property_id = $properties['RESULT'][$property_code]['ID'];

            if (intval($element_id) && intval($property_id)) {
                //abs(crc32(ELEMENT_ID)) - алгоритм формирования имени инпута для варианта фильтра типа "E", которое должно пойти в REQUEST если бы фильтр был установлен
                //выставляем его принудетельно в том случае если мы переходим в раздел каталога через бренды
                $_GET["arrFilter_".$property_id."_".abs(crc32($element_id))] = "Y";
                $_GET["set_filter"] = "Y";
            }
        }
    }

    public static function rebuildBrandSectionHref($folder, $sectionCode, $brandCode) {
        return str_replace("/catalog/", "{$folder}{$brandCode}/", $sectionCode);
    }

    public static function rebuildBrandElementHref($href, $brand_code) {
        $exploded_href = explode("/",$href);
        $c = count($exploded_href);
        array_splice($exploded_href, $c-2, 0, array($brand_code));
        return implode("/",$exploded_href);
    }

    public static function getRangePropertyGroupsForFilter() {
        global $DB;

        $dbResult = $DB->Query("
            SELECT 
              DISTINCT bip.ID, bip.NAME, bip.CODE 
              FROM b_iblock_property bip 
              JOIN b_iblock_element_property biep ON bip.ID = biep.IBLOCK_PROPERTY_ID 
              WHERE 1=1
                AND bip.PROPERTY_TYPE = 'N' 
                AND bip.IBLOCK_ID = 1 
                -- AND biep.VALUE <> '' 
                AND (bip.CODE LIKE '%min%' OR bip.CODE LIKE '%max%')");
        //$dbResult = $DB->Query("SELECT DISTINCT bip.ID, bip.NAME, bip.CODE FROM b_iblock_property bip WHERE bip.PROPERTY_TYPE = 'N' AND bip.IBLOCK_ID = 1 AND (bip.CODE LIKE '%min%' OR bip.CODE LIKE '%max%')");
        $range_groups = array();
        while ($next = $dbResult->GetNext()) {
            $is_min = $is_max = false;
            $code = preg_replace("/(_max|_min)/i","", $next['CODE']);
            foreach (array('min','max') as $type) {
                ${"is_".$type} = substr_count($next['CODE'], $type) > 0;
            }
            if (!isset($range_groups[$code]['RANGE'])) {
                $range_groups[$code]['RANGE'] = array();
            }
            if ($is_max) {
                $range_groups[$code]['RANGE'][1] = $next['ID'];
            } else if ($is_min) {
                $name = trim(preg_replace("/(минимальный)/iu","", $next['NAME']));
                $range_groups[$code]['NAME'] = $name;
                $range_groups[$code]['RANGE'][0] = $next['ID'];
            }
        }
        return array_values($range_groups);
    }

    public static function wPrice ($display_price) {
        return preg_replace("/(^[\d \.,]+)(.+)/","$1 <span>$2</span>", $display_price);
    }

    public static function woPrice ($display_price) {
        return preg_replace("/(^[\d \.,]+)(.+)/","$1", $display_price);
    }


	 /**
     * Получает ID свойства по его символьному коду и символьному коду информационного блока, которому принадлежит
     * @param $IBlock_code
     * @param $code
     * @return bool|int
     */
    public static function getPropIDByCode($IBlock_code, $code) {
        \CModule::IncludeModule('iblock');
        $result = false;
        $arOrder = array(
            'timestamp_x' => 'DESC'
        );
        $arFilter = array(
            'IBLOCK_CODE' => $IBlock_code,
            'CODE' => $code
        );
        $obCache = new \CPHPCache;
        $cache_id = 'PropIDByCode'.$IBlock_ID.$code;
        $life_time = 60*60*24;
        if ($obCache->InitCache($life_time, $cache_id))
        {
            $res = $obCache->GetVars();
            $result = $res['result'];
        }
        elseif ($obCache->StartDataCache())
        {
            $cdbResult = \CIBlockProperty::GetList($arOrder, $arFilter);
            if ($element = $cdbResult->GetNext()) {
                $result = $element['ID'];
            }
            $obCache->EndDataCache(array(
                'result' => $result,
            ));
        }
        return $result;
    }
    public static function generateSeminarRegistrationNumber () {
        $valid_number = false;
        CModule::IncludeModule('iblock');
        $obElement = new CIBlockElement();
        while (!$valid_number) {
            $rand = rand(10000000, 99999999);
            CModule::IncludeModule("form");
            $FORM_ID = 5;
            $arFields[] = array(
                "CODE"              => "SEMINAR_REGISTRATION_NUMBER",       
                "FILTER_TYPE"       => "text",       
                "PARAMETER_NAME"    => "USER",         
                "VALUE"    			=> $rand,          
                "EXACT_MATCH"       => "Y"                
            );
            $arFilter["FIELDS"] = $arFields;
            $rsResults = CFormResult::GetList($FORM_ID,
                ($by="s_timestamp"),
                ($order="desc"),
                $arFilter,
                $is_filtered="true",
                "Y");
            $count = $obElement->GetList(array(), array('IBLOCK_ID' => SEMINAR_IBLOCK_ID ,'PROPERTY_sem_ean_id' => $rand, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), array());

		//	if (!intval($count)) {
                $valid_number = $rand;
        //    }
        }
        return $valid_number;
    }

    public static function generateSeminarNumber () {
        $valid_number = false;
        CModule::IncludeModule('iblock');
        $obElement = new CIBlockElement();
        while (!$valid_number) {
            $rand = rand(10000000, 99999999);
            $count = $obElement->GetList(array(), array('IBLOCK_ID' => SEMINAR_IBLOCK_ID ,'PROPERTY_sem_ean_id' => $rand, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), array());
            if (!intval($count)) {
                $valid_number = $rand;
            }
        }


        return $valid_number;
    }

    public static function generateBarCodeImageData ($code, $encoding = "ANY", $scale = 2, $mode = "png") {
        $base64_file = "data:image/png;base64,".trim(shell_exec("php -f ".$_SERVER["DOCUMENT_ROOT"]."/ean2/barcode.php code=$code encoding=$encoding scale=$scale mode=$mode"));
        return $base64_file;
    }

	public static function generateBarCodeData ($code, $encoding = "ANY", $scale = 2, $mode = "code13") {
        $base64_code = trim(shell_exec("php -f ".$_SERVER["DOCUMENT_ROOT"]."/ean2/barcode.php code=$code encoding=$encoding scale=$scale mode=$mode"));
        return $base64_code;
    }
	
    public static function mergeRangePropertiesForItem (&$arProperties) {
        $arMergedProp = array();
        foreach ($arProperties as &$arProp) {
            if (preg_match("/(.*)_min/", $arProp['CODE'], $match)) {
                $base_code = $match[1];
                $new_name = preg_replace("/минимальный/u","",$arProp['NAME']);
                if (!empty($arProperties[$base_code."_max"])) {
                    $arProp['NAME'] = $new_name;
                    if ($arProperties[$base_code."_max"]['VALUE'] > $arProperties[$base_code."_min"]['VALUE']) {
                        $new_value = "от ".$arProp['VALUE'];
                        $new_value .= " до ".$arProperties[$base_code."_max"]['VALUE'];
                    } else {
                        $new_value = $arProperties[$base_code."_max"]['VALUE'];
                    }
                    unset($arProperties[$base_code."_max"]);
                    $arProp['VALUE'] = $new_value;
                }
            }
        }
    }
}
?>