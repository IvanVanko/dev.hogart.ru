<?php
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/CCustomInit.php')){
	require_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/CCustomInit.php');
}

require_once dirname(__FILE__) . "/HogartBlockPropertyMeasureList.php";

#
# 22.09.2015 For mobile version
#
# Функция определяет мобильное устройство
# IsMadeonMobile(); - возвращает TRUE если именно ТЕЛЕФОН
# Используем в шаблоне для мобильной версии, как параметр PHP 
function IsMadeonMobile()
{
	# RTFM Описание модуля
	if(!CModule::IncludeModule('nurgush.mobiledetect')) {
		return false;
	}
	$detect = new Nurgush\MobileDetect\Main();
	// Мобильные исключая планшеты
	if( $detect->isMobile() && !$detect->isTablet() ){
		return true;
	}
}

#
# Дебаггер, потом если хотите удалите
# Работает и виден только для группы "Администратор"
#
define("MOBILE_PATH","/local/templates/hogart_mobile_new/");
define("INCLUDE_AREAS","/local/templates/hogart_mobile_new/include_areas/");

function DebugMessage($message, $title = false, $color = "#008B8B")
{
	Global $USER;
	if (!is_object($USER))
		$USER = new CUser;
	if ($USER->IsAdmin()) {
		echo '<table border="0" cellpadding="5" cellspacing="0" style="border:1px solid ' . $color . ';margin:2px;"><tr><td>';
		if (strlen($title) > 0) {
			echo '<p style="color: ' . $color . ';font-size:11px;font-family:Verdana;">[' . $title . ']</p>';
		}
		if (is_array($message) || is_object($message)) {
			echo '<pre style="color:' . $color . ';font-size:11px;font-family:Verdana;">';
			print_r($message);
			echo '</pre>';
		} else {
			echo '<p style="color:' . $color . ';font-size:11px;font-family:Verdana;">' . $message . '</p>';
		}
		echo '</td></tr></table>';
	}
}

function convert($size) {
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function DebugMemory() {
	if (defined('DEBUG_MEMORY') && DEBUG_MEMORY === true) {
		$d = debug_backtrace();
		$line = $d[0]['line'];
		$file = $d[0]['file'];
		$memory = convert(memory_get_usage(true));
		DebugMessage("Файл {$file}:{$line}, использовано памяти {$memory}");
	}
}

if (CModule::IncludeModule("subscribe"))
{
	$cPosting = new CPosting;
	$cPosting->AutoSend();
}

$obInit = new CCustomInit();
$obInit->addFolder('classes');
$obInit->addFolder('entities');
$obInit->addFolder('forks');
$obInit->addFolder('vendor');
$obInit->addFolder('');
//$obInit->addFolder('scripts');



$obInit->Init();

//if (class_exists('DeviceDetector')) {
	$browsers = new DeviceDetector\DeviceDetector($_SERVER['HTTP_USER_AGENT']);
	$browsers->parse();

	$browsers->parse();

	if ($browsers->isBot()) {
		// handle bots,spiders,crawlers,...
		$botInfo = $browsers->getBot();
	} else {
		$GLOBALS['user_agent']['browser'] = $browsers->getClient(); // holds information about browser, feed reader, media player, ...
		$GLOBALS['user_agent']['os'] = $browsers->getOs();
		$GLOBALS['user_agent']['device_type'] = $browsers->getDevice();
		$GLOBALS['user_agent']['device_brand'] = $browsers->getBrand();
		$GLOBALS['user_agent']['device_model'] = $browsers->getModel();
	}
//}

global $BX_MENU_CUSTOM;
$BX_MENU_CUSTOM = new HogartCustomMenu();

//if (class_exists('Firestorm\BXHelper\BXHelper')) {
	class BXHelper extends Firestorm\BXHelper\BXHelper {
		public static function aggregateElemPropToSectProp ($iblock_id, $element_prop_code, $section_string_uf_prop_code, $section_id = false, $element_id = false ,$delimiter = ",") {
			if (!empty($element_prop_code) && !empty($section_string_uf_prop_code)) {
				$property = BXHelper::getProperties(array(), array("IBLOCK_ID" => $iblock_id, "CODE" => $element_prop_code), array("PROPERTY_TYPE", "ID", "CODE"), "CODE", true);
				$property = $property['RESULT'][$element_prop_code];
				$element_filter = array("IBLOCK_ID" => $iblock_id);
				$enum = array();
				if (intval($section_id)) {
					$element_filter["SECTION_ID"] = $section_id;
				}
				if ($property["PROPERTY_TYPE"] == "L") {
					$enum = BXHelper::getEnum($property["ID"], array(), array(), "ID", false);
				}
				if ($property["PROPERTY_TYPE"] == "E") {
					$elements = BXHelper::getElements(array(), $element_filter, false, false, array("PROPERTY_".$element_prop_code.".NAME", "ID", "NAME"), false);
					$valid_prop_key = "PROPERTY_".strtoupper($element_prop_code)."_NAME";
				} else {
					$elements = BXHelper::getElements(array(), $element_filter, false, false, array("PROPERTY_".$element_prop_code, "ID", "NAME"), false);
					$valid_prop_key = "PROPERTY_".strtoupper($element_prop_code)."_VALUE";
				}
				$elements = $elements['RESULT'];
				$prop_values = array();
				foreach ($elements as $elem) {
					if ($property["PROPERTY_TYPE"] == "L") {
						$current_prop_value = $enum[$elem[$valid_prop_key]];
					} else {
						$current_prop_value = $elem[$valid_prop_key];
					}
					if (!in_array($current_prop_value,$prop_values) && !empty($current_prop_value)) {
						$prop_values[] = $current_prop_value;
					}
				}
				$sect_uf_prop_value = implode($delimiter, $prop_values);
				if ($section_id) {
					$obSection = new CIBlockSection();
					$obSection->Update($section_id,array($section_string_uf_prop_code => $sect_uf_prop_value));
				}
			}
		}

		public static function getDownloadLink ($id, $script_name = "download.php", $name = false, $ext = false) {
			if (is_file($_SERVER['DOCUMENT_ROOT']."/".$script_name)) {
				if ($name && $ext) {
					$name = $name.".".$ext;
					return "/".$script_name."?id=".$id."&name=".$name;
				} else if ($name) {
					return "/".$script_name."?id=".$id."&name=".$name;
				} else {
					return "/".$script_name."?id=".$id;
				}

			}
			return false;
		}

		public static function updateAggregateSectProp ($iblock_id, $element_prop_code, $section_string_uf_prop_code, $section_id, $element_id, $property_values ,$delimiter = ",") {
			//пока не работает для multiple
			if (
				!empty($element_prop_code) &&
				!empty($section_string_uf_prop_code) &&
				intval($section_id)
			) {

				$property = BXHelper::getProperties(array(), array("IBLOCK_ID" => $iblock_id, "CODE" => $element_prop_code), array(), "CODE", true);
				$property = $property['RESULT'][$element_prop_code];
				$enum = array();
				if (intval($section_id)) {
					$element_filter["SECTION_ID"] = $section_id;
				}
				if ($property["PROPERTY_TYPE"] == "L") {
					$enum = BXHelper::getEnum($property["ID"], array(), array(), "ID", false);
				} else {
					$enum = BXHelper::getElementLinkEnum(false,$property);
				}

				$try_add = false;
				$try_remove = false;
				$do_nothing = false;

				$current_sections = false;

				if (intval($element_id)) {
					$element = BXHelper::getElements(array(), array('ID' => $element_id,'IBLOCK_ID' => $iblock_id), false, false, array(), false, "ID", true);
					$element = $element['RESULT'][$element_id];
					$current_value = $element['PROPERTIES'][$element_prop_code]['VALUE'];
					$current_sections = $element['SECTIONS'];
				}

				$updated_value = current($property_values[$property['ID']]); // вот здесь логика завязана на условие что у нас property не multiple

				if (is_array($updated_value) && array_key_exists("VALUE", $updated_value)) {
					$updated_value = $updated_value['VALUE'];
				}


				//проверяем текущие и установленные значения свойства
				if ($property_values) {
					if (empty($updated_value)) {
						if (empty($current_value)) {
							$do_nothing = true;
						} else {
							$try_remove = true;
						}
					} else if (empty($current_value)) {
						$try_add = true;
					} else {
						$try_add = $try_remove = true;
					}
				} else {
					if (empty($updated_value)) {
						$do_nothing = true;
					} else {
						$try_remove = true;
					}

				}
				//================================================

				//проверяем не было ли добавления элемента в новый раздел
				if ($do_nothing) {
					if ($current_sections && !in_array($section_id, $current_sections)) {
						$try_add = true;
						$try_remove = false;
						$do_nothing = false;
					}
				}
				//=========================================================

				//проверяем есть ли элементы в этом же разделе с таким же значением свойства, если есть ниче не делаем

				$remove_section_value = ""; //непосредственное строковые значения (не IDшники) которые мы удалим или добавим к свойству раздела
				$add_section_value = "";

				if ($try_add) {
					$other_elements = BXHelper::getElements(
						array(),
						array('!ID' => $element_id,'IBLOCK_ID' => $iblock_id, 'SECTION_ID' => $section_id, 'PROPERTY_'.$element_prop_code => $updated_value),
						false,
						false,
						array(),
						false,
						"ID",
						true
					);
					if (empty($other_elements['RESULT'])) {
						if ($property["PROPERTY_TYPE"] == "L" || $property["PROPERTY_TYPE"] == "E") {
							$add_section_value = $enum[$updated_value]['VALUE'];
						} else {
							$add_section_value = $updated_value;
						}
					}
				}

				if ($try_remove) {
					$other_elements = BXHelper::getElements(
						array(),
						array('!ID' => $element_id,'IBLOCK_ID' => $iblock_id, 'SECTION_ID' => $section_id, 'PROPERTY_'.$element_prop_code => $current_value),
						false,
						false,
						array(),
						false,
						"ID",
						true
					);
					if (empty($other_elements['RESULT'])) {
						if ($property["PROPERTY_TYPE"] == "L" || $property["PROPERTY_TYPE"] == "E") {
							$remove_section_value = $enum[$current_value]['VALUE'];
						} else {
							$remove_section_value = $current_value;
						}
					}
				}

				if (!empty($remove_section_value) || !empty($add_section_value)) {
					$section = BXHelper::getSections(array(), array('IBLOCK_ID' => $iblock_id, 'ID' => $section_id), false, array('ID', 'NAME', $section_string_uf_prop_code), false, 'ID');
					$section = $section['RESULT'][$section_id];
					$current_sections_values = explode($delimiter, $section[$section_string_uf_prop_code]);
					$sections_values = $current_sections_values;
					if (!empty($remove_section_value)) {
						foreach ($sections_values as $k => $val) {
							if ($val == $remove_section_value) {
								unset($sections_values[$k]);
							}
						}
					}

					if (!empty($add_section_value)) {
						if (!in_array($add_section_value,$sections_values)) {
							$sections_values[] = $add_section_value;
						}
					}

					if ($sections_values != $current_sections_values) {
						$obSection = new CIBlockSection();
						$update_section_value = implode(" ",$sections_values);
						CStorage::setVar('section_aggregation_proceed');
						$obSection->Update($section_id, array($section_string_uf_prop_code => $update_section_value));
					}

				}
			}
		}
		public static function getElementLinkEnum($prop_id = null, $prop_array = false, $element_filter = array(), $use_as_text = 'NAME') {
			if (empty($prop_array)) {
				if ($prop_id) {
					$prop_array = BXHelper::getProperties(array(), array("ID" => $prop_id), array(), "ID", true);
					$prop_array = $prop_array['RESULT'][$prop_id];
				}
			}
			if ($prop_array['PROPERTY_TYPE'] == 'E') {
				$filter = array("IBLOCK_ID" => $prop_array["LINK_IBLOCK_ID"]);
				if (is_array($element_filter) && count($element_filter)) {
					unset($element_filter["IBLOCK_ID"]);
					$filter = array_merge($filter,$element_filter);
				}
				$elements = BXHelper::getElements(array(), $filter, false, false, array('ID',$use_as_text));
				$result_array = array();
				foreach ($elements['RESULT'] as $elem) {
					$result_array[$elem['ID']] = array('VALUE' => $elem[$use_as_text]);
				}
				return $result_array;
			}
			return false;
		}
		public static function getSectionPath($iblock_id, $section_id, $arSelect, $use_cache = true, $field_as_key = false) {
			$param_string = serialize(func_get_args());
			CModule::IncludeModule('iblock');
			if ($use_cache) $result = static::getCache(__FUNCTION__.$param_string);
			if (!is_array($result['RESULT'])) {
				$dbResult = CIBlockSection::GetNavChain($iblock_id, $section_id, $arSelect);
				while ($next = $dbResult->GetNext()) {
					if ($field_as_key) {
						$result['RESULT'][$next[$field_as_key]] = $next;
					} else {
						$result['RESULT'][] = $next;
					}
				}
				static::setCache(__FUNCTION__.$param_string, $result['RESULT']);
			}
			return $result;
		}
		public static function getProtectedProperty(&$object, $prop_name) {
			$reflection = new ReflectionClass($object);
			$property = $reflection->getProperty($prop_name);
			$property->setAccessible(true);
			return $property->getValue($object);
		}
		public static function setProtectedProperty(&$object, $prop_name, $value) {
			$reflection = new ReflectionClass($object);
			$property = $reflection->getProperty($prop_name);
			$property->setAccessible(true);
			$property->setValue($object, $value);
			return true;
		}

		public static function convertCodeFromName ($iblock_id) {
			if (intval($iblock_id)) {
				$elements = BXHelper::getElements(array(), array('IBLOCK_ID' => $iblock_id), false, false, array('ID','NAME'));

				$obElement = new CIBlockElement();

				foreach ($elements['RESULT'] as $element) {
					$obElement->Update($element['ID'], array('CODE' => CUtil::translit($element['NAME'], 'ru', array('max_len' => 100, 'replace_space' => '-', 'replace_other' => '-'))));
				}
			}
		}

		public static function getPropertySectionLinks($iblock_id, $arSectionId = array(), $section_depth_level = false, $look_back_depth = false, $arPropertyId = array(), $additionalFilter = array(), $use_as_key = false ,$use_cache = true) {

			$param_string = serialize(func_get_args());
			if ($use_cache) $result = static::getCache(__FUNCTION__.$param_string);
			if (!is_array($result['RESULT'])) {

				global $DB;

				$parent_table_short_names = array();
				$parent_select = array();
				$parent_join = array();
				$section_property_join_on = array();

				$look_back_diff = intval($section_depth_level) - intval($look_back_depth);

				$section_property_join_on[] = "bis.ID = bispr.SECTION_ID";

				if (intval($look_back_depth) > 0 && $look_back_diff >= 0) {
					for ($i = 1; $i < $look_back_depth; $i++) {
						$parent_table_short_names[] = "bis_".str_repeat("p",$i);
					}
					foreach ($parent_table_short_names as $key => $tb_name) {
						$parent_select[] = $tb_name.".ID as ".str_repeat("PARENT_",$key+1)."ID";
						$main_section_table_name = $key+1 == 1 ? "bis":"bis_".str_repeat("p",$key);
						$parent_join[] = "LEFT OUTER JOIN b_iblock_section ".$tb_name." ON ".$main_section_table_name.".IBLOCK_SECTION_ID = ".$tb_name.".ID";
						$section_property_join_on[] = "bispr.SECTION_ID = ".$tb_name.".ID";
					}
					if ($look_back_diff === 0) {
						$section_property_join_on[] = "bispr.SECTION_ID = 0";
					}
				}

				if (!empty($parent_select)) {
					$parent_select[] = ""; //чтобы запятую в конце поставить
				}

				$where_string = "WHERE bispr.IBLOCK_ID = $iblock_id";
				if (!empty($iblock_id) && is_array($arSectionId) && is_array($arPropertyId)) {
					foreach (array('SECTION_ID' => $arSectionId, 'PROPERTY_ID' => $arPropertyId) as $field_name => $id_array) {
						if (count($id_array)) {
							$id_filter = "(".implode(",", $id_array).")";
							$where_string .= " AND bispr.$field_name IN $id_filter";
						}
					}
					if (!empty($additionalFilter)) {
						foreach ($additionalFilter as $field => $value) {
							$where_string .= " AND bispr.$field = '$value'";
						}
					}
					$query = "SELECT bis.ID as ID, ".implode(" ,",$parent_select)." bispr.* , bip.CODE,bip.NAME, bip.PROPERTY_TYPE FROM b_iblock_section bis ".implode(" ",$parent_join)." JOIN b_iblock_section_property bispr ON ".implode(" OR ",$section_property_join_on)." JOIN b_iblock_property bip ON bip.ID = bispr.PROPERTY_ID ".$where_string." GROUP BY bispr.PROPERTY_ID, bispr.SECTION_ID";
					$dbResult = $DB->Query($query);
					while ($next = $dbResult->GetNext()) {
						if (!$use_as_key) {
							$result['RESULT'][] = $next;
						} else {
							$nkey = $next[$use_as_key];
							if (!empty($result['RESULT'][$nkey])) {
								if (empty($result['RESULT'][$nkey][0])) {
									$result['RESULT'][$nkey]= array($result['RESULT'][$nkey]);
								}
								$result['RESULT'][$nkey][] = $next;
							} else {
								$result['RESULT'][$nkey] = $next;
							}
						}
						//$result['RESULT'][] = $next;
					}
					static::setCache(__FUNCTION__.$param_string, $result['RESULT']);
				}
			}
			return $result['RESULT'];
		}

		public static function getEnumPropertyById($ID) {
			global $DB;
			$sql = "SELECT * FROM b_iblock_property_enum where ID = {$ID} limit 1";
			$result = $DB->Query($sql);
			return $result->getNext();
		}
		public static function getEnumPropertyByXMLId($PROPERTY_ID, $ID) {
			global $DB;
			$sql = "SELECT * FROM b_iblock_property_enum where PROPERTY_ID = {$PROPERTY_ID} and XML_ID = '{$ID}' limit 1";
			$result = $DB->Query($sql);
			return $result->getNext();
		}

		public static function getInvalidEnumPropertyValues () {
			global $DB;
			$sql = "SELECT biep.*,bipe.ID as ENUM_ID, bipe.XML_ID  FROM b_iblock_element_property biep LEFT OUTER JOIN b_iblock_property_enum bipe ON bipe.ID = biep.VALUE_ENUM WHERE VALUE_ENUM IS NOT NULL AND bipe.ID IS NULL";
			return $DB->Query($sql);
		}

		public static function getSectionPropertyLinksWithCount ($iblock_id, $section_ids, $property_ids) {
			global $DB;
			$sql = "SELECT bispr.SECTION_ID, bispr.PROPERTY_ID, bip.PROPERTY_TYPE, COUNT(DISTINCT biep.ID) as VALUE_CNT FROM b_iblock_section bis  JOIN b_iblock_section_property bispr ON bis.ID = bispr.SECTION_ID JOIN b_iblock_property bip ON bip.ID = bispr.PROPERTY_ID JOIN b_iblock_element bie ON bie.IBLOCK_SECTION_ID = bispr.SECTION_ID JOIN b_iblock_element_property biep ON biep.IBLOCK_PROPERTY_ID = bispr.PROPERTY_ID AND biep.IBLOCK_ELEMENT_ID = bie.ID";
			$where = " WHERE bispr.IBLOCK_ID = $iblock_id";
			if (is_array($section_ids) && count($section_ids)) {
				$where .= " AND bispr.SECTION_ID IN (".implode(",", $section_ids).")";
			}
			if (is_array($property_ids) && count($property_ids)) {
				$where .= " AND bispr.PROPERTY_ID IN (".implode(",", $property_ids).")";
			}

			$sql .= $where." GROUP BY bispr.PROPERTY_ID, bispr.SECTION_ID";
			return $DB->Query($sql);
		}

		public static function getSectionPropertyValues ($iblock_id, $section_ids, $property_ids) {
			global $DB;
			$sql = "SELECT * FROM b_iblock_section_property bispr JOIN b_iblock_element_property biep ON bispr.PROPERTY_ID = biep.IBLOCK_PROPERTY_ID ";
			$where = " WHERE bispr.IBLOCK_ID = $iblock_id";
			if (is_array($section_ids) && count($section_ids)) {
				$where .= " AND bispr.SECTION_ID IN (".implode(",", $section_ids).")";
			}
			if (is_array($property_ids) && count($property_ids)) {
				$where .= " AND bispr.PROPERTY_ID IN (".implode(",", $property_ids).")";
			}

			$sql .= $where;
			return $DB->Query($sql);
		}

		public static function pull_array_field ($array, $field) {
			$result = array();
			if (!empty($field)) {
				foreach ($array as $arr) {
					if (isset($arr[$field])) {
						$result[] = $arr[$field];
					}
				}
				return $result;
			}
			return false;
		}

		public static function group_array ($array, $field) {
			$result = array();
			if (!empty($field)) {
				foreach ($array as $arr) {
					$nkey = trim($arr[$field]);
					$result[$nkey][] = $arr;
				}
				return $result;
			}
			return false;
		}

		public static function getPropertySectionLinks_v1 ($iblock_id, $arSectionId = array(), $arPropertyId = array(), $use_cache = true) {
			global $DB;
			$param_string = serialize(func_get_args());
			if ($use_cache) $result = static::getCache(__FUNCTION__.$param_string);
			$b_iblock_property_table_short_name = $bip = "bip";
			$b_iblock_property_table_name  = "b_iblock_property";

			$b_iblock_property_section_table_short_name = $bisp = "bisp";
			$b_iblock_property_section_table_name  = "b_iblock_section_property";
			if (!is_array($result['RESULT'])) {
				if (!empty($iblock_id) && is_array($arSectionId) && is_array($arPropertyId)) {
					$where_string = "WHERE $bisp.IBLOCK_ID = $iblock_id";
					foreach (array('SECTION_ID' => $arSectionId, 'PROPERTY_ID' => $arPropertyId) as $field_name => $id_array) {
						if (count($id_array)) {
							$id_filter = "(".implode(",", $id_array).")";
							$where_string .= " AND $bisp.$field_name IN $id_filter";
						}
					}
					$query = "SELECT * FROM $b_iblock_property_table_name $bip JOIN $b_iblock_property_section_table_name $bisp ON $bisp.PROPERTY_ID = $bip.ID ".$where_string." ORDER BY SMART_FILTER DESC";
					$dbResult = $DB->Query($query);
					while ($next = $dbResult->GetNext()) {
						$result['RESULT'][] = $next;
					}
				}
				static::setCache(__FUNCTION__.$param_string, $result['RESULT']);
			}
			return $result['RESULT'];
		}
		public static function mb_str_split($str)
		{
			preg_match_all('/.{1}/uis', $str, $out);
			return $out[0];
		}

		public static function string_intersect ($string_1, $string_2) {
			$array_1 = static::mb_str_split($string_1);
			$array_2 = static::mb_str_split($string_2);
			$result_array = array_intersect_assoc($array_1, $array_2);
			return implode("",$result_array);
		}

		public static function convert_name_to_code ($iblock_id) {
			$elements = BXHelper::getElements(array(), array('IBLOCK_ID' => $iblock_id), false, false, array(), false, 'ID');
			$obElement = new CIBlockElement();

			foreach ($elements['RESULT'] as $element) {
				$obElement->Update($element['ID'], array('CODE' => CUtil::translit($element['NAME'], 'ru', array('max_len' => 100, 'replace_space' => '-', 'replace_other' => '-'))));
			}
		}

		public static function calculateDicountPrice ($arItem, $price_id ,$price_code, $site_id, $currency_format_code = false) {
			CModule::IncludeModule('sale');
			CModule::IncludeModule('catalog');
			$obDiscount = new CCatalogDiscount();
			$obProduct = new CCatalogProduct();
			global $USER;
			$arDiscounts = $obDiscount->GetDiscount(
				$arItem["ID"],
				$arItem["IBLOCK_ID"],
				array($price_id) //IDшник цены,
				,
				$USER->GetUserGroupArray(),
				"N",
				$site_id,
				array()
			);
			if (!empty($arDiscounts)) {
				$discountPrice = $obProduct->CountPriceWithDiscount(
					$arItem["CATALOG_PRICE_".$price_id],
					$arItem["CATALOG_CURRENCY_".$price_id],
					$arDiscounts
				);
			} else {
				$discountPrice = $arItem["CATALOG_PRICE_".$price_id];
			}
			if ($currency_format_code) {
				$discountPrice = CurrencyFormat($discountPrice, $currency_format_code);
			} else {
				$discountPrice = number_format($discountPrice, 0, ".", " ");
			}
			return $discountPrice;
		}

		public static function getStores ($arOrder, $arFilter, $arGroup, $arNavParams, $arSelect, $field_as_key = false, $use_cache = true) {
			CModule::IncludeModule('catalog');
			$param_string = serialize(func_get_args());
			if ($use_cache) $result = static::getCache(__FUNCTION__.$param_string);
			if (empty($result['RESULT'])) {
				$dbResult  = CCatalogStore::GetList($arOrder, $arFilter, $arGroup, $arNavParams, $arSelect);
				while ($next = $dbResult->GetNext()) {
					if ($field_as_key) {
						$result['RESULT'][$next[$field_as_key]] = $next;
					} else {
						$result['RESULT'][] = $next;
					}
				}
				static::setCache(__FUNCTION__.$param_string, $result['RESULT']);
			}
			return $result['RESULT'];
		}
		public static function getIblockAdminLink ($iblock_id, $element_id, $iblock_type) {
			return "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=$iblock_id&type=$iblock_type&ID=$element_id&lang=ru&find_section_section=0&WF=Y";
		}
		public static function getFormFields($form_id, $arOrder, $arFilter, $field_as_key = false, $use_cache = true) {
			CModule::IncludeModule('form');
			$param_string = serialize(func_get_args());
			if ($use_cache) $result = static::getCache(__FUNCTION__.$param_string);
			if (empty($result['RESULT'])) {
				$by = key($arOrder);
				$order = current($arOrder);
				$obForm = new CFormField();
				$dbResult = $obForm->GetList(
					$form_id,
					"N",
					$by,
					$order,
					$arFilter
				);
				while ($next = $dbResult->GetNext()) {
					if ($field_as_key) {
						$result['RESULT'][$next[$field_as_key]] = $next;
					} else {
						$result['RESULT'][] = $next;
					}
				}
				static::setCache(__FUNCTION__.$param_string, $result['RESULT']);
			}
			return $result['RESULT'];
		}

		public static function getFormAnswers($question_id, $arOrder, $arFilter, $field_as_key = false, $use_cache = true) {
			CModule::IncludeModule('form');
			$param_string = serialize(func_get_args());
			if ($use_cache) $result = static::getCache(__FUNCTION__.$param_string);
			if (empty($result['RESULT'])) {
				$by = key($arOrder);
				$order = current($arOrder);
				$obForm = new CFormAnswer();
				$dbResult = $obForm->GetList(
					$question_id,
					$by,
					$order,
					$arFilter
				);
				while ($next = $dbResult->GetNext()) {

					if ($field_as_key) {
						$result['RESULT'][$next[$field_as_key]] = $next;
					} else {
						$result['RESULT'][] = $next;
					}
				}
				static::setCache(__FUNCTION__.$param_string, $result['RESULT']);
			}
			return $result['RESULT'];
		}

	}

	BXHelper::Init();
//}

function number($n, $titles) {
  $cases = array(2, 0, 1, 1, 1, 2);
  return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}
//Array('тетрадь', 'тетради', 'тетрадей')
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("MyClass", "OnAfterIBlockElementAddHandler"));

class MyClass
{
	function OnAfterIBlockElementAddHandler(&$arFields)
	{
		if ($arFields["ID"] > 0 && $arFields['ACTIVE'] == 'N' && $arFields['IBLOCK_ID'] == '15') {

			$arEventFields = array(
				'ID'      => $arFields['ID'],
				'NAME'    => $arFields['NAME'],
				'COMPANY' => $arFields['PROPERTY_VALUES'][85],
				'EMAIL'   => $arFields['PROPERTY_VALUES'][88],
				/*'COMPANY' => $arFields['PROPERTY_VALUES'][85]['n0']['VALUE'],
				'EMAIL'   => $arFields['PROPERTY_VALUES'][88]['n0']['VALUE'],*/
				'COMMENT' => $arFields['PREVIEW_TEXT'],
			);
			CEvent::Send("NEW_COMMENT", 's1', $arEventFields);
		}
	}
}
include_once($_SERVER['DOCUMENT_ROOT'] . '/local/templates/hogart/prop_checkbox.php');
AddEventHandler("iblock", "OnIBlockPropertyBuildList", Array("CIBlockPropertyCheckbox", "GetUserTypeDescription"));

/*
AddEventHandler("main", "OnAdminTabControlBegin", "MyOnAdminTabControlBegin");
function MyOnAdminTabControlBegin(&$form)
{
	if($GLOBALS["APPLICATION"]->GetCurPage() == "/bitrix/admin/subscr_edit.php")
	{
		print_r($form);
		$form->tabs[] = array("DIV" => "my_edit", "TAB" => "Дополнительно", "ICON"=>"main_user_edit", "TITLE"=>"Дополнительные параметры", "CONTENT"=>
			'<tr valign="top">
				<td>Телефон:</td>
				<td>
					<input type="text" name="MY_HEADERS[]" value="" size="30"><br>
				</td>
			</tr>'
		);
	}
}
*/
?>
<?
/*AddEventHandler('form', 'onAfterResultAdd', Array("ShareLink","onAfterResultAddHandler"));
class ShareLink
{

	function onAfterResultAddHandler($WEB_FORM_ID, $RESULT_ID){

		if ($WEB_FORM_ID == 10)
		{
			$arAnswer = CFormResult::GetDataByID($RESULT_ID,array("EMAIL","QUESTION"),
				$arResult,$arAnswer2);
//            $name = $arAnswer['FIO']['0']['USER_TEXT'];
//            $phone = $arAnswer['PHONE']['0']['USER_TEXT'];
			$text = $arAnswer['QUESTION']['0']['USER_TEXT'];
			$email = $arAnswer['E-MAIL']['0']['USER_TEXT'];
			$arSend = array("EMAIL" => $email, "QUESTION" => $text);
			CEvent::Send('FORM_SEND_ARTICLE_s1',SITE_ID,$arSend);
		}

	}
}*/
?>