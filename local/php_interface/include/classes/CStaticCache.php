<?
/**
 * 
 * Виртуальный кэш (статические переменные)
 * @author Sergey Leshchenko, 2012-2013
 * @updated: 22.11.2013
 */

class CStaticCache {
	private static $arStaticCache = array();
	private static $arCachePath2Entity = array();

	public static function FlushAllCache() {
		self::$arStaticCache = array();
	}

	public static function FlushEntityCache($sStaticCacheEntity = '-') {
		if(isset(self::$arStaticCache[$sStaticCacheEntity])) {
			unset(self::$arStaticCache[$sStaticCacheEntity]);
		}
	}

	public static function FlushCache($sStaticCacheKey, $sStaticCacheEntity = '-') {
		if(isset(self::$arStaticCache[$sStaticCacheEntity][$sStaticCacheKey])) {
			unset(self::$arStaticCache[$sStaticCacheEntity][$sStaticCacheKey]);
		}
	}

	public static function InitCache($sStaticCacheKey, $sStaticCacheEntity = '-') {
		$bReturn = self::IsSetCache($sStaticCacheKey, $sStaticCacheEntity);
		if(!$bReturn) {
			self::SetCacheValue($sStaticCacheKey, '', $sStaticCacheEntity);
		}
		return $bReturn;
	}

	public static function IsSetCache($sStaticCacheKey, $sStaticCacheEntity = '-') {
		return isset(self::$arStaticCache[$sStaticCacheEntity][$sStaticCacheKey]);
	}

	public static function GetCacheValue($sStaticCacheKey, $sStaticCacheEntity = '-') {
		//return strlen($sStaticCacheKey) && isset(self::$arStaticCache[$sStaticCacheKey]) ? self::$arStaticCache[$sStaticCacheKey] : false;
		// ! именно так и нужно возвращать результат !
		return self::$arStaticCache[$sStaticCacheEntity][$sStaticCacheKey];
	}

	public static function SetCacheValue($sStaticCacheKey, $mValue, $sStaticCacheEntity = '-', $iMaxEntityCacheSize = 0) {
		$iMaxEntityCacheSize = intval($iMaxEntityCacheSize);
		self::$arStaticCache[$sStaticCacheEntity][$sStaticCacheKey] = $mValue;
		if($iMaxEntityCacheSize > 0 && count(self::$arStaticCache[$sStaticCacheEntity]) > $iMaxEntityCacheSize) {
			// $sStaticCacheKey - теоретически может быть числовым значением, поэтому использовать array_shift() нельзя
			//array_shift(self::$arStaticCache[$sStaticCacheEntity]);
			self::$arStaticCache[$sStaticCacheEntity] = array_slice(self::$arStaticCache[$sStaticCacheEntity], 1, null, true);
		}
	}

	public static function AddCachePath2Entity($sCachePath, $sStaticCacheEntity) {
		if(is_scalar($sCachePath) && is_scalar($sStaticCacheEntity)) {
			self::$arCachePath2Entity[$sCachePath][] = $sStaticCacheEntity;
			return true;
		}
		return false;
	}

	public static function GetCachePath2Entity($sCachePath = false) {
		if($sCachePath === false) {
			return array_unique(self::$arCachePath2Entity);
		} elseif(is_scalar($sCachePath) && isset(self::$arCachePath2Entity[$sCachePath])) {
			return array_unique(self::$arCachePath2Entity[$sCachePath]);
		}
		return array();
	}

	public static function ClearCachePath2Entity($sCachePath = false) {
		if($sCachePath === false) {
			self::$arCachePath2Entity = array();
		} elseif(is_scalar($sCachePath)) {
			self::$arCachePath2Entity[$sCachePath] = array();
		}
	}

	public static function FlushEntityCacheByCachePath($sCachePath) {
		$bReturn = false;
		if(is_scalar($sCachePath)) {
			$arEntities = self::GetCachePath2Entity($sCachePath);
			if($arEntities) {
				foreach($arEntities as $sStaticCacheEntity) {
					self::FlushEntityCache($sStaticCacheEntity);
				}
				$bReturn = true;
			}
		}
		return $bReturn;
	}


}
