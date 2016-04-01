<?

CModule::IncludeModule('highloadblock');
use \Bitrix\Highloadblock\CustomHighloadBlockTable as HLT;

class CHLEntity {
	//
	// Хелпер добавления HL-сущности
	// @return object
	//
	public function Add($arEntityParams, $bAddBaseFields = true) {
		$obResult = null;
		$obResult = \Bitrix\Highloadblock\HighloadBlockTable::Add($arEntityParams);
		if($bAddBaseFields) {
			if($obResult->IsSuccess()) {
				$iEntityId = $obResult->GetId();
				self::AddBaseFields($iEntityId);
			}
		}
		return $obResult;
	}

	//
	// Возвращает ID HL-сущности по имени
	// @return integer
	//
	public static function GetEntityIdByName($sEntityName, $bRefreshCache = false) {
		$iReturnId = 0;
		$sEntityName = strtoupper(trim($sEntityName));
		$sCacheKey = $sEntityName.'|';
		$sCacheEntity = 'hle_id_by_name';
		if(!$bRefreshCache && CStaticCache::IsSetCache($sCacheKey, $sCacheEntity)) {
			$iReturnId = CStaticCache::GetCacheValue($sCacheKey, $sCacheEntity);
		} else {
			$arHLEnititesList = self::GetEntitiesList($bRefreshCache);
			if($arHLEnititesList[$sEntityName]) {
				$iReturnId = isset($arHLEnititesList[$sEntityName]) ? reset($arHLEnititesList[$sEntityName]) : 0;
			}
			CStaticCache::SetCacheValue($sCacheKey, $iReturnId, $sCacheEntity, METHODS_DEFAULT_CACHE_ENTITY_SIZE);
		}
		return $iReturnId;
	}

	//
	// Возвращает массив описания HL-сущности по ее ID
	// @return array
	//
	public function GetEntityInfoById($iEntityId, $bRefreshCache = false) {
		$arReturn = array();
		$iEntityId = intval($iEntityId);
		if($iEntityId < 1) {
			return $arReturn;
		}
		$sCacheKey = $iEntityId.'|';
		$sCacheEntity = 'hle_info_by_id';
		if(!$bRefreshCache && CStaticCache::IsSetCache($sCacheKey, $sCacheEntity)) {
			$arReturn = CStaticCache::GetCacheValue($sCacheKey, $sCacheEntity);
		} else {
			$arHLEnititesList = self::GetEntitiesList($bRefreshCache);
			foreach($arHLEnititesList as $arItem) {
				if($arItem['ID'] == $iEntityId) {
					$arReturn = $arItem;
					break;
				}
			}
			CStaticCache::SetCacheValue($sCacheKey, $arReturn, $sCacheEntity, METHODS_DEFAULT_CACHE_ENTITY_SIZE);
		}
		return $arReturn;
	}

	//
	// Возвращает массив описания HL-сущности по ее названию
	// @return array
	//
	public function GetEntityInfoByName($sEntityName, $bRefreshCache = false) {
		$iEntityId = self::GetEntityIdByName($sEntityName, $bRefreshCache);
		return self::GetEntityInfoById($iEntityId, $bRefreshCache);
	}

	//
	// Возвращает объект HL-сущности по ее ID
	// !!! Статический кэш в будущем нужно будет убрать (когда оптимизируют CompileEntity) !!!
	// @return object
	//
	public function GetEntityById($iEntityId, $bRefreshCache = false) {
		$obEntity = null;
		$arEntityInfo = self::GetEntityInfoById($iEntityId, $bRefreshCache);
		if($arEntityInfo) {
			$sCacheKey = $iEntityId.'|';
			$sCacheEntity = 'hle_by_id';
			if(!$bRefreshCache && CStaticCache::IsSetCache($sCacheKey, $sCacheEntity)) {
				$obEntity = CStaticCache::GetCacheValue($sCacheKey, $sCacheEntity);
			} else {
				$obEntity = HLT::CompileEntity($arEntityInfo);
				CStaticCache::SetCacheValue($sCacheKey, $obEntity, $sCacheEntity, METHODS_DEFAULT_CACHE_ENTITY_SIZE);
			}
		}
		return $obEntity;
	}

	//
	// Возвращает объект HL-сущности по ее названию
	// @return object
	//
	public static function GetEntityByName($sEntityName, $bRefreshCache = false) {
		$iEntityId = self::GetEntityIdByName($sEntityName, $bRefreshCache);
		return self::GetEntityById($iEntityId, $bRefreshCache);
	}

	//
	// Возвращает массив описания полей HL-сущности по ее ID
	// @return array
	//
	public function GetEntityFieldsById($iEntityId) {
		$arReturn = array();
		$iEntityId = intval($iEntityId);
		if($iEntityId < 1) {
			return $arReturn;
		}
		// в $GLOBALS['USER_FIELD_MANAGER'] используется встроенный кэш
		$arReturn = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('HLBLOCK_'.$iEntityId);
		return $arReturn;
	}

	//
	// Возвращает массив описания полей HL-сущности по ее названию
	// @return array
	//
	public function GetEntityFieldsByName($sEntityName) {
		$iEntityId = self::GetEntityIdByName($sEntityName);
		return self::GetEntityFieldsById($iEntityId);
	}

	//
	// Добавление нового поля HL-сущности
	// @return array
	//
	public function AddEntityField($iEntityId, $arUFParams) {
		$iUserFieldId = 0;
		$iEntityId = intval($iEntityId);
		if($iEntityId < 1 || empty($arUFParams) || !is_array($arUFParams)) {
			return $iUserFieldId;
		}
		$arUFParams['ENTITY_ID'] = 'HLBLOCK_'.$iEntityId;
		$obUserTypeEntity = new CUserTypeEntity();
		$iUserFieldId = $obUserTypeEntity->Add($arUFParams);
		return $iUserFieldId;
	}

	//
	// Возвращает массив всех HL-сущностей
	// (для корректной работы требуются обработчики событий сброса кэша при набора сущностей)
	// @return array
	//
	public function GetEntitiesList($bRefreshCache = false) {
		$arReturn = array();
		// дополнительный параметр для ID кэша
		$sCacheAddParam = '';
		// идентификатор кэша (обязательный и уникальный параметр)
		$sCacheId = 'hle_list';
		// массив тегов для тегированного кэша (если пустой, то тегированный кэш не будет использован)
		// использовать тегированный кэш не будем, т.к. будет выполняться постоянный сброс при любом действии над записями сущностей
		$arAddCacheTags = array();
		// путь для сохранения кэша
		$sCachePath = '/'.__CLASS__.'/hle_list/';
		// сохранять ли значения дополнительно в виртуальном кэше
		$bUseStaticCache = true;
		// максимальное количество записей виртуального кэша
		$iStaticCacheEntityMaxSize = METHODS_DEFAULT_CACHE_ENTITY_SIZE;
		// соберем в массив идентификационные параметры кэша
		$arCacheIdParams = array(__FUNCTION__, $sCacheId, $arAddCacheTags, $sCacheAddParam);

		$obExtCache = new CExtCache($arCacheIdParams, $sCachePath, $arAddCacheTags, $bUseStaticCache, $iStaticCacheEntityMaxSize);
		if(!$bRefreshCache && $obExtCache->InitCache()) {
			$arReturn = $obExtCache->GetVars();
		} else {
			// открываем кэшируемый участок
			$obExtCache->StartDataCache();
			if(CModule::IncludeModule('highloadblock')) {
				$dbItems = HLT::GetList(
					array(
						'select' => array('ID', 'NAME', 'TABLE_NAME')
					)
				);
				while($arItem = $dbItems->Fetch()) {
					$arReturn[strtoupper($arItem['NAME'])] = $arItem;
				}
			}

			// закрываем кэшируемый участок
			$obExtCache->EndDataCache($arReturn);
		}
		return $arReturn;
	}

	//
	// Добавляет базовые поля для HL-сущности 
	// По-уму должен быть на событии HighloadBlockOnAfterAdd, но во время срабатывания не все таблицы еще созданы
	//
	public static function AddBaseFields($iEntityId, $bChangeTypes = true) {
		$arAddFields = array(
			// Дата модификации
			'UF_TIMESTAMP_X' => array(
				'USER_TYPE_ID' => 'datetime',
				'NAME' => 'Дата модификации',
				'EDIT_IN_LIST' => 'N',
			),
			// Код пользователя, сделавшего последнее изменение
			'UF_MODIFIED_BY' => array(
				'USER_TYPE_ID' => 'integer',
				'NAME' => 'MODIFIED_BY',
				'EDIT_IN_LIST' => 'N',
			),
			// Ссылка
			'UF_XML_ID' => array(
				'USER_TYPE_ID' => 'string',
				'NAME' => 'Ссылка'
			),
		);

		$sTmpEntityTableName = '';
		if($bChangeTypes) {
			$arTmp = self::GetEntityInfoById($iEntityId);
			if($arTmp) {
				$sTmpEntityTableName = $arTmp['TABLE_NAME'];
			}
		}

		foreach($arAddFields as $sTmpFieldName => $arAddParams) {
			$arTmpParams = array_merge(
				array(
					'FIELD_NAME' => $sTmpFieldName,
					'EDIT_FORM_LABEL' => '',
					'USER_TYPE_ID' => 'string',
						'MULTIPLE' => 'N',
						'SHOW_FILTER' => 'I',
						'SHOW_IN_LIST' => 'Y',
						'EDIT_IN_LIST' => 'Y',
						'IS_SEARCHABLE' => 'N',
						'SETTINGS' => array(
							'SIZE' => '50'
						),
						'LIST_COLUMN_LABEL' => '',
						'LIST_FILTER_LABEL' => '',
						'ERROR_MESSAGE' => '',
						'HELP_MESSAGE' => ''
				),
				$arAddParams
			);
			if(!is_array($arTmpParams['EDIT_FORM_LABEL']) && $arAddParams['NAME']) {
				$arTmpParams['EDIT_FORM_LABEL'] = array('ru' => $arAddParams['NAME'].' ['.$sTmpFieldName.']');
				$arTmpParams['LIST_COLUMN_LABEL'] = $arTmpParams['EDIT_FORM_LABEL'];
				$arTmpParams['LIST_FILTER_LABEL'] = $arTmpParams['EDIT_FORM_LABEL'];
			}
			$iTmpUserFieldId = self::AddEntityField($iEntityId, $arTmpParams);

			// костыли
			if($bChangeTypes && strlen($sTmpEntityTableName) && $iTmpUserFieldId && $sTmpFieldName == 'UF_XML_ID') {
				// изменим тип поля UF_XML_ID с text на varchar(255)
				$sTmpQuery = 'ALTER TABLE '.$sTmpEntityTableName.' CHANGE UF_XML_ID UF_XML_ID VARCHAR(255)';
				$GLOBALS['DB']->Query($sTmpQuery);
				// добавим по полю идекс
				$sTmpQuery = 'ALTER TABLE '.$sTmpEntityTableName.' ADD INDEX (UF_XML_ID)';
				$GLOBALS['DB']->Query($sTmpQuery);
			}
		}
	}


	//
	// Возвращает массив вариантов значений всех свойств типа "Список" заданной сущности
	//
	public static function GetEnumPropsData($iEntityId, $bRefreshCache = false) {
		$arReturn = array();
		$iEntityId = intval($iEntityId);
		if($iEntityId < 1) {
			return $arReturn;
		}
		$sStaticCacheKey = $iEntityId.'|';
		$sCacheEntity = 'hl_enum_props';
		if(!$bRefreshCache && CStaticCache::IsSetCache($sStaticCacheKey, $sCacheEntity)) {
			$arReturn = CStaticCache::GetCacheValue($sStaticCacheKey, $sCacheEntity);
		} else {
			$arEntityFields = self::GetEntityFieldsById($iEntityId);
			if(empty($arEntityFields)) {
				return $arReturn;
			}
			foreach($arEntityFields as $arField) {
				$arRels = array();
				if($arField['USER_TYPE']['BASE_TYPE'] == 'enum') {
					$arRels[$arField['ID']] = $arField['FIELD_NAME'];
				}
				if($arRels) {
					$dbItems = CUserFieldEnum::GetList(
						array(
							'SORT' => 'ASC'
						),
						array(
							'USER_FIELD_ID' => array_keys($arRels)
						)
					);
					while($arItem = $dbItems->Fetch()) {
						$arReturn[$arRels[$arItem['USER_FIELD_ID']]][$arItem['ID']] = $arItem;
					}
				}
			}
			CStaticCache::SetCacheValue($sStaticCacheKey, $arReturn, $sCacheEntity, METHODS_DEFAULT_CACHE_ENTITY_SIZE);
		}
		return $arReturn;
	}

	public static function GetEnumPropsDataByEntityName($sEntityName, $bRefreshCache = false) {
		$iEntityId = self::GetEntityIdByName($sEntityName, $bRefreshCache);
		return self::GetEnumPropsData($iEntityId, $bRefreshCache);
	}

}
