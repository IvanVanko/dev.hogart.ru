<?
/**
 * 
 * Расширенное кэширование
 * @author Sergey Leshchenko, 2012
 * @updated: 17.04.2014
 * 
 */

if(!defined('METHODS_DEFAULT_CACHE_TIME')) {
	define('METHODS_DEFAULT_CACHE_TIME', 3600);
}

class CExtCache {
	private $iDefaultCacheTime = METHODS_DEFAULT_CACHE_TIME;
	private $obCache = null;
	private $arCacheIdParams = array();
	private $sCachePath = '/extcache';
	private $bTagCacheEnabled = false;
	private $bTagCacheStarted = false;
	private $arCacheTags = array();
	private $bUseStaticCache = true;
	private $bCacheInited = false;
	private $bOnStartDataCacheInit = false;
	private $bCacheRealInited = false;
	private $bDataCacheStarted = false;
	private $_iCacheTime = 0;
	private $_sCacheType = 'A';
	private $sCacheId = '';
	private $sStaticCacheEntity = '';
	private $bInitCacheReturn = false;
	private $iStaticCacheEntityMaxSize = 0;
	private $bCachePath2StaticCacheAdded = false;
	private $iTTL = 0;
	// Важно! Нельзя включать этот флаг для кэширования шаблонов или когда что-либо выводится в кэшируемой области!
	private $bForceStartDataCache = true;

	public function __construct($arCacheIdParams = array(), $sCachePath = '', $arAddCacheTags = array(), $bUseStaticCache = true, $iStaticCacheEntityMaxSize = 0, $bForceStartDataCache = true) {
		// путь для сохранения кэша
		$sCachePath = ltrim($sCachePath, '/');// для memcache
		$this->sCachePath = rtrim(trim($sCachePath), '/');
		// теги для тегированного кэша
		$this->AddCacheTags($arAddCacheTags);
		// параметры идентификации кэша
		$this->arCacheIdParams = $arCacheIdParams;
		// сохранять ли значения дополнительно в виртуальном кэше
		$this->bUseStaticCache = $bUseStaticCache ? true : false;
		$this->iStaticCacheEntityMaxSize = intval($iStaticCacheEntityMaxSize);

		// флаг принудительного включения кэширования методом StartDataCache (т.е. StartDataCache будет всегда true)
		// Важно! Нельзя включать этот флаг для кэширования шаблонов или когда что-либо выводится в кэшируемой области - возможно дублирование выводимого контента!
		if($bForceStartDataCache) {
			$this->EnableForceCaching();
		} else {
			$this->DisableForceCaching();
		}
	}

	//
	// функция-хелпер, инициализация механизма кэширования
	//
	public function InitCache($iCacheTime = 0, $sCacheType = 'A') {
		$this->bCacheInited = true;
		$this->_iCacheTime = $iCacheTime;
		$this->_sCacheType = $sCacheType;
		if(!$this->bOnStartDataCacheInit) {
			// если выбран режим использования виртуального (статического) кэша, то первым делом проверим, нет ли в нем готовых данных
			if($this->IsStaticCacheEnabled()) {
				$sStaticCacheKey = $this->GetCacheId();
				$sStaticCacheEntity = $this->GetStaticCacheEntity();
				if(CStaticCache::IsSetCache($sStaticCacheKey, $sStaticCacheEntity)) {
					// данные уже есть, сообщаем об этом
					return true;
				}
			}
		}

		return $this->_initCache();
	}

	private function _initCache() {
		if($this->bCacheInited && !$this->bCacheRealInited) {
			$this->bCacheRealInited = true;

			$iCacheTime = $this->_iCacheTime;
			$sCacheType = $this->_sCacheType;

			$iCacheTime = $this->GetCacheTime($iCacheTime, $sCacheType);
			$sCacheId = $this->GetCacheId();
			$sCachePath = $this->GetCachePath();
			$obCache = $this->GetCacheObject();
			$this->iTTL = $iCacheTime;
			$this->bInitCacheReturn = $obCache->InitCache($iCacheTime, $sCacheId, $sCachePath);
		}
		return $this->bInitCacheReturn;
	}

	//
	// функция-хелпер, возвращает результат кэширования
	//
	public function GetVars() {
		// если выбран режим виртуального кэша, то первым делом проверим его, нет ли в нем сохраненных данных
		if($this->IsStaticCacheEnabled()) {
			$sStaticCacheKey = $this->GetCacheId();
			$sStaticCacheEntity = $this->GetStaticCacheEntity();
			if(CStaticCache::IsSetCache($sStaticCacheKey, $sStaticCacheEntity)) {
				// данные уже есть, сразу отдаем их
				return CStaticCache::GetCacheValue($sStaticCacheKey, $sStaticCacheEntity);
			}
		}
		$obCache = $this->GetCacheObject();
		$mCacheData = $obCache->GetVars();
		if($this->IsStaticCacheEnabled()) {
			CStaticCache::SetCacheValue($sStaticCacheKey, $mCacheData, $sStaticCacheEntity, $this->iStaticCacheEntityMaxSize);
			$this->AddCachePath2StaticCacheEntity($sStaticCacheEntity);
		}
		return $mCacheData;
	}

	//
	// функция-хелпер, открывает кэшируемый участок 
	//
	public function StartDataCache($iCacheTime = 0, $sCacheType = 'A') {
		if($this->IsDataCacheStarted()) {
			return false;
		}

		if(!$this->bCacheInited) {
			$this->bOnStartDataCacheInit = true;
			$this->InitCache($iCacheTime, $sCacheType);
		}

		$bCacheStarted = false;

		if($this->iTTL > 0) {
			// включим флаг для статик кэша
			$this->SetDataCacheStarted();

			$obCache = $this->GetCacheObject();

			$bForceCaching = $this->GetForceCachingState();
			//
			// Внимание! Костыль! 
			// убрать, когда появится нормальная возможность перезаписи кэша
			// (см. Cache::ShouldClearCache() + Cache::InitCache())
			//
			// >>>
			if($bForceCaching) {
				$sSessClearCacheOrig = null;
				if(isset($_SESSION['SESS_CLEAR_CACHE'])) {
					$sSessClearCacheOrig = $_SESSION['SESS_CLEAR_CACHE'];
				}
				$_SESSION['SESS_CLEAR_CACHE'] = 'Y';
			}
			// <<<

			$bCacheStarted = $obCache->StartDataCache();

			// продолжение костыля >>>
			if($bForceCaching) {
				if(is_null($sSessClearCacheOrig)) {
					unset($_SESSION['SESS_CLEAR_CACHE']);
				} else {
					$_SESSION['SESS_CLEAR_CACHE'] = $sSessClearCacheOrig;
				}
			}
			// подстраховка, если фокус с $_SESSION['SESS_CLEAR_CACHE'] не пройдет
			if($bForceCaching && !$bCacheStarted) {
				// очистим кэш и пробуем еще раз стартануть
				$this->CleanCache();
				// !!! здесь кроется причина дублирования выводимого контента (если кэш используется для этих целей) !!!
				$bCacheStarted = $obCache->StartDataCache();
			}
			// <<<

			if($bCacheStarted) {
				// если разрешен тегированный кэш, то запустим его буферизацию
				if($this->IsTagCacheEnabled()) {
					$sCachePath = $this->GetCachePath();
					if(strlen($sCachePath)) {
						if(defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER'])) {
							$GLOBALS['CACHE_MANAGER']->StartTagCache($sCachePath);
							$this->SetTagCacheStarted();
						}
					}
				}
			}
		}
		return $bCacheStarted;
	}

	//
	// функция-хелпер, закрывает кэшируемый участок
	// @params mixed $mCacheData - данные для сохранения в кэше
	// @params array $arAddCacheTags - массив тегов для тегированного кэша
	//
	public function EndDataCache($mCacheData, $arAddCacheTags = array()) {
		if(!$this->IsDataCacheStarted()) {
			return false;
		}
		$this->SetDataCacheEnded();

		if($this->IsTagCacheStarted()) {
			if(!empty($arAddCacheTags)) {
				$this->AddCacheTags($arAddCacheTags);
			}
			$arCacheTags = $this->GetCacheTags();
			if(!empty($arCacheTags)) {
				foreach($arCacheTags as $sCacheTag) {
					$GLOBALS['CACHE_MANAGER']->RegisterTag($sCacheTag);
				}
			}
			$GLOBALS['CACHE_MANAGER']->EndTagCache();
			$this->SetTagCacheEnded();
		}

		if($this->IsStaticCacheEnabled()) {
			$sStaticCacheKey = $this->GetCacheId();
			$sStaticCacheEntity = $this->GetStaticCacheEntity();
			CStaticCache::SetCacheValue($sStaticCacheKey, $mCacheData, $sStaticCacheEntity, $this->iStaticCacheEntityMaxSize);
			$this->AddCachePath2StaticCacheEntity($sStaticCacheEntity);
		}

		$obCache = $this->GetCacheObject();
		$obCache->EndDataCache($mCacheData);

		return true;
	}

	//
	// функция-хелпер, прерывает кэширование
	//
	public function AbortDataCache() {
		if(!$this->IsDataCacheStarted()) {
			return false;
		}
		$this->SetDataCacheEnded();

		if($this->IsTagCacheStarted()) {
			$GLOBALS['CACHE_MANAGER']->AbortTagCache();
			$this->SetTagCacheEnded();
		}

		$obCache = $this->GetCacheObject();
		$obCache->AbortDataCache();

		return true;
	}

	public function GetCacheObject() {
		if(!$this->obCache) {
			$this->obCache = new CPHPCache();
		}
		return $this->obCache;
	}

	public function IsStaticCacheEnabled() {
		return $this->bUseStaticCache;
	}

	public function SetDefaultCacheTime($iValue) {
		$iValue = intval($iValue);
		$this->iDefaultCacheTime = $iValue > 0 ? $iValue : 0;
	}

	public function GetDefaultCacheTime() {
		return $this->iDefaultCacheTime;
	}

	public function GetCachePath() {
		return $this->sCachePath;
	}

	// возвращает время жизни кэша с учетом типа кэширования
	public function GetCacheTime($iCacheTime = 0, $sCacheType = 'A') {
		$iCacheTime = intval($iCacheTime);
		$iCacheTime = $iCacheTime > 0 ? $iCacheTime : $this->GetDefaultCacheTime();
		$sCacheType = $sCacheType != 'Y' && $sCacheType != 'N' ? 'A' : $sCacheType;
		if($sCacheType == 'N' || ($sCacheType == 'A' && COption::GetOptionString('main', 'component_cache_on', 'Y') == 'N')) {
			$iCacheTime = 0;
		}
		return $iCacheTime;
	}

	public function GetCacheId() {
		if(!$this->sCacheId) {
			$arCacheParams = array(
				$this->sCachePath,
				$this->arCacheIdParams,
				// время жизни кэша не должно подмешиваться в кэш-идентификатор
				//$this->_iCacheTime,
				//$this->_sCacheType,
			);
			$this->sCacheId = md5(serialize($arCacheParams));
		}
		return $this->sCacheId;
	}

	public function GetStaticCacheEntity() {
		if(!$this->sStaticCacheEntity) {
			$arCacheParams = array(
				$this->sCachePath
			);
			$this->sStaticCacheEntity = md5(serialize($arCacheParams));
		}
		return $this->sStaticCacheEntity;
	}

	//
	// Добавление тегов для управляемого кэша
	//
	public function AddCacheTags($arAddCacheTags = array()) {
		if(is_array($arAddCacheTags) && !empty($arAddCacheTags)) {
			$arAddCacheTags = array_unique($arAddCacheTags);
			$this->arCacheTags = array_merge($arAddCacheTags, $this->arCacheTags);
			if(!empty($this->arCacheTags)) {
				$this->bTagCacheEnabled = true;
			}
		}
	}

	public function GetCacheTags() {
		return $this->arCacheTags;
	}

	private function SetTagCacheStarted() {
		$this->bTagCacheStarted = true;
	}

	private function SetTagCacheEnded() {
		$this->bTagCacheStarted = false;
	}

	public function IsTagCacheEnabled() {
		return $this->bTagCacheEnabled;
	}

	public function IsTagCacheStarted() {
		return $this->bTagCacheStarted;
	}

	private function SetDataCacheStarted() {
		$this->bDataCacheStarted = true;
	}

	private function SetDataCacheEnded() {
		$this->bDataCacheStarted = false;
	}

	public function IsDataCacheStarted() {
		return $this->bDataCacheStarted;
	}
	private function AddCachePath2StaticCacheEntity($sStaticCacheEntity = '') {
		if(!$this->bCachePath2StaticCacheAdded) {
			$sStaticCacheEntity = $sStaticCacheEntity ? $sStaticCacheEntity : $this->GetStaticCacheEntity();
			CStaticCache::AddCachePath2Entity($this->GetCachePath(), $sStaticCacheEntity);
			$this->bCachePath2StaticCacheAdded = true;
		}
	}

	//
	// Сброс кэша
	//
	public function CleanCache() {
		$sCacheId = $this->GetCacheId();
		$sCachePath = $this->GetCachePath();
		$obCache = $this->GetCacheObject();
		$obCache->Clean($sCacheId, $sCachePath);
	}

	//
	// Отключает принудительный старт кэширования методом StartDataCache
	//
	public function DisableForceCaching() {
		$this->bForceStartDataCache = false;
	}

	//
	// Включает принудительный старт кэширования методом StartDataCache
	//
	public function EnableForceCaching() {
		$this->bForceStartDataCache = true;
	}

	//
	// Возвращает состояние флага принудительного старта кэширования методом StartDataCache
	//
	public function GetForceCachingState() {
		return $this->bForceStartDataCache;
	}
}
