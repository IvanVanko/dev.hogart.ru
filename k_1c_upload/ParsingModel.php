<?php

require_once('csv.php');
require_once('config.php');

ini_set('max_execution_time', 0);

class ParsingModel {
    //Приватные переменные для хранения ссылки на подключение
    public $client;
    public $csv;
    public $arr_params = array();
    public $answer = false;
    public $cache = false;
    public $create_dir = false;
    public $mongo_db_name;
    public $import_property_types = array(
        "0" => "STR",
        "1" => "INT",
        "2" => "BOOL"
    );

    private static $topSections = [
        'otoplenie' => 9,
        'ventilyatsiya' => 2,
        'santekhnika' => 1,
    ];

    const CATALOG_IBLOCK_ID = 1;
    const BRAND_IBLOCK_ID = 2;
    const COLLECTIONS_IBLOCK_ID = 22;
    const DOCUMENTATION_IBLOCK_ID = 10;

    private $sectionsCache = [];
    private $sectionsCodeCache = [];

    private $techDocCache = [];
    private $techDocCodeCache = [];

    private $collectionCache = [];
    private $collectionCodeCache = [];

    private $tmp = array();

    /**
     * Подключиться к Soap-серверу
     */
    function __construct($create_dir) {
        global $DB;
        try {
            //Подключаемся к soap-серверу
            ini_set("soap.wsdl_cache_enabled", 0);
            $this->arr_params['type_id'] = '';
            $obConfig = new SoapLocalConfig();
            $this->client = new SoapClient(

                $obConfig->getUrl(),
                $obConfig->getConfig()
            );
            //создаём ассоциативный массив с названием ключа, совпадающим
            //с названием параметра операции веб-сервиса и передаём значение id
            $this->arr_params['ID_Portal'] = 'HG';
        }
        catch(SoapFault $e) {
            //Если произошла ошибка выводим сообщение
            echo '<div class="error">';
            echo $e->getMessage();
            echo '</div>';
        }
        $this->csv = new csv($create_dir);
        $this->mongo_db_name = "hogart";

        $DB->Query("SET NAMES 'utf8'");
        $DB->Query('SET collation_connection = "utf8_unicode_ci"');
    }

    public static function updateElementPropertyValuesToList($arPropertyValues) {
        global $DB;
        $db_name = "b_iblock_element_property";
        $db_sname = "biep";
    }

    /**
     * Soap сервер доступен?
     * @return bool
     */
    function isReadySoap() {
        return $this->client ? true : false;
    }

    function clearMongoValues() {
        $mongo = new MongoClient();
        $mongo_database = $mongo->selectDB($this->mongo_db_name);
        if(is_object($mongo_database)) {
            $property_values_collection = $mongo_database->selectCollection('property_values');
            if(is_object($property_values_collection)) {
                $property_values_collection->remove(array());
                return true;
            }
        }
        return false;
    }

    function getMongoPropertyValue($property_value_id) {
        $mongo = new MongoClient();
        $return_value = false;
        $mongo_database = $mongo->selectDB($this->mongo_db_name);
        if(is_object($mongo_database)) {
            $property_values_collection = $mongo_database->selectCollection('property_values');
            if(is_object($property_values_collection)) {
                $result = $property_values_collection->find(array('value_id' => $property_value_id))->getNext();
                $return_value = $result['value'];
            }
        }
        return $return_value;
    }

    /**
     * Показать доступные функции
     */
    function showFunctionSoap() {
        try {
            echo '<div class="suc">Доступные функции soap<pre>';
            print_r($this->getFunctionSoap());
            echo '</pre></div>';
        }
        catch(Exception $e) {
            echo "<p class='error'><b>Получить данные из 1С не удалось: ".$e->getMessage()."</b></p>";
        }
    }

    /**
     * Получить массив доступных функций
     * @return array|bool
     */
    function getFunctionSoap() {
        try {
            $func_list = $this->client->__getFunctions();

            return $func_list;
        }
        catch(Exception $e) {
            return false;
        }
    }

    function QueryAnswer($method, $param) {
        return $this->client->__soapCall($method, $param);
    }

    /**
     * выполняем все операции по брендам
     * @return bool|mixed
     */
    function initBrands() {
        $BLOCK_ID = self::BRAND_IBLOCK_ID;
        $ost = $this->GetResultFunction("BrandGet");
        $answer = array();
        $answer['ID_Portal'] = 'HG';
        $answer['StringBrands'] = array();
        //Объект элемента в модели битрикса
        $el = new CIBlockElement;
        echo "<div class='suc'>";

        if(is_object($ost->return->brand)) {
            $ost->return->brand = array($ost->return->brand);
        }

        foreach($ost->return->brand as $key => $value) {
            $arLoadProductArray = Array(
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID" => $BLOCK_ID,
                "XML_ID" => $value->id,
                "PROPERTY_VALUES" => array(),
                "NAME" => $value->title,
                "ACTIVE" => ($value->visibility) ? "Y" : "N"
            );
            //проверяем наличие такого элемента
            $rsItems = CIBlockElement::GetList(array(), array(
                'IBLOCK_ID' => $BLOCK_ID,
                "=XML_ID" => $value->id,
            ), false, false, array('ID'));
            //Если элемент уже существует, обновляем его или удаляем, при условии что флаг установлен
            if($arItem = $rsItems->GetNext()) {
                //Удаляем из битрикса элемент
                if($value->deletion_mark == true) {
                    //Проверяем права на удаление
                    /*if (CIBlock::GetPermission($BLOCK_ID) >= 'W') {*/
                    //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                    $arItem['ACTIVE'] = 'N';
                    if($res = $el->Update($arItem['ID'], $arItem)) {
                        echo 'Запись деактивирована - '.$arItem['ID'];
                    }
                    else {
                        echo '<p class="error">При деактивации элемента произошла ошибка ['.$arItem['ID'].']<br>
                                    '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'</p>';
                    }
                    /*}*/
                }
                else {
                    //Если удалять не нужно, то обновляем и выводим сообщение
                    if($res = $el->Update($arItem['ID'], $arLoadProductArray)) {
                        echo "Запись обновлена: ".$value->name." - ".$value->title."<br />";
                    }
                    else {
                        echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                        $this->csv->saveLog(array('Ошибка обновления бренда',
                                                  $arItem['ID'],
                                                  $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                    }
                }
            }
            else {
                $arLoadProductArray["CODE"] = $value->id;

                if($BRAND_ID = $el->Add($arLoadProductArray)) {
                    echo "Добавлена: ".$value->name." - ".$value->title."<br />";
                    $dbResult = CIBlockElement::GetList(Array("SORT" => "ASC"), Array('IBLOCK_ID' => 2), false, false,
                        Array('ID', 'NAME'));
                    $tempelement = array();
                    while($new = $dbResult->GetNext()) {
                        $tempelement[] = $new;
                    }
                    $has_codes = array();
                    foreach($tempelement as $element) {
                        $elementup = new CIBlockElement;
                        $code = CUtil::translit($element['NAME'], 'ru',
                            array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => ''));
                        if(array_key_exists($code, $has_codes)) {
                            $code = $code.(++$has_codes[$code]);
                        }
                        else {
                            ++$has_codes[$code];
                        }
                        if($elementup->Update($element['ID'], array('CODE' => $code))) {
                            echo 'OK';
                        }
                    }
                }
                else {
                    echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                    $this->csv->saveLog(array('Ошибка добавления бренда',
                                              $arItem['ID'],
                                              $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                }
            }

            $answer['StringBrands'][] = $value->id;
        }

        echo "</div>";
        $g = false;

        if(count($answer['StringBrands']) > 0) {
            $g = true;
        }
        $answer['StringBrands'] = implode(";", $answer['StringBrands']);

        //Коллекции
        $this->initCollection($ost);

        if($this->answer) {
            echo '<i>Ответ на сервер отправлен</i>';
            $ost = $this->client->__soapCall("BrandAnswer", array('parameters' => $answer));
            if($g) {
                //                echo "<meta http-equiv=\"refresh\" content=\"2; url=".$_SERVER["REQUEST_URI"]."\">";
                //$this->initBreands();
            }
        }
        if(($ost->return == true)) {
            echo "<div class='suc'>Бренды загружены</div>";
            echo "<div class='info'>В количестве: ".count($ost->return->brand)."</div>";
        }
        else {
            echo "<div class='error'>Бренды не загружены</div>";
            $this->csv->saveLog(array('Бренды не загружены', 'Не получен подходящий ответ от сервера'));
        }
    }

    /**
     * Получить данные из удаленного 1с
     *
     * @param $name
     *
     * @return bool|mixed
     */
    function GetResultFunction($name) {
        if(!$this->cache) {
            try {
                $ost = $this->client->__soapCall($name, array('parameters' => $this->arr_params));

                if($name == 'BrandGet' and $this->csv->create_dir) {
                    $this->csv->saveBrands($ost->return->brand);
                    $this->csv->resetQueryCsv();
                    $this->csv->saveSet($ost->return->set);
                }

                if($name == 'CategoryGet' and $this->csv->create_dir) {
                    $this->csv->saveCategory($ost->return->category);
                    $this->csv->resetQueryCsv();
                    $this->csv->savePropname($ost->return->propname);
                    $this->csv->resetQueryCsv();
                    $this->csv->savePropname($ost->return->prop);
                }

                if($name == 'TehDocGet' and $this->csv->create_dir) {
                    $this->csv->saveTehdoc($ost->return->tehdoc);
                    $this->csv->resetQueryCsv();
                }

                if($name == 'CostsGet' and $this->csv->create_dir) {
                    $this->csv->savePrice($ost->return->cost);
                    $this->csv->resetQueryCsv();
                }

                if($name == 'StockGet' and $this->csv->create_dir) {
                    $this->csv->saveWarehouse($ost->return->warehouse);
                    $this->csv->resetQueryCsv();
                    $this->csv->saveItem_amount($ost->return->item_amount);
                    $this->csv->resetQueryCsv();
                }

                if($name == 'ItemsGet' and $this->csv->create_dir) {
                    $this->csv->saveUnit_messure_catalog($ost->return->unit_messure_catalog);
                    $this->csv->resetQueryCsv();
                    $this->csv->saveUnit_messure($ost->return->unit_messure);
                    $this->csv->resetQueryCsv();
                    $this->csv->saveItem($ost->return->item);
                }

                return $ost;
            }
            catch(Exception $e) {
                echo "<p class='error'><b>Получить данные из 1С не удалось: ".$e->getMessage()."</b></p>";
                $this->csv->saveLog(array('Ошибка при подключении к soap', $e->getMessage()));

                return false;
            }
        }
    }


    const DETAIL_PICTURE_1C_TYPE_ID = 'c6764e87-3703-496f-9bf7-a48e052967fd';

    private $classTrans = array(
        '3ac566b4-e78f-11e4-9045-003048b99ee9' => 18,//Прайс-лист
        '4e1de85e-fdf0-11e4-9045-003048b99ee9' => 10,//Каталог
        '4e1de85f-fdf0-11e4-9045-003048b99ee9' => 7,//Буклеты
        '4e1de860-fdf0-11e4-9045-003048b99ee9' => 11,//Сертификаты
        '4e1de863-fdf0-11e4-9045-003048b99ee9' => 8,//Инструкция по монтажу
        '4e1de865-fdf0-11e4-9045-003048b99ee9' => 9,//Гарантийный талон/Тех. паспорт
        '4e1de864-fdf0-11e4-9045-003048b99ee9' => 97719,//Инструкция по эксплуатации
        'default' => 10
    );

    function initTehDoc() {
        $this->csv->saveLog(['techDoc import start '.date('d.M.Y H:i:s')]);
        $ost = $this->GetResultFunction("TehDocGet");

        $answer = array();
        $answer['ID_Portal'] = 'HG';
        $answer['StringTehDoc'] = array();
        $error = array();
        $answer['type_id'] = $_GET['d'];
        $el = new CIBlockElement;

        if(is_object($ost->return->tehdoc)) {
            $ost->return->tehdoc = array($ost->return->tehdoc);
        }

        echo "<div class='suc'>";

        $error = array();
        $error_count = 0;

        foreach($ost->return->tehdoc as $key_file => $file) {
            $file_obj = CFile::MakeFileArray(trim($_SERVER['DOCUMENT_ROOT'].'/1c-upload/'.$file->adress));
            $name = $file->name;
            $fileXmlID = $file->id;
            $del = $file->deletion_mark;
            $type_index = $file->type_id;
            $access_level = $file->access_level;
            $actual = $file->actual;
            $answer['StringTehDoc'][] = $fileXmlID;
            if(empty($access_level)) {
                $access_level = 1;
            }


            $lines = $file->lines;
            if(is_object($lines)
            ) {
                $lines = array($lines);
            }

            if($del){
                $this->deleteFile($file);
                continue;
            }

            // проверка на случай, если с какого-то хера детальную картинку переносят в другой тип документов
            // тогда мы должны её удалить у товара и добавить в ИБ с документами
            $this->checkifFileDetailImageExist($file);

            $array_brands = array();
            $array_product = array();
            $array_product_doc = array();

            foreach($lines as $line) {
                $elementXmlID = $line->obj_id;
                $type = $line->obj_type;

                $iblock_id = false;
                if($type == 'brands') {
                    $iblock_id = self::BRAND_IBLOCK_ID;
                }
                elseif($type == 'categorys') {
                    $iblock_id = self::CATALOG_IBLOCK_ID;
                }
                elseif($type == 'nomenclature') {
                    $iblock_id = self::CATALOG_IBLOCK_ID;
                }
                else {
                    $this->csv->saveLog(array('Передан неизвестный тип документации', $type, __LINE__));
                    continue;
                }

                $elementsPhotos = [];
                $elementID = false;
                $bQ = CIBlockElement::GetList(array(), array(
                    'IBLOCK_ID' => $iblock_id,
                    "=XML_ID" => $elementXmlID,
                ), false, false, array('ID'));

                if($arItem = $bQ->GetNext()) {
                    $elementID = $arItem['ID'];
                    $photosRes = CIBlockElement::GetProperty($iblock_id, $elementID, [], ['CODE' => 'photos']);
                    while($obPhotosRes = $photosRes->GetNext()){
                        $elementsPhotos[$elementID] = $obPhotosRes['VALUE'];
                    }
                }

                $elementsPhotos = array_filter($elementsPhotos);

                if($type == 'brands') {
                    if(!$elementID) {
                        echo 'Не найден бренд для документации '.$fileXmlID."<br>";
                        if($_GET['P'] == 'Y') {
                            $this->csv->saveLog(array('Не найден бренд для документации', $elementXmlID, __LINE__));
                            $error[] = $fileXmlID;
                        }
                        if($_GET['V'] == 'Y') {
                            $error_count++;
                            echo $error_count.')Не найден бренд для документации: '.$elementXmlID.'<br>';
                        }
                    }
                    else {
                        $array_brands[] = array('id' => $elementXmlID, 'id_b' => $elementID);
                    }
                }
                if($type == 'categorys') {

                    if(!$elementID) {
                        echo 'Не найден товар для связки с документацией '.$fileXmlID."<br>";
                        if($_GET['P'] == 'Y') {
                            $this->csv->saveLog(array('Не найден товар для связки с документацией', $elementXmlID, __LINE__));
                            $error[] = $fileXmlID;
                        }
                        if($_GET['V'] == 'Y') {
                            $error_count++;
                            echo $error_count.')Не найден товар для связки с документацией: '.$elementXmlID.'<br>';
                        }
                    }
                    else {
                        $array_product_doc[] = array('id' => $elementXmlID, 'id_b' => $elementID);
                    }
                }
                if($type == 'nomenclature') {
                    if(!$elementID) {
                        echo 'Нет товара '.$fileXmlID."<br>";
                        if($_GET['P'] == 'Y') {
                            $this->csv->saveLog(array('Не найден товар', $elementXmlID, __LINE__));
                            $error[] = $fileXmlID;
                        }
                        if($_GET['V'] == 'Y') {
                            $error_count++;
                            $this->csv->saveLog(array('Не найден товар', $elementXmlID, __LINE__));
                            echo $error_count.')Не найден товар: '.$elementXmlID.'<br>';
                        }
                    }
                    else {
                        $array_product[] = array('id' => $elementXmlID, 'id_b' => $elementID);
                    }

                }
            }

            if(!isset($this->classTrans[$type_index])){
                $this->csv->saveLog(array('Не найден тип товара для id', $fileXmlID, $type_index, __LINE__));
            }

            if(count($array_brands) and $_GET['V'] != 'Y' and $_GET['P'] != 'Y') {
                $param = array();
                foreach($array_brands as $brand) {
                    $param[] = array('VALUE' => $brand['id_b']);
                }
                $this->addDoc($fileXmlID, $file_obj, $name, $param, $del, $this->classTrans[$type_index],
                    $access_level, $actual ? "Y" : "N");
            }

            if(count($array_product) and $_GET['V'] != 'Y' and $_GET['P'] != 'Y') {
                foreach($array_product as $product) {
                    if($product['id_b']) {
                        if($this->classTrans[$type_index]) {
                            $res = CIBlockElement::GetProperty(self::CATALOG_IBLOCK_ID,
                                $product['id_b'], array(), array("CODE" => "BRAND"));
                            $arFile = array();
                            while($brand_product = $res->GetNext()) {
                                $arFile[] = array(
                                    'VALUE' => $brand_product['VALUE']
                                );
                            }
                            $id_doc_dynamic = $this->addDoc($fileXmlID, $file_obj, $name, $arFile, $del,
                                $this->classTrans[$type_index], $access_level);

                            $id_doc_dynamic = array(array('VALUE' => $id_doc_dynamic));
                            if($el->SetPropertyValueCode($product['id_b'], "DOCS", $id_doc_dynamic)) {
                                echo 'Документация добавлена и связана с товаром: '.$product['id_b'];
                                unlink(trim($_SERVER['DOCUMENT_ROOT'].'/1c-upload/'.$file->adress));
                            }
                            else {
                                $this->csv->saveLog(array(
                                    'Не удалось связать документацию и товар',
                                    $fileXmlID,
                                    $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__
                                ));
                                echo 'Не удалось связать документацию и товар: ['.$fileXmlID.'] '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                            }
                        }
                        else {
                            $arFile = array();
                            $arFile[] = array(
                                'VALUE' => $file_obj,
                                "DESCRIPTION" => $name,
                            );

                            if($type_index == self::DETAIL_PICTURE_1C_TYPE_ID) {
                                if($del){
                                    $file_obj['del'] = 'del';
                                }
                                $el->Update($product['id_b'], array(
                                    "DETAIL_PICTURE" => $file_obj
                                ), false, true, true);
                                if($this->answer) {
                                    unlink(trim($_SERVER['DOCUMENT_ROOT'].'/1c-upload/'.$file->adress));
                                }
                            }
                            else {
                                if(isset($elementsPhotos[$product['id_b']])){
                                    foreach($elementsPhotos[$product['id_b']] as $photo){
                                        $fileArray = CFile::GetByID($photo);
                                        if($fileArray['ORIGINAL_NAME'] == $file->adress && $del){
                                            $arFile['del'] = 'Y';
                                            echo "Фото добавлено: ".$name."<br />";
                                        }
                                    }
                                }

                                if($el->SetPropertyValueCode($product['id_b'], "photos", $arFile)) {
                                    if($this->answer) {
                                        unlink(trim($_SERVER['DOCUMENT_ROOT'].'/1c-upload/'.$file->adress));
                                    }
                                    echo "Фото добавлено: ".$name."<br />";
                                }
                                else {
                                    $this->csv->saveLog(array('фото не добавилось',
                                                              $fileXmlID,
                                                              $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                                    echo 'Error product img: ['.$fileXmlID.'] '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                                }
                            }
                        }
                    }
                }
            }

            if(count($array_product_doc) and $_GET['V'] != 'Y' and $_GET['P'] != 'Y') {
                $item = $this->findEl(array('IBLOCK_ID' => self::DOCUMENTATION_IBLOCK_ID, "=XML_ID" => $elementXmlID));
                if(!empty($item['ID'])) {
                    $arFile = array();
                    $arFile[] = array('VALUE' => $item['ID']);
                    foreach($array_product_doc as $product) {
                        if($el->SetPropertyValueCode($product['id_b'], "DOCS", $arFile)) {
                            echo "Фото добавлено: ".$name."<br />";
                        }
                        else {
                            $this->csv->saveLog(array(
                                'Документация не связалась с продуктом',
                                $fileXmlID,
                                $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__
                            ));
                            echo 'Документация не связалась с продуктом: ['.$fileXmlID.'] '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                        }
                    }
                }
                else {
                    $this->csv->saveLog(array('Документация для связки не найдена',
                                              $fileXmlID,
                                              $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                    echo 'Документация для связки не найдена: ['.$fileXmlID.'] '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                }

            }

        }

        if($_GET['P'] == 'Y') {
            $answer['StringTehDoc'] = implode(";", $error);
            $ost = $this->client->__soapCall("TehDocAnswer", array('parameters' => $answer));
        }

        if($_GET['V'] != 'Y' and $_GET['P'] != 'Y') {
            $count_list = count($answer['StringTehDoc']);
            $answer['StringTehDoc'] = implode(";", $answer['StringTehDoc']);
            if($this->answer) {
                echo $answer['StringTehDoc'];
                $ost = $this->client->__soapCall("TehDocAnswer", array('parameters' => $answer));
                if($count_list) {
//                    echo "<meta http-equiv=\"refresh\" content=\"2; url=".$_SERVER["REQUEST_URI"]."\">";
                    //$this->_initTehDoc();
                }
                if(($ost->return == true)) {
                    echo "<div class='suc'>Документации загружены</div>";
                    echo "<div class='info'>В количестве: ".$count_list."</div>";
                }
                else {
                    $this->csv->saveLog(array('Ответ сервера по документации не прошел'));
                    echo "<div class='error'>Документации не загружены</div>";
                }

            }

        }

        echo '</div>';
        $this->csv->saveLog(['techDoc import end '.date('d.M.Y H:i:s')]);
    }

    function addDoc($xmlId, $file, $name, $brand, $del, $type, $access_level = 1, $active = "Y") {

        $arLoadArray = array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => self::DOCUMENTATION_IBLOCK_ID,
            "XML_ID" => $xmlId,
            "PROPERTY_VALUES" => array(
                'type' => $type,
                'file' => $file,
                'brand' => $brand,
                'access_level' => $access_level
            ),
            "NAME" => $name,
            "ACTIVE" => $active,
        );

        $el = new CIBlockElement;
        $item = $this->findEl(array('IBLOCK_ID' => self::DOCUMENTATION_IBLOCK_ID, "=XML_ID" => $xmlId));
        $id_r = false;
        if($del) {
            $this->csv->saveLog(array('Документация удалена(сделана неактивной)',
                                      $xmlId,
                                      $item['ID'], __LINE__));
            $el->Update($item['ID'], ['ACTIVE' => 'N']);
        }
        else {
            if($item) {
                //                if($arLoadArray)
                //                CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
                if($el->Update($item['ID'], $arLoadArray)) {
                    echo 'Документация обновлена: ['.$item['ID'].'] '.$xmlId.'<br>';
                    $id_r = $item['ID'];
                    if($this->answer) {
                        unlink($file['tmp_name']);
                    }
                }
                else {
                    $this->csv->saveLog(array('Документация не обновлена',
                                              $xmlId,
                                              $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                    echo 'Документация не обновлена: ['.$xmlId.'] '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                }
            }
            else {
                if($doc_b = $el->Add($arLoadArray)) {
                    echo 'Документация добавлена: ['.$doc_b.'] '.$xmlId.'<br>';
                    $id_r = $doc_b;
                    if($this->answer) {
                        unlink($file['tmp_name']);
                    }
                }
                else {
                    $this->csv->saveLog(array('Документация не добавлена',
                                              $xmlId,
                                              $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                    echo 'Документация не добавлена: ['.$xmlId.'] '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                }
            }
        }

        return $id_r;
    }

    /**
     * выполняем все операции по документации
     */

    function findEl($filter) {
        $obj = false;
        $bQ = CIBlockElement::GetList(array(), $filter, false, false, array());
        if($arItem = $bQ->GetNext()) {
            $obj = $arItem;
        }

        return $obj;
    }

    /**
     * Загружаем склады
     */
    function initWarehouse() {
        $ost = $this->GetResultFunction('StockGet');
        $answer = array();
        $answer['ID_Portal'] = 'HG';
        $el = new CIBlockElement;
        $st = new CCatalogStore;
        echo "<div class='suc'>";

        if(is_object($ost->return->warehouse)) {
            $ost->return->warehouse = array($ost->return->warehouse);
        }
        if(is_object($ost->return->item_amount)) {
            $ost->return->item_amount = array($ost->return->item_amount);
        }

        foreach($ost->return->warehouse as $key => $stock) {
            //            mb_convert_variables('utf-8', 'windows-1251', $stock->warehouse_name);
            //            mb_convert_variables('utf-8', 'windows-1251', $stock->warehouse_address);
            $arFields = Array(
                "TITLE" => $stock->warehouse_name,
                "ACTIVE" => "Y",
                "ADDRESS" => $stock->warehouse_address,
                "DESCRIPTION" => "",
                "IMAGE_ID" => false,
                "GPS_N" => "",
                "GPS_S" => "",
                "PHONE" => "",
                "SCHEDULE" => "",
                "XML_ID" => $stock->warehouse_id
            );
            $answer['StringStock'][] = $stock->warehouse_id;
            $stockInBitrix = $st->GetList(array(), array('XML_ID' => $stock->warehouse_id));
            $stockInBitrix_t = $st->GetList(array(), array('XML_ID' => $stock->warehouse_id.'_t'));
            if($stockInBitrix = $stockInBitrix->GetNext()) {
                $stockInBitrix_t = $stockInBitrix_t->GetNext();
                if($st->Update($stockInBitrix['ID'], $arFields)) {
                    $arFields['XML_ID'] .= '_t';
                    $arFields['TITLE'] .= ' Stock in transit';
                    $st->Update($stockInBitrix_t['ID'], $arFields);
                    global $USER_FIELD_MANAGER;
                    $USER_FIELD_MANAGER->Update('CAT_STORE', $stockInBitrix_t['ID'], array(
                        'UF_TRANSIT' => 1
                    ));
                    echo "Склад обновлен: [".$stockInBitrix['ID']."]<br>";
                }
                else {
                    $this->csv->saveLog(array('Склад не обновлен',
                                              $stockInBitrix['ID'],
                                              $st->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                    echo 'Склад не обновлен : ['.$stockInBitrix['ID'].']'.$st->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                }
            }
            else {
                if($stockInBitrix = $st->Add($arFields)) {
                    $arFields['XML_ID'] .= '_t';
                    $arFields['TITLE'] .= ' Stock in transit';
                    $arFields['UF_TRANSIT'] = '1';
                    $new_id_reans = $st->Add($arFields);
                    global $USER_FIELD_MANAGER;
                    $USER_FIELD_MANAGER->Update('CAT_STORE', $new_id_reans, array(
                        'UF_TRANSIT' => 1
                    ));
                    echo "Склад добавлен: [".$stockInBitrix."]<br>";
                }
                else {
                    $this->csv->saveLog(array('Склад не добавлен',
                                              $stock->warehouse_id,
                                              $st->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                    echo 'Склад не добавлен : ['.$stock->warehouse_id.']'.$st->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                }
            }
        }

        $st2pr = new CCatalogStoreProduct;
        foreach($ost->return->item_amount as $product_s) {
            $answer['StringStock'][] = $product_s->item_id;
            $productInBitrix = $el->GetList(array(), array('IBLOCK_ID' => 1, 'XML_ID' => $product_s->item_id));
            if($productInBitrix = $productInBitrix->GetNext()) {
                $fil = array(
                    'warehouse' => array("VALUE" => $product_s->warehouse ? '1' : '0'),
                    'days_till_receive' => array("VALUE" => $product_s->days_till_receive),
                );
                $el->SetPropertyValuesEx($productInBitrix['ID'], 1, $fil);
                echo $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br><br>';
                if(is_object($product_s->item_amount_line)) {//подправляем массив со остатками, если нам пришел 1 склад. Ошибка в выгрузке.
                    $product_s->item_amount_line = array($product_s->item_amount_line);
                }
                foreach($product_s->item_amount_line as $skey => $stock) {
                    $stockInBitrix = $st->GetList(array(), array('XML_ID' => $stock->warehouse_id));
                    $stockInBitrix_t = $st->GetList(array(), array('XML_ID' => $stock->warehouse_id.'_t'));
                    if($stockInBitrix = $stockInBitrix->GetNext()) {
                        $stockInBitrix_t = $stockInBitrix_t->GetNext();
                        $id_p = $productInBitrix['ID'];
                        $id_s = $stockInBitrix['ID'];
                        $id_st = $stockInBitrix_t['ID'];

                        $items = $st2pr->GetList(array(), array('PRODUCT_ID' => $id_p, 'STORE_ID' => $id_s));
                        $items_t = $st2pr->GetList(array(), array('PRODUCT_ID' => $id_p, 'STORE_ID' => $id_st));

                        $arFields = Array(
                            "PRODUCT_ID" => $id_p,
                            "STORE_ID" => $id_s,
                            "AMOUNT" => $stock->stock,
                        );
                        $arFields_t = Array(
                            "PRODUCT_ID" => $id_p,
                            "STORE_ID" => $id_st,
                            "AMOUNT" => $stock->in_transit,
                        );

                        if($items = $items->GetNext()) {
                            $items_t = $items_t->GetNext();
                            if($st2pr->Update($items['ID'], $arFields)) {
                                $st2pr->Update($items_t['ID'], $arFields_t);

                                echo "Количество товара на складе обновлено [".$product_s->item_id."] [".$stock->warehouse_id."]: [".$items['ID']."]<br>";
                            }
                            else {
                                $this->csv->saveLog(array(
                                    'Количество товара на складе не обновлено',
                                    $items['ID'],
                                    $st2pr->LAST_ERROR." ".__LINE__." ".__FUNCTION__
                                ));
                                echo 'Количество товара на складе не обновлено : ['.$items['ID'].'] ['.$product_s->item_id.'] ['.$stock->warehouse_id.']'.$st2pr->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                            }
                        }
                        else {
                            if($id_sps = $st2pr->Add($arFields)) {
                                $st2pr->Add($arFields_t);
                                echo "Количество товара на складе добавлено [".$product_s->item_id."] [".$stock->warehouse_id."]: [".$id_sps."]<br>";
                            }
                            else {
                                $this->csv->saveLog(array(
                                    'Количество товара на складе не добавлено',
                                    $st2pr->LAST_ERROR." ".__LINE__." ".__FUNCTION__
                                ));
                                echo "Количество товара на складе не добавлено : [".$product_s->item_id."] [".$stock->warehouse_id."] ".$st2pr->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                            }
                        }
                    }
                    else {
                        $this->csv->saveLog(array('Склад не найден',
                                                  $stock->warehouse_id,
                                                  $st->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                        echo "Склад не найден [".$product_s->item_id."] [".$stock->warehouse_id."] : [".$stock->warehouse_id.']'.$st->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                    }
                }
            }
            else {
                $this->csv->saveLog(array('Товар не найден',
                                          $product_s->item_id,
                                          $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__));
                echo "Товар не найден :[".$product_s->item_id."] [".$stock->warehouse_id."] [".$product_s->item_id.']'.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
            }
        }

        if($this->answer) {
            $answer['StringStock'] = implode(";", $answer['StringStock']);
            $ost = $this->client->__soapCall("StockAnswer", array('parameters' => $answer));
        }

        echo "<div class='suc'>Склады загружены</div>";

        echo "</div>";
    }

    function initSectionsCache() {
        $arFilter = Array(
            'IBLOCK_ID' => self::CATALOG_IBLOCK_ID
        );
        $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, ['XML_ID', 'ID', 'CODE']);
        while($item = $rsItems->Fetch()) {
            $this->sectionsCache[$item['XML_ID']] = $item['ID'];
            $this->sectionsCodeCache[$item['XML_ID']] = $item['CODE'];
        }
    }

    function initTechDocCache($force = false) {
        if (!empty($this->techDocCache) && !$force) return;

        $arFilter = Array(
            'IBLOCK_ID' => self::DOCUMENTATION_IBLOCK_ID
        );

        $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, ['XML_ID', 'ID', 'CODE']);
        while($item = $rsItems->Fetch()) {
            $this->techDocCache[$item['XML_ID']] = $item['ID'];
            $this->techDocCodeCache[$item['XML_ID']] = $item['CODE'];
        }
    }

    function initCollectionCache($force = false) {
        if (!empty($this->collectionCache) && !$force) return;

        $arFilter = Array(
            'IBLOCK_ID' => self::COLLECTIONS_IBLOCK_ID
        );

        $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, ['XML_ID', 'ID', 'CODE']);
        while($item = $rsItems->Fetch()) {
            $this->collectionCache[$item['XML_ID']] = $item['ID'];
            $this->collectionCodeCache[$item['XML_ID']] = $item['CODE'];
        }
    }

    /**
     * Загружаем разделы
     */
    function initCategory() {
        $ost = $this->GetResultFunction('CategoryGet');
        $this->initSectionsCache();

        define("CACHED_b_iblock_bucket_size", 3600);
        $answer = array();
        $answer['ID_Portal'] = 'HG';
        $answer['StringCategory'] = array();
        //Объект элемента в модели битрикса
        $el = new CIBlockSection;
        echo "<div class='suc'>";

        if(is_object($ost->return->Category)) {
            $ost->return->Category = array($ost->return->Category);
        }

        foreach($ost->return->Category as $key => $value) {
            $parent_id = false;
            if(!empty($value->parent_id)) {

                //Если элемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if(isset($this->sectionsCache[$value->parent_id])) {
                    $parent_id = $this->sectionsCache[$value->parent_id];
                }
                else {
                    $parent_id = false;
                }
            }

            $iblock_section_id = $parent_id
                ?
                $parent_id
                : (
                $value->branch == 0
                    ?
                    self::$topSections['otoplenie']
                    : (
                $value->branch == 1 ? self::$topSections['ventilyatsiya'] : self::$topSections['santekhnika']
                )
                );

            $arLoadProductArray = Array(
                "IBLOCK_SECTION_ID" => $iblock_section_id,
                "XML_ID" => $value->id,
                "NAME" => $value->name,
                "ACTIVE" => "Y",
                "IBLOCK_CODE" => $value->name,
                "IBLOCK_ID" => self::CATALOG_IBLOCK_ID,
            );

            //Если элемент уже существует, обновляем его или удаляем, при условии что флаг установлен
            if(isset($this->sectionsCache[$value->id])) {
                //Удаляем из битрикса элемент
                if($value->deletion_mark == true) {
                    //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                    $arItem['ACTIVE'] = 'N';
                    if($res = $el->Update($this->sectionsCache[$value->id], $arItem)) {
                        echo 'Запись деативирована - '.$arItem['ID'];
                    }
                    else {
                        echo '<p class="error">При деактивации раздела произошла ошибка ['.$arItem['ID'].']<br>
                                    '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'</p>';
                    }
                    //                    if (CIBlockSection::Delete($arItem['ID'])) {
                    //                        echo 'Запись удалена - ' . $arItem['ID'];
                    //                    } else {
                    //                        echo '<p class="error">При удалении элемента произошла ошибка [' . $arItem['ID'] . ']<br>' . $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__ . '</p>';
                    //                    }
                }
                else {
                    //Если удалять не нужно, то обновляем и выводим сообщение
                    if($res = $el->Update($this->sectionsCache[$value->id], $arLoadProductArray)) {
                        echo "Запись обновлена: ".$value->name." - ".$value->title."<br />";
                    }
                    else {
                        echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                    }
                }
            }
            else {
                $code = CUtil::translit($arLoadProductArray['NAME'], 'ru',
                    array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => ''));

                if(array_search($code, $this->sectionsCodeCache)) {
                    $code .= md5($arLoadProductArray['XML_ID']);
                }

                $arLoadProductArray["CODE"] = $code;
                if($sectionId = $el->Add($arLoadProductArray)) {
                    $this->sectionsCache[$arLoadProductArray['XML_ID']] = $sectionId;
                    $this->sectionsCodeCache[$arLoadProductArray['XML_ID']] = $code;
                    echo "Добавлена: ".$value->name." - ".$value->title."<br />";
                }
                else {
                    echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                }
            }

            if(!empty($value->set_cat)) {

                $this->initTechDocCache();

                if(is_object($value->set_cat)) {
                    $value->set_cat = array($value->set_cat);

                    foreach ($value->set_cat as $set_cat) {
                        $props = [
                            'id_cat' => $this->sectionsCache[$value->id]
                        ];
                        if(isset($this->techDocCache[$set_cat->id_tehdoc])) {
                            $props['id_tehdoc'] = $this->techDocCache[$set_cat->id_tehdoc];
                        }
                        $arFields = Array(
                            "IBLOCK_SECTION_ID" => false,
                            "IBLOCK_ID" => self::COLLECTIONS_IBLOCK_ID,
                            "XML_ID" => $set_cat->set_id,
                            "PROPERTY_VALUES" => $props,
                            "NAME" => $set_cat->description,
                            "ACTIVE" => "Y"
                        );
                        $this->addCollection($arFields);
                    }
                }
            }
            $answer['StringCategory'][] = $value->id;
        }

        echo "</div>";
        $g = count($answer['StringCategory']) > 0 ? true : false;
        $answer['StringCategory'] = implode(";", $answer['StringCategory']);
        if(!empty($ost->return->propname)) {
            $this->initPropname($ost);
        }

        if($this->answer) {
            $ost = $this->client->__soapCall("CategoryAnswer", array('parameters' => $answer));
            if($g) {
                //                echo "<meta http-equiv=\"refresh\" content=\"2; url=".$_SERVER["REQUEST_URI"]."\">";
                //$this->initCategory();
            }
        }
        if(($ost->return == true)) {
            echo "<div class='suc'>Категории загружены</div>";
            echo "<div class='info'>В количестве: ".count($ost->return->category)."</div>";
        }
        else {
            echo "<div class='error'>Категории не загружены</div>";
        }
    }

    public function addCollection($fields, $params = [])
    {
        $this->initCollectionCache();
        $el = new CIBlockElement();
        
        //проверяем наличие такого элемента
        //Если элемент уже существует, обновляем его или удаляем, при условии что флаг установлен
        if(($existElementId = $this->collectionCache[$fields["XML_ID"]])) {
            //Удаляем из битрикса элемент
            if($params["deletion_mark"] == true) {
                //Проверяем права на удаление
                //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                if(!CIBlockElement::Delete($existElementId)) {
                    echo 'Запись удалена - '.$existElementId;
                }
                else {
                    echo '<p class="error">При удалении элемента произошла ошибка ['.$existElementId.']<br>
                                    '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'</p>';
                }
            }
            else {
                //Если удалять не нужно, то обновляем и выводим сообщение
                if($res = $el->Update($existElementId, $fields)) {
                    echo "Запись обновлена: " . $fields["NAME"] . "<br />";
                }
                else {
                    echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                }
            }
        }
        else {
            $fields['CODE'] = CUtil::translit($fields['NAME'], 'ru',
                array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => ''));

            if(array_search($fields['CODE'], $this->collectionCodeCache)) {
                $fields['CODE'] .= md5($fields['XML_ID']);
            }

            if($newId = $el->Add($fields)) {
                $this->collectionCache[$fields['XML_ID']] = $newId;
                $this->collectionCodeCache[$fields['XML_ID']] = $fields['CODE'];

                echo "Добавлена: " . $fields['NAME'] . "<br />";
            }
            else {
                echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
            }
        }
    }

    function initPropname($ost) {

        $section_xml_ids = array();
        $props_codes = array();
        foreach($ost->return->Category as $category) {
            $section_xml_ids[] = $category->id;
        }

        $this->writeMongoValues($ost->return->propname, 'properties');

        foreach($ost->return->propname as $propname) {
            $props_codes[] = $this->code1C2codeBitrix($propname->id);
        }

        $props_codes = array_unique($props_codes);

        $sections = BXHelper::getSections(array(), array('IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                                                         'XML_ID' => $section_xml_ids), false, array('ID',
                                                                                                     'XML_ID'), true, 'XML_ID');
        $sections = $sections['RESULT'];

        $section_ids = BXHelper::pull_array_field($sections, 'ID');

        $properties = BXHelper::getProperties(array(), array('IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                                                             'CODE' => $props_codes), array('ID',
                                                                                            'CODE',
                                                                                            'NAME'), 'CODE');
        $properties = $properties['RESULT'];


        $current_section_properties = BXHelper::getPropertySectionLinks(self::CATALOG_IBLOCK_ID, $section_ids, false, false, array(), array(), 'SECTION_ID', false);
        //здесь достаем ВСЕ связи между свойство-раздел, без доп условий (кроме $section_ids) и без кеша.

        $array_import_sections = BXHelper::group_array(json_decode(json_encode($ost->return->propname), true), 'cat_id');

        $obIblockSectionLink = new CIBlockSectionPropertyLink;


        foreach($sections as $sect_xml_id => $arSection) {
            if(!empty($array_import_sections[$sect_xml_id]) && !empty($current_section_properties[$arSection['ID']])) {
                $import_sect_props = $array_import_sections[$sect_xml_id];
                $current_sect_props = $current_section_properties[$arSection['ID']];

                foreach($current_sect_props as $cs_prop) {
                    if(strpos("min", $cs_prop['CODE']) === false && strpos("max", $cs_prop['CODE']) === false) { //пропускаем если свойства типа min max
                        $current_prop_found = false;
                        foreach($import_sect_props as $k => $is_prop) {
                            if($this->code1C2codeBitrix($is_prop['id']) == $cs_prop['CODE']) {
                                $current_prop_found = true;
                                break;
                            }
                        }
                        if(!$current_prop_found) {
                            $obIblockSectionLink->Delete($arSection['ID'], $cs_prop['PROPERTY_ID']);
                            foreach($current_sect_props as $add_prop) {
                                if($add_prop['CODE'] == $cs_prop['CODE']."_min" || $add_prop['CODE'] == $cs_prop['CODE']."_max") { //удалем дополнительные свойства типа min max связанные с этим
                                    $obIblockSectionLink->Delete($arSection['ID'], $add_prop['PROPERTY_ID']);
                                }
                            }
                        }
                    }
                }
            }
        }


        define("CACHED_b_iblock_bucket_size", 3600);
        $answer = array();
        $answer['ID_Portal'] = 'HG';
        //Объект элемента в модели битрикса
        $el = new CIBlockProperty;
        echo "<div class='suc'>";
        $get = array();

        if(is_object($ost->return->propname)) {
            $ost->return->propname = array($ost->return->propname);
        }

        foreach($ost->return->propname as $k => $v) {
            if($get[$v->id]) {
                $get[$v->id]['cat_id'][] = array($v->cat_id, $v->main, $v->order);
            }
            else {
                $get[$v->id] = array(
                    'name' => $v->name,
                    'cat_id' => array(array($v->cat_id, $v->main, $v->order)),
                    'main' => $v->main,
                    'type' => $v->type,
                    'interval' => $v->interval,
                    'sort' => $v->order,
                    'deletion_mark' => $v->deletion_mark
                );
            }
        }

        foreach($get as $key => $value) {

            if(!$value['interval']) {
                switch($this->import_property_types[$value['type']]) {
                    case "STR":
                    case "BOOL":
                        $type = "L";
                        $display_type = "F";
                        break;
                    case "INT":
                        $type = "N";
                        $display_type = "A";
                        break;
                }
            }
            else {
                $type = "L";
                $display_type = "F";
            }

            $arFields = Array(
                "NAME" => $value['name'],
                "ACTIVE" => "Y",
                "SORT" => $value['sort'],
                "CODE" => $this->code1C2codeBitrix($key),
                "PROPERTY_TYPE" => $type,
                "IBLOCK_ID" => 1,
                "SECTION_PROPERTY" => "N"
            );

            //проверяем наличие такого элемента
            $current_сode = $this->code1C2codeBitrix($key);

            //Если элемент уже существует, обновляем его или удаляем, при условии что флаг установлен
            if($value['interval']) {
                $this->AddDopProp($key.'_min', $value['name'].' минимальный', $value['sort'], $value['cat_id'],
                    $value['deletion_mark'], "1");
                $this->AddDopProp($key.'_max', $value['name'].' максимальный', $value['sort'], $value['cat_id'],
                    $value['deletion_mark'], "1");
            }

            if($arItem = $properties[$current_сode]) {
                //Удаляем из битрикса элемент
                if($value['deletion_mark'] == true) {
                    //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                    if(CIBlockProperty::Delete($arItem['ID'])) {
                        echo 'Запись удалена - '.$arItem['ID'];
                    }
                    else {
                        echo '<p class="error">При удалении элемента произошла ошибка ['.$arItem['ID'].']<br>'.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'</p>';
                    }
                }
                else {
                    //Если удалять не нужно, то обновляем и выводим сообщение
                    if($res = $el->Update($arItem['ID'], $arFields)) {
                        echo "Запись обновлена: ".$value['name']."<br />";

                        foreach($value['cat_id'] as $v) {
                            $tmp = new CIBlockSectionPropertyLink;
                            if($arItem_s = $sections[$v[0]]) {
                                $tmp->Set($arItem_s['ID'], $arItem['ID'],
                                    $value['interval']
                                        ? array(
                                        'SMART_FILTER' => 'N',
                                        'DISPLAY_EXPANDED' => 'N',
                                        'IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                                        'DISPLAY_TYPE' => $display_type
                                    )
                                        : (
                                    $v[1]
                                        ?
                                        array(
                                            'SMART_FILTER' => 'Y',
                                            'DISPLAY_EXPANDED' => 'Y',
                                            'IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                                            'DISPLAY_TYPE' => $display_type
                                        )
                                        :
                                        array(
                                            'SMART_FILTER' => 'Y',
                                            'DISPLAY_EXPANDED' => 'N',
                                            'IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                                            'DISPLAY_TYPE' => $display_type
                                        )
                                    )
                                );
                                $this->setSectionPropertySort(self::CATALOG_IBLOCK_ID, $arItem_s['ID'], $arItem['ID'], $v[2]);
                            }
                            else {
                                echo 'Error: не удалось связать блоки. '.$tmp->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                            }
                        }
                    }
                    else {
                        echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                    }
                }
            }
            else {
                if($PROP_ID = $el->Add($arFields)) {
                    $arFields['ID'] = $PROP_ID;
                    $properties[$current_сode] = $arFields;

                    echo "Добавлена: ".$value['name'].""."<br />";
                    foreach($value['cat_id'] as $v) {
                        $tmp = new CIBlockSectionPropertyLink;
                        if($arItem_s = $sections[$v[0]]) {
                            echo '<h1>'.$arItem['ID'].'</h1>';
                            $tmp->Set($arItem_s['ID'],
                                $PROP_ID,
                                $value['interval']
                                    ? array(
                                    'SMART_FILTER' => 'N',
                                    'DISPLAY_EXPANDED' => 'N',
                                    'IBLOCK_ID' => 1,
                                    'DISPLAY_TYPE' => $display_type
                                )
                                    : (
                                $v[1]
                                    ?
                                    array(
                                        'SMART_FILTER' => 'Y',
                                        'DISPLAY_EXPANDED' => 'Y',
                                        'IBLOCK_ID' => 1,
                                        'DISPLAY_TYPE' => $display_type
                                    )
                                    :
                                    array(
                                        'SMART_FILTER' => 'Y',
                                        'DISPLAY_EXPANDED' => 'N',
                                        'IBLOCK_ID' => 1,
                                        'DISPLAY_TYPE' => $display_type
                                    )
                                )
                            );

                            $this->setSectionPropertySort(self::CATALOG_IBLOCK_ID, $arItem_s['ID'], $arItem['ID'], $v[2]);

                        }
                        else {
                            echo 'Error: не удалось связать блоки. '.$tmp->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                        }
                    }
                }
                else {
                    echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                }
            }
        }

        echo "</div>";
        $this->initProp();

        if(($ost->return == true)) {
            echo "<div class='suc'>Свойства загружены</div>";
            echo "<div class='info'>В количестве: ".count($ost->return->category)."</div>";
        }
        else {
            echo "<div class='error'>Свойства не загружены</div>";
        }


    }

    function writeMongoValues($values, $collection_name) {
        if(is_object($values) || is_object($values[0])) {
            $values = json_decode(json_encode($values), true);
        }

        //pr(array($collection_name, $values));
        if(is_array($values) && count($values)) {
            $mongo = new MongoClient();
            $mongo_database = $mongo->selectDB($this->mongo_db_name);
            if(is_object($mongo_database)) {
                $property_values_collection = $mongo_database->selectCollection($collection_name);
                if(is_object($property_values_collection)) {
                    foreach($values as $val) {
                        $property_values_collection->update(array('id' => $val['id']), $val, array('upsert' => true));
                    }
                    return true;
                }
            }
        }
        return false;
    }

    /*function addSectionPropertySort($iblock_id, $section_id, $property_id, $sort) {
        if (intval($iblock_id) && intval($section_id) && intval($property_id) && intval($sort)) {
            $obSort = new \CUSTOM\Entity\SectionPropertySortTable();
            $sort_field = $obSort->getList(
                array(
                    'filter' => array(
                        'UF_IBLOCK_ID' => $iblock_id,
                        'UF_SECTION_ID' => $section_id,
                        'UF_PROPERTY_ID' => $property_id
                    )
                )
            )->getNext();
            if (empty($sort_field['ID'])) {
                $obSort->Add(array('UF_IBLOCK_ID' => $iblock_id, 'UF_SECTION_ID' => $section_id, 'UF_PROPERTY_ID' => $property_id, 'UF_SORT' => $sort));
            }
        }
    }*/

    /**
     * Преобразуем хем id из 1с в вид пригодный для битрикса
     *
     * @param $code String код из удаленного 1с
     *
     * @return string подходящий для храненея в битриксе вид кода
     */
    function code1C2codeBitrix($code) {
        return 'd_'.str_replace('-', '_', $code);
    }

    /**
     * Загружаем свойства разделов
     */

    function AddDopProp($key, $name, $sort, $cat, $del, $type) {
        switch($this->import_property_types[$type]) {
            case "BOOL":
            case "STR":
                $display_type = "F";
                break;
            case "INT":
                $display_type = "A";
                break;

        }
        $el = new CIBlockProperty;
        $arFields = Array(
            "NAME" => $name,
            "ACTIVE" => "Y",
            "SORT" => $sort,
            "CODE" => $this->code1C2codeBitrix($key),
            "PROPERTY_TYPE" => "N",
            "IBLOCK_ID" => 1,
            "VALUES" => array(),
            "SECTION_PROPERTY" => "N"
        );

        $arFilter = Array('IBLOCK_ID' => 1, 'CODE' => $this->code1C2codeBitrix($key));
        $rsItems = CIBlockProperty::GetList(Array("SORT" => "ASC"), $arFilter);
        if($arItem = $rsItems->GetNext()) {
            if($del) {
                if(CIBlockProperty::Delete($arItem['ID'])) {
                    echo 'Запись удалена - '.$arItem['ID'];

                }
                else {
                    echo '<p class="error">При удалении элемента произошла ошибка ['.$arItem['ID'].']<br>'.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'</p>';
                }
            }
            else {
                if($res = $el->Update($arItem['ID'], $arFields)) {
                    echo "Запись обновлена: ".$name."<br />";
                    foreach($cat as $v) {
                        echo '<br>';
                        $arFilter = Array('IBLOCK_ID' => 1, 'XML_ID' => $v[0]);
                        $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, true);
                        $tmp = new CIBlockSectionPropertyLink;
                        if($arItem_s = $rsItems->GetNext()) {
                            $tmp->Set($arItem_s['ID'], $arItem['ID'],
                                (
                                $v[1]
                                    ?
                                    array(
                                        'SMART_FILTER' => 'Y',
                                        'DISPLAY_EXPANDED' => 'Y',
                                        'DISPLAY_TYPE' => $display_type,
                                        'IBLOCK_ID' => 1
                                    )
                                    :
                                    array(
                                        'SMART_FILTER' => 'Y',
                                        'DISPLAY_EXPANDED' => 'N',
                                        'DISPLAY_TYPE' => $display_type,
                                        'IBLOCK_ID' => 1
                                    )
                                )
                            );
                            $this->setSectionPropertySort(1, $arItem_s['ID'], $arItem['ID'], $v[2]);
                        }
                        else {
                            echo 'Error dopblock: не удалось связать блоки. '.$tmp->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                        }
                    }
                }
                else {
                    echo 'Error dopblock: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                }
            }
        }
        else {
            if($dop_id = $el->Add($arFields)) {
                echo "Добавлена: ".$name.""."<br />";
                foreach($cat as $v) {
                    $arFilter = array('IBLOCK_ID' => 1, 'XML_ID' => $v[0]);
                    $rsItems = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilter, true);
                    $tmp = new CIBlockSectionPropertyLink;
                    if($arItem = $rsItems->GetNext()) {
                        echo '<h1>'.$arItem['ID'].'</h1>';
                        $tmp->Add($arItem['ID'],
                            $dop_id, $v[1]
                                ? array(
                                    'SMART_FILTER' => 'Y',
                                    'DISPLAY_EXPANDED' => 'Y',
                                    'DISPLAY_TYPE' => $display_type,
                                    'IBLOCK_ID' => 1
                                )
                                : array(
                                    'SMART_FILTER' => 'Y',
                                    'DISPLAY_TYPE' => $display_type,
                                    'DISPLAY_EXPANDED' => 'N',
                                    'IBLOCK_ID' => 1
                                )
                        );
                        $this->setSectionPropertySort(1, $arItem['ID'], $dop_id, $sort);
                    }
                    else {
                        echo 'Error: не удалось связать блоки. '.$tmp->LAST_ERROR." ".__LINE__." ".__FUNCTION__.'<br>';
                    }
                }
            }
            else {
                echo 'Error: '.$el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
            }
        }
    }

    function setSectionPropertySort($iblock_id, $section_id, $property_id, $sort) {
        if(intval($iblock_id) && intval($section_id) && intval($property_id)) {
            $obSort = new \CUSTOM\Entity\SectionPropertySortTable();
            $sort_field = $obSort->getList(
                array(
                    'filter' => array(
                        'UF_IBLOCK_ID' => $iblock_id,
                        'UF_SECTION_ID' => $section_id,
                        'UF_PROPERTY_ID' => $property_id
                    )
                )
            )->fetch();
            if($sort_field['ID']) {
                $obSort->Update($sort_field['ID'], array('UF_SORT' => intval($sort)));
            }
            else {
                $obSort->Add(array('UF_IBLOCK_ID' => $iblock_id,
                                   'UF_SECTION_ID' => $section_id,
                                   'UF_PROPERTY_ID' => $property_id,
                                   'UF_SORT' => intval($sort)));
            }
        }
    }

    function initProp() {

        $ost = $this->GetResultFunction('CategoryGet');
        //define("CACHED_b_iblock_bucket_size", 3600);
        $answer = array();
        $answer['ID_Portal'] = 'HG';
        //Объект элемента в модели битрикса
        $el = new CIBlockProperty;
        echo "<div class='suc'>";
        $get = array();

        if(is_object($ost->return->prop)) {
            $ost->return->prop = array($ost->return->prop);
        }

        $enum_values_xml_ids = array();
        $property_xml_ids = array();

        $this->writeMongoValues($ost->return->prop, 'property_values');

        foreach($ost->return->prop as $key => $value) {
            if(!empty($value->name) && $value->name != '.' && $value->name != '-') {
                $xml_enum_id = $this->code1C2codeBitrix($value->id);
                $enum_values_xml_ids[] = $xml_enum_id;
                $property_xml_ids[] = $this->code1C2codeBitrix($value->propname_id);
                $get[$value->propname_id][$xml_enum_id] = array(
                    'VALUE' => $value->name,
                    'XML_ID' => $xml_enum_id
                );
            }
        }

        $property_xml_ids = array_unique($property_xml_ids);

        $arPropertyEnums = BXHelper::getPropertyEnum(array(), array('XML_ID' => $enum_values_xml_ids), 'XML_ID', false);
        $arProperties = BXHelper::getProperties(array(), array('IBLOCK_ID' => 1,
                                                               'CODE' => $property_xml_ids), array('ID',
                                                                                                   'CODE',
                                                                                                   'NAME',
                                                                                                   'PROPERTY_TYPE'), 'CODE', false);
        $arProperties = $arProperties['RESULT'];

        $obPropertyEnum = new CIBlockPropertyEnum();

        foreach($get as $key => $value) {

            //            $arFields = array(
            //                "VALUES" => $value,
            //            );

            $code = $this->code1C2codeBitrix($key);

            if(!empty($arProperties[$code])) {
                foreach($value as $val) {
                    if($arProperties[$code]['PROPERTY_TYPE'] == 'L') {
                        if($arPropertyEnums[$val['XML_ID']]['PROPERTY_ID'] == $arProperties[$code]['ID']) {
                            $obPropertyEnum->Update($arPropertyEnums[$val['XML_ID']]['ID'], array('VALUE' => $val['VALUE']));
                        }
                        else {
                            $obPropertyEnum->Add(array('VALUE' => $val['VALUE'],
                                                       'XML_ID' => $val['XML_ID'],
                                                       'PROPERTY_ID' => $arProperties[$code]['ID']));
                        }
                    }
                    //$DB->Query("DELETE FROM b_iblock_property_enum WHERE PROPERTY_ID = ".$arProperties[$code]['ID']." AND XML_ID = '".$val['XML_ID']."'");
                }
            }

            //проверяем наличие такого элемента
            //            $arFilter = Array('IBLOCK_ID' => 1, 'CODE' => $this->code1C2codeBitrix($key));
            //            $rsItems = CIBlockProperty::GetList(Array("SORT" => "ASC"), $arFilter);
            //            //Если элемент уже существует, обновляем его
            //            if ($arItem = $rsItems->GetNext()) {
            //                //Если удалять не нужно, то обновляем и выводим сообщение
            //                if ($res = $el->Update($arItem['ID'], $arFields)) {
            //                    echo "Значение свойства добавлено: " . $value['name'] . "<br />";
            //                } else {
            //                    echo 'Error: ' . $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
            //                }
            //
            //            }
        }

        echo "</div>";
        if(($ost->return == true)) {
            echo "<div class='suc'>Значения свойств загружены</div>";
            echo "<div class='info'>В количестве: ".count($ost->return->category)."</div>";
        }
        else {
            echo "<div class='error'>Значения свойств не загружены</div>";
        }
    }

    function deleteSectionPropertySort($iblock_id, $section_id, $property_id) {
        if(intval($iblock_id) && intval($section_id) && intval($property_id)) {
            $obSort = new \CUSTOM\Entity\SectionPropertySortTable();
            $sort_field = $obSort->getList(
                array(
                    'filter' => array(
                        'UF_IBLOCK_ID' => $iblock_id,
                        'UF_SECTION_ID' => $section_id,
                        'UF_PROPERTY_ID' => $property_id
                    )
                )
            )->fetch();
            if($sort_field['ID']) {
                $obSort->Delete($sort_field['ID']);
            }
        }
    }

    /**
     * Загружаем значения свойств
     */

    function refactorPropertiesByValues($property_xml_ids) {
        print_r($property_xml_ids);
        $property_xml_ids_bitrix = array_map(array($this, 'code1C2codeBitrix'), $property_xml_ids);

        //$reserved_property_values = $this->getMongoValues(array('propname_id' => array('$in' => $property_xml_ids)));
        $reserved_properties = $this->getMongoValues(array('id' => array('$in' => $property_xml_ids)), 'properties');

        $temp = array();
        foreach($reserved_properties as $res_prop) {
            $temp[$res_prop['id']] = $res_prop;
        }

        $reserved_properties = $temp;

        //        $temp = array();
        //        foreach ($reserved_property_values as $res_prop) {
        //            $temp[$res_prop['id']] = $res_prop;
        //        }
        //        $reserved_property_values = $temp;


        $arProperties = BXHelper::getProperties(array(), array('IBLOCK_ID' => 1,
                                                               'CODE' => $property_xml_ids_bitrix), array('ID',
                                                                                                          'PROPERTY_TYPE',
                                                                                                          'CODE'), 'CODE', false);
        $arProperties = $arProperties['RESULT'];

        $arPropertyListToInt = array();
        $arPropertyIntToList = array();

        foreach($property_xml_ids as $xml_id) {
            $xml_id_b = $this->code1C2codeBitrix($xml_id);
            $arFilter =
                array(
                    'IBLOCK_ID' => 1,
                    '!PROPERTY_'.$xml_id_b => false
                );
            $count = CIBlockElement::GetList(array(), $arFilter, array());
            //            print_r(array($count, $arProperties[$xml_id_b]));
            //print_r(array($xml_id_b, $arProperties[$xml_id_b], $count, $this->import_property_types[$reserved_properties[$xml_id]['type']]));
            //print_r(array(intval($arProperties[$xml_id_b]['PROPERTY_TYPE'] == 'L' ), intval($count > 3), intval($this->import_property_types[$reserved_properties[$xml_id]['type']] == 'INT')));
            if(
                $arProperties[$xml_id_b]['PROPERTY_TYPE'] == 'N'
                && $count < 4
            ) {
                $arPropertyIntToList[] = $xml_id;
            }
            else {
                if($arProperties[$xml_id_b]['PROPERTY_TYPE'] == 'L' && $count > 3 && $this->import_property_types[$reserved_properties[$xml_id]['type']] == 'INT') {
                    $arPropertyListToInt[] = $xml_id_b;
                }
                else {
                    continue;
                }
            }
        }

        //        if (!empty($arPropertyListToInt)) {
        //            $this->convertListPropertiesToInt(array_unique($arPropertyListToInt), $arProperties);
        //        }
        //
        //        if (!empty($arPropertyIntToList)) {
        //            $this->convertIntPropertiesToList(array_unique($arPropertyIntToList), $arProperties);
        //        }

    }

    function getMongoValues($query_filter, $collection_name) {
        $mongo = new MongoClient();
        $mongo_database = $mongo->selectDB($this->mongo_db_name);
        if(is_object($mongo_database)) {
            $property_values_collection = $mongo_database->selectCollection($collection_name);
            if(is_object($property_values_collection)) {
                $i_values = $property_values_collection->find($query_filter); //iterator values
                $values = iterator_to_array($i_values);
                return $values;
            }
        }
        return false;
    }

    function convertListPropertiesToInt($arPropCodes, $arProperties) {
        global $DB;
        $obProperty = new CIBlockProperty();
        $arPropertyEnum = BXHelper::getPropertyEnum(array(), array('PROPERTY_ID' => $arPropCodes), 'ID', false);
        foreach($arPropCodes as $p_code) {
            $DB->Query("UPDATE b_iblock_property SET PROPERTY_TYPE = 'N' WHERE ID = ".$arProperties[$p_code]['ID']);
            //$obProperty->Update($arProperties[$p_code]['ID'], array('PROPERTY_TYPE' => 'N'));
        }
        $this->updateElementPropertyValuesToInt($arPropertyEnum);

    }

    public static function updateElementPropertyValuesToInt($arPropertyValues) {
        global $DB;
        $db_name = "b_iblock_element_property";
        $db_sname = "biep";
        $property_value_ids = array();
        foreach($arPropertyValues as $id => $arPropVal) {
            $DB->Query("UPDATE $db_name biep SET $db_sname.VALUE = ".$arPropVal['VALUE'].", $db_sname.VALUE_ENUM = NULL, $db_sname.VALUE_NUM = ".$arPropVal['VALUE']." WHERE $db_sname.VALUE_ENUM = $id");
        }
    }

    function convertIntPropertiesToList($arPropCodes, $arProperties) {
        //        global $DB;
        //        $arPropBitrixCodes = array_map(array($this, 'code1C2codeBitrix'), $arPropCodes);
        //        $obPropertyEnum = new CIBlockPropertyEnum();
        //        $arPropertyEnum = BXHelper::getPropertyEnum(array(), array('PROPERTY_ID' => $arPropBitrixCodes), 'ID', false);
        //        foreach ($arPropCodes as $i => $p_code) {
        //            $p_bitrix_code = $arPropBitrixCodes[$i];
        //            $DB->Query("UPDATE b_iblock_property SET PROPERTY_TYPE = 'L' WHERE ID = ".$arProperties[$p_bitrix_code]['ID']);
        //                //$obProperty->Update($arProperties[$p_code]['ID'], array('PROPERTY_TYPE' => 'N'));
        //            $dbElementResult = $DB->Query("SELECT biep.* FROM b_iblock_element_property biep WHERE biep.IBLOCK_PROPERTY_ID = ".$arProperties[$p_bitrix_code]['ID']);
        //            while ($next = $dbElementResult->GetNext()) {
        //                $existed_enum_found = false;
        //                foreach ($arPropertyEnum as $arEnum) {
        //                    if ($arEnum['VALUE'] == $next['VALUE'] && $arEnum['PROPERTY_ID'] == $arProperties[$p_bitrix_code]['ID']) {
        //                        $DB->Query("UPDATE b_iblock_element_property SET VALUE = '".$arEnum['ID']."', VALUE_ENUM = '".$arEnum['ID']."' WHERE ID = ".$next['ID']);
        //                        $existed_enum_found = true;
        //                        break;
        //                    }
        //                }
        //                if (!$existed_enum_found) {
        //                    $value = $this->getMongoValues(array('propname_id' => $p_code, 'name' => $next['VALUE']), 'property_values');
        //                    if ($value) {
        //                        $added_id = $obPropertyEnum->Add(array("PROPERTY_ID" => $arProperties[$p_bitrix_code]['ID'], 'VALUE' => $value['name'], 'XML_ID' => $this->code1C2codeBitrix($value['id'])));
        //                        $DB->Query("UPDATE b_iblock_element_property SET VALUE = '".$added_id."', VALUE_ENUM = '".$added_id."' WHERE ID = ".$next['ID']);
        //                        $existed_enum_found = true;
        //                    }
        //                }
        //                if (!$existed_enum_found) {
        //                    $md5_enum = substr(md5($next['VALUE']), 0, 7);
        //                    print_r(array("PROPERTY_ID" => $arProperties[$p_bitrix_code]['ID'], 'VALUE' => $next['VALUE'], 'XML_ID' => $p_bitrix_code."_".$md5_enum));
        //                    $added_id = $obPropertyEnum->Add(array("PROPERTY_ID" => $arProperties[$p_bitrix_code]['ID'], 'VALUE' => $next['VALUE'], 'XML_ID' => $p_bitrix_code."_".$md5_enum));
        //                    $this->writeMongoValues(array('name' => $next['VALUE'], 'id' => $p_code."_".$md5_enum, 'propname_id' => $p_code), 'property_values');
        //                    $DB->Query("UPDATE b_iblock_element_property SET VALUE = '".$added_id."', VALUE_ENUM = '".$added_id."' WHERE ID = ".$next['ID']);
        //                }
        //            }
        //        }
    }

    /**
     * Обрабатываем товар
     */
    function initProduct() {
        //fileDump(array("azazaaz"), true);
        //$this->csv->saveLog(array('************'));
        // $this->csv->dynamicSave('cache/01.07.2015', array('*******'));

        $BLOCK_ID = self::CATALOG_IBLOCK_ID;
        $ost = $this->GetResultFunction('ItemsGet');
        //Бренды

        if(empty($this->tmp['B'])) {
            $arrayB = array();
            $rsItems = CIBlockElement::GetList(array(), array(
                'IBLOCK_ID' => self::BRAND_IBLOCK_ID,
            ), false, false, array('ID', 'XML_ID'));
            while($arItem = $rsItems->GetNext()) {
                $arrayB[$arItem['XML_ID']] = $arItem['ID'];
            }
            $this->tmp['B'] = $arrayB;
        }

        $this->initSectionsCache();

        if(empty($this->tmp['С'])) {
            $arrayB = array();
            $rsItems = CIBlockElement::GetList(array(), array(
                'IBLOCK_ID' => self::COLLECTIONS_IBLOCK_ID
            ), false, false, array('ID', 'XML_ID'));
            while($arItem = $rsItems->GetNext()) {
                $arrayB[$arItem['XML_ID']] = $arItem['ID'];
            }
            $this->tmp['C'] = $arrayB;
        }

        $answer = array();
        $answer['ID_Portal'] = 'HG';
        $answer['StringItems'] = array();
        //Объект элемента в модели битрикса
        $el = new CIBlockElement;
        //echo "<div class='suc'>";

        if(is_object($ost->return->item)) {
            $ost->return->item = array($ost->return->item);
        }

        $section_ids = array();
        $prop_codes = array();

        $measures = array();
        $measure_units = array();
        $arCatalogMeasures = array();

        if(!empty($ost->return->unit_messure_catalog)) {
            if(is_object($ost->return->unit_messure_catalog)) {
                $ost->return->unit_messure_catalog = array($ost->return->unit_messure_catalog);
            }
            $measure_units = json_decode(json_encode($ost->return->unit_messure_catalog), true);
            $temp_meas = array();
            foreach($measure_units as $m_unit) {
                $temp_meas[$m_unit['id']] = $m_unit;
            }
            $measure_units = $temp_meas;
        }

        if(!empty($ost->return->unit_messure)) {

            if(is_object($ost->return->unit_messure)) {
                $ost->return->unit_messure = array($ost->return->unit_messure);
            }

            $measures = json_decode(json_encode($ost->return->unit_messure), true);
            $temp_meas = array();
            foreach($measures as $m_unit_product) {
                $temp_meas[$m_unit_product['id']] = $m_unit_product;
            }
            $measures = $temp_meas;
        }

        $dbCatalogMeasureResult = CCatalogMeasure::GetList(array(), array('CODE' => array_keys($measure_units)));
        while($next = $dbCatalogMeasureResult->GetNext()) {
            $arCatalogMeasures[$next['CODE']] = $next;
        }

        foreach($measure_units as $id => $m_unit) {
            if(!is_array($arCatalogMeasures[$id])) {
                $added_measure_id = CCatalogMeasure::add(array(
                    'MEASURE_TITLE' => $m_unit['name'],
                    'SYMBOL_RUS' => $m_unit['name'],
                    'SYMBOL_INTL' => $m_unit['name'],
                    'SYMBOL_LETTER_INTL' => $m_unit['name'],
                    'CODE' => $id
                ));
                if($added_measure_id) {
                    $arCatalogMeasures[$id] = array(
                        'ID' => $added_measure_id
                    );
                }
            }
        }

        $updated_property_values_xml_ids = array();

        foreach($ost->return->item as $key => $value) {
            $section_id = false;
            if($this->sectionsCache[$value->cat_id]) {
                $section_id = $this->sectionsCache[$value->cat_id];
            }

            $id_brand = false;
            if($this->tmp['B'][$value->brand_id]) {
                $id_brand = $this->tmp['B'][$value->brand_id];
            }

            $id_col = false;
            if($this->tmp['C'][$value->Set_ID]) {
                $id_col = $this->tmp['C'][$value->Set_ID];
            }

            $prop = $value->properties;
            if(is_object($prop)) {
                $prop = array($prop);
            }
            $prop = array_map('json_encode', $prop);
            $prop = array_unique($prop);
            $prop = array_map('json_decode', $prop);
            $propA = array();

            //echo "<PRE>";

            foreach($prop as $vp) {
                $updated_property_values_xml_ids[] = $vp->propname_id;
            }

            if(!empty($updated_property_values_xml_ids)) {
                $propIntervals = $this->getMongoValues(array('id' => array('$in' => $updated_property_values_xml_ids),
                                                             'interval' => true), 'properties');

                $import_property_enums = $this->getMongoValues(array('propname_id' => array('$in' => $updated_property_values_xml_ids)), 'property_values');

                $temp = array();

                foreach($import_property_enums as $prop_val) {
                    $temp[$prop_val['id']] = $prop_val;
                }

                $prop_values = $temp;

                //                $arPropertyIntervalEnums = array();
                if(!empty($propIntervals)) {
                    $propIntervalsId = BXHelper::pull_array_field($propIntervals, 'id');
                    $obModel = &$this;

                    //                $prop_intervals_min = array_map(
                    //                    function ($code) use (&$obModel) {
                    //                        $code = $this->code1C2codeBitrix($code)."_min";
                    //                        return $code;
                    //                    },
                    //                    $propIntervalsId
                    //                );
                    //
                    //                $prop_intervals_max = array_map(
                    //                    function ($code) use (&$obModel) {
                    //                        $code = $this->code1C2codeBitrix($code)."_max";
                    //                        return $code;
                    //                    },
                    //                    $propIntervalsId
                    //                );

                    $prop_intervals_id_bitrix = array_map(
                        function ($code) use (&$obModel) {
                            $code = $this->code1C2codeBitrix($code);
                            return $code;
                        },
                        $propIntervalsId
                    );

                    //                    $arPropertyIntervalEnums = BXHelper::getPropertyEnum(
                    //                        array(),
                    //                        array('IBLOCK_ID' => 1,
                    //                              'CODE' => $prop_intervals_id_bitrix
                    //                        ), 'XML_ID', false);
                    //
                    //
                    //                    $arPropertyIntervalEnums = BXHelper::group_array($arPropertyIntervalEnums, 'PROPERTY_CODE');
                }


                $updated_property_values_xml_ids = array_unique($updated_property_values_xml_ids);
                /*foreach ($prop as $vp) {
                    if (in_array($vp->propname_id, $propIntervalsId)) {
                        $index = array_search($vp->propname_id, $updated_property_values_xml_ids);
                        unset($updated_property_values_xml_ids[$index]);
                        $updated_property_values_xml_ids[] = $vp->propname_id."_min";
                        $updated_property_values_xml_ids[] = $vp->propname_id."_max";
                    }
                }*/

                $updated_property_values_xml_ids = array_values($updated_property_values_xml_ids);

                $arPropertyEnums = BXHelper::getPropertyEnum(
                    array(),
                    array('IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                          'PROPERTY_ID' => array_map(
                              array($this, 'code1C2codeBitrix'),
                              $updated_property_values_xml_ids
                          )
                    ), 'XML_ID', false);

                $arProperties = BXHelper::getProperties(
                    array(),
                    array('IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                          'CODE' => array_map(
                              array($this, 'code1C2codeBitrix'),
                              $updated_property_values_xml_ids
                          )), array(), 'CODE', false
                );

                $arPropertyCollection = BXHelper::getProperties(array(), array('IBLOCK_ID' => self::CATALOG_IBLOCK_ID,
                                                                               'CODE' => 'collection'), array(), 'CODE');
                $arPropertyCollection = $arPropertyCollection['RESULT'];

                $arProperties = $arProperties['RESULT'];

                $arCurrentPropertyEnums = BXHelper::group_array($arPropertyEnums, 'PROPERTY_CODE');

                foreach($prop as $vp) {

                    $property_enums = $arCurrentPropertyEnums[$this->code1C2codeBitrix($vp->propname_id)];

                    $value_enum = false;
                    //$min_property_enums = $arPropertyIntervalEnums[$this->code1C2codeBitrix($vp->propname_id)."_min"];
                    //$max_property_enums = $arPropertyIntervalEnums[$this->code1C2codeBitrix($vp->propname_id)."_max"];

                    if(in_array($this->code1C2codeBitrix($vp->propname_id), $prop_intervals_id_bitrix)) {
                        foreach($property_enums as $enum) {
                            if($enum['XML_ID'] == $this->code1C2codeBitrix($vp->prop_id)) {
                                $min_max_array = explode(';', $enum["VALUE"]);

                                $propA[$this->code1C2codeBitrix($vp->propname_id).'_min'] = array(
                                    "VALUE" => $min_max_array[0]
                                );

                                $propA[$this->code1C2codeBitrix($vp->propname_id).'_max'] = array(
                                    "VALUE" => $min_max_array[1]
                                );

                                //echo 'CODE IN BITRIX: ' . $value->id . '<br>';

                                //$this->csv->saveLog(array('CODE IN BITRIX: ' . $value->id . '<br>'));
                                break;
                            }
                        }
                    }
                    if($vp->propname_id == 'edb71222-f482-11e4-9045-003048b99ee9') {
                        fileDump(array($value->article,
                                       $prop_values[$vp->prop_id],
                                       $vp->prop_id,
                                       $property_enums), true);
                    }
                    //                    $enum_found = false;

                    if(!empty($property_enums)) {
                        foreach($property_enums as $enum) {
                            $value_enum = null;
                            if($enum['XML_ID'] == $this->code1C2codeBitrix($vp->prop_id)) {
                                if($arProperties[$this->code1C2codeBitrix($vp->propname_id)]['PROPERTY_TYPE'] == 'L') {
                                    $value_enum = $enum['ID'];
                                }
                                else {
                                    if($arProperties[$this->code1C2codeBitrix($vp->propname_id)]['PROPERTY_TYPE'] == 'N') {
                                        $value_enum = $enum['VALUE'];
                                    }
                                }
                                $propA[$this->code1C2codeBitrix($vp->propname_id)] = array(
                                    "VALUE" => $value_enum
                                );
                                break;
                            }
                            //                            if (!$enum_found && $prop_values[$vp->prop_id]) {
                            //                                $value_enum = $prop_values[$this->code1C2codeBitrix($vp->prop_id)];
                            //                                /*if ($arProperties[$this->code1C2codeBitrix($vp->propname_id)]['PROPERTY_TYPE'] == 'L') {
                            //                                    $value_enum = $import_property_enums[$this->code1C2codeBitrix($vp->prop_id)]['ID'];
                            //                                } else if ($arProperties[$this->code1C2codeBitrix($vp->propname_id)]['PROPERTY_TYPE'] == 'N') {
                            //                                    $value_enum = $import_property_enums[$this->code1C2codeBitrix($vp->prop_id)]['VALUE'];
                            //                                }*/
                            ////                            $propA[$this->code1C2codeBitrix($vp->propname_id)] = array(
                            ////                                "VALUE" => $import_property_enums[$this->code1C2codeBitrix($vp->prop_id)]['ID']
                            ////                            );
                            //                            }
                            //                            $propA[$this->code1C2codeBitrix($vp->propname_id)] = array(
                            //                                "VALUE" => $value_enum
                            //                            );
                        }
                    }
                    else {
                        if(!empty($prop_values[$vp->prop_id])) {
                            $value_enum = $prop_values[$vp->prop_id]['name'];
                        }
                    }
                    //                    foreach ($property_enums as $enum) {
                    //                        $value_enum = null;
                    //                        if ($enum['XML_ID'] ==  $this->code1C2codeBitrix($vp->prop_id)) {
                    //                            if ($arProperties[$this->code1C2codeBitrix($vp->propname_id)]['PROPERTY_TYPE'] == 'L') {
                    //                                $value_enum = $enum['ID'];
                    //                            } else if ($arProperties[$this->code1C2codeBitrix($vp->propname_id)]['PROPERTY_TYPE'] == 'N') {
                    //                                $value_enum = $enum['VALUE'];
                    //                            }
                    //                            $propA[$this->code1C2codeBitrix($vp->propname_id)] = array(
                    //                                "VALUE" => $value_enum
                    //                            );
                    //                            $enum_found  = true;
                    //                            break;
                    //                        }
                    //                        if (!$enum_found && $prop_values[$vp->prop_id]) {
                    //                            $value_enum = $prop_values[$this->code1C2codeBitrix($vp->prop_id)];
                    //                            /*if ($arProperties[$this->code1C2codeBitrix($vp->propname_id)]['PROPERTY_TYPE'] == 'L') {
                    //                                $value_enum = $import_property_enums[$this->code1C2codeBitrix($vp->prop_id)]['ID'];
                    //                            } else if ($arProperties[$this->code1C2codeBitrix($vp->propname_id)]['PROPERTY_TYPE'] == 'N') {
                    //                                $value_enum = $import_property_enums[$this->code1C2codeBitrix($vp->prop_id)]['VALUE'];
                    //                            }*/
                    ////                            $propA[$this->code1C2codeBitrix($vp->propname_id)] = array(
                    ////                                "VALUE" => $import_property_enums[$this->code1C2codeBitrix($vp->prop_id)]['ID']
                    ////                            );
                    //                        }
                    //                        $propA[$this->code1C2codeBitrix($vp->propname_id)] = array(
                    //                            "VALUE" => $value_enum
                    //                        );
                    //                    }
                    $propA[$this->code1C2codeBitrix($vp->propname_id)] = array(
                        "VALUE" => $value_enum
                    );


                    //                $this->csv->dynamicSave('cache/01.07.2015', array($this->code1C2codeBitrix($vp->propname_id)));
                    //
                    //
                    //                $properties = CIBlockProperty::GetList(Array(),
                    //                    Array("CODE" => $this->code1C2codeBitrix($vp->propname_id) . '_min', "IBLOCK_ID" => 1));
                    //                if ($f_min = $properties->GetNext()) {
                    //                    $properties2 = CIBlockProperty::GetList(Array(),
                    //                        Array("CODE" => $this->code1C2codeBitrix($vp->propname_id) . '_max', "IBLOCK_ID" => 1));
                    //                    $f_max = $properties2->GetNext();
                    //                    $this->csv->dynamicSave('cache/01.07.2015', array($this->code1C2codeBitrix($vp->propname_id)));
                    //
                    //                    $property_enums = CIBlockPropertyEnum::GetList(array("DEF" => "DESC", "SORT" => "ASC"), array(
                    //                        "IBLOCK_ID" => 1,
                    //                        "CODE"      => $this->code1C2codeBitrix($vp->propname_id)
                    //                    ));
                    //
                    //                    while ($enum_fields = $property_enums->GetNext()) {
                    //                        if ($enum_fields["XML_ID"] == $this->code1C2codeBitrix($vp->prop_id)) {
                    //                            $min_max_array = explode(';', $enum_fields["VALUE"]);
                    //                            $propA[$this->code1C2codeBitrix($vp->propname_id) . '_min'] = array(
                    //                                "VALUE" => $min_max_array[0]
                    //                            );
                    //                            $propA[$this->code1C2codeBitrix($vp->propname_id) . '_max'] = array(
                    //                                "VALUE" => $min_max_array[1]
                    //                            );
                    //                            echo 'CODE IN BITRIX: ' . $value->id . '<br>';
                    //                            $this->csv->saveLog(array('CODE IN BITRIX: ' . $value->id . '<br>'));
                    //                            break;
                    //                        }
                    //                    }
                    //
                    //                } else {
                    //                    $property_enums = CIBlockPropertyEnum::GetList(array("DEF" => "DESC", "SORT" => "ASC"), array(
                    //                        "IBLOCK_ID" => 1,
                    //                        "CODE"      => $this->code1C2codeBitrix($vp->propname_id)
                    //                    ));
                    //
                    //                    while ($enum_fields = $property_enums->GetNext()) {
                    //                        if ($enum_fields["XML_ID"] == $this->code1C2codeBitrix($vp->prop_id)) {
                    //                            $propA[$this->code1C2codeBitrix($vp->propname_id)] = array(
                    //                                "VALUE" => $enum_fields["ID"]
                    //                            );
                    //
                    //                            break;
                    //                        }
                    //                    }
                    //                }
                }
            }

            $propA['sku'] = $value->article;
            $propA[$arPropertyCollection['collection']['ID']] = $id_col;
            $propA['brand'] = $id_brand;
            $propA['is_new'] = $value->novelty ? 19 : '';
            $arLoadProductArray = Array(
                "IBLOCK_SECTION_ID" => $section_id,
                "IBLOCK_ID" => $BLOCK_ID,
                "XML_ID" => $value->id,
                "PROPERTY_VALUES" => $propA,
                "NAME" => $value->name_full,
                "ACTIVE" => "Y",
                "DETAIL_TEXT" => $value->description,
            );

            $measure_id = $arCatalogMeasures[intval($measures[$value->unit_messure_id]['unit_messure_catalog_id'])]['ID'];


            //проверяем наличие такого элемента
            $rsItems = CIBlockElement::GetList(array(), array(
                'IBLOCK_ID' => $BLOCK_ID,
                "=XML_ID" => $value->id,
            ), false, false, array('ID'));

            //Если элемент уже существует, обновляем его или удаляем, при условии что флаг установлен
            if($arItem = $rsItems->GetNext()) {
                //Удаляем из битрикса элемент
                if($value->deletion_mark == true) {
                    //Проверяем права на удаление
                    /* if (CIBlock::GetPermission($BLOCK_ID) >= 'W') {*/
                    //деактивируем и выводим сообщение о выполнении, иначе показываем ошибку
                    $arItem['ACTIVE'] = 'N';
                    if($res = $el->Update($arItem['ID'], $arItem)) {
                        //echo 'Запись деактивирована - ' . $arItem['ID'];
                    }
                    else {
                        /*echo '<p class="error">При деактивации элемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                ' . $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__ . '</p>';*/
                    }
                    /*  }*/
                }
                else {
                    //Если удалять не нужно, то обновляем и выводим сообщение
                    $arLoadProductArray["CODE"] = CUtil::translit($value->name.'_'.$arItem['ID'], 'ru',
                        array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => ''));
                    if($res = $el->Update($arItem['ID'], $arLoadProductArray, false, true, true)) {
                        $arFields = array(
                            "MEASURE" => $measure_id
                        );
                        if(CCatalogProduct::Update($arItem['ID'], $arFields)) {
                            //echo "Обновили параметры товара к элементу каталога " . $arItem['ID'] . '<br>';
                        }
                        //echo "Запись обновлена: " . $value->name . "<br />";
                    }
                    else {
                        //echo 'Error: ' . $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__;
                    }
                }
            }
            else {
                $arLoadProductArray["CODE"] = CUtil::translit($value->name.'_'.$arItem['ID'], 'ru',
                    array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => ''));
                if($BRAND_ID = $el->Add($arLoadProductArray, false, true, true)) {
                    //echo "Добавлена: " . $value->name . "<br />";

                    $arFields = array(
                        "ID" => $BRAND_ID,
                        "VAT_ID" => 1,
                        "VAT_INCLUDED" => "Y",
                        "MEASURE" => $measure_id
                    );
                    if(CCatalogProduct::Add($arFields)) {
                        //echo "Добавили параметры товара к элементу каталога " . $arItem['ID'] . '<br>';
                    }
                }
                else {
                    //echo 'Error: ' . $el->LAST_ERROR." ".__LINE__." ".__FUNCTION__ . "<br>";
                }
            }
            $answer['StringItems'][] = $value->id;
        }

        //$this->refactorPropertiesByValues($updated_property_values_xml_ids);

        //echo "</div>";
        $isGo = false;

        if(count($answer['StringItems']) > 0) {
            $isGo = true;
        }

        $dbResult = CIBlockElement::GetList(Array("SORT" => "ASC"), Array('IBLOCK_ID' => self::CATALOG_IBLOCK_ID), false,
            Array('ID', 'NAME'), false);
        $tempelement = array();
        while($new = $dbResult->GetNext()) {
            $tempelement[] = $new;
        }
        $has_codes = array();
        foreach($tempelement as $element) {
            $elementup = new CIBlockElement;
            $code = CUtil::translit($element['NAME'], 'ru',
                array('change_case' => 'L', 'replace_space' => '-', 'replace_other' => ''));
            if(array_key_exists($code, $has_codes)) {
                $code = $code.(++$has_codes[$code]);
            }
            else {
                ++$has_codes[$code];
            }
            if($elementup->Update($element['ID'], array('CODE' => $code))) {
                //echo 'OK';
            }
        }


        $answer['StringItems'] = implode(";", $answer['StringItems']);
        if($this->answer) {
            $ost = $this->client->__soapCall("ItemsAnswer", array('parameters' => $answer));
            if($isGo) {
                echo "<meta http-equiv=\"refresh\" content=\"2; url=".$_SERVER["REQUEST_URI"]."\">";
                //header('Location: '.$_SERVER['REQUEST_URI']);
                //$this->initProduct();
            }
        }

        if(($ost->return == true)) {
            //echo "<div class='suc'>Товары загружены</div>";
            //echo "<div class='info'>В количестве: " . count($ost->return->item) . "</div>";
        }
        else {
            //echo "<div class='error'>Товары не загружены</div>";
        }
    }

    /**
     * выполняем все операции по ценам
     */
    function initPrice() {
        $BLOCK_ID = 1;
        $ost = $this->GetResultFunction("CostsGet");
        $answer = array();
        $answer['ID_Portal'] = 'HG';
        //Объект элемента в модели битрикса
        $el = new CIBlockElement;
        echo "<div class='suc'>";
        if(is_object($ost->return->cost)) {
            $ost->return->cost = array($ost->return->cost);
        }
        foreach($ost->return->cost as $key => $value) {
            $answer['StringCosts'][] = $value->item_id;
            $rsItems = CIBlockElement::GetList(array(), array(
                'IBLOCK_ID' => 1,
                "=XML_ID" => $value->item_id
            ), false, false, array('ID'));
            if($arItem = $rsItems->GetNext()) {
                $arFields = Array(
                    "PRODUCT_ID" => $arItem['ID'],
                    "CATALOG_GROUP_ID" => 1,
                    "PRICE" => $value->value_rub,
                    "CURRENCY" => "RUB",
                );
                $res = CPrice::GetList(array(), array(
                    "PRODUCT_ID" => $arItem['ID'],
                    "CATALOG_GROUP_ID" => 1
                ));
                if($arr = $res->Fetch()) {
                    CPrice::Update($arr["ID"], $arFields);
                    echo "Запись обновлена: ".$value->value_rub."<br />";
                }
                else {
                    CPrice::Add($arFields);
                    echo "Запись добавлена: ".$value->value_rub."<br />";
                }
            }
            else {
                echo '<p class="error">Не найден товар</p>';
            }
        }
        echo "</div>";
        if(($ost->return == true)) {
            echo "<div class='suc'>Цены загружены</div>";
            echo "<div class='info'>В количестве: ".count($ost->return->item)."</div>";
        }
        else {
            echo "<div class='error'>Цены не загружены</div>";
        }
        if($this->answer) {
            $answer['StringCosts'] = implode(";", $answer['StringCosts']);
            $ost = $this->client->__soapCall("CostsAnswer", array('parameters' => $answer));
        }

    }

    //выполняем все операции по брендам
    function initCollection($ost) {
        $iblockId = self::COLLECTIONS_IBLOCK_ID;
        $answer = array();
        $answer['ID_Portal'] = 'HG';
        //Объект элемента в модели битрикса
        $el = new CIBlockElement;
        echo "<div class='suc'>";
        if(is_object($ost->return->set)) {
            $ost->return->set = array($ost->return->set);
        }
        $this->initCollectionCache();

        $brandsCache = [];
        $rsItems = CIBlockElement::GetList(array(), array(
            'IBLOCK_ID' => self::BRAND_IBLOCK_ID,
        ), false, false, array('ID', 'XML_ID'));
        while($arItem = $rsItems->GetNext()) {
            $brandsCache[$arItem['XML_ID']] = $arItem['ID'];
        }

        foreach($ost->return->set as $key => $value) {
            $props = [];
            if(isset($brandsCache[$value->brand_id])) {
                $props['link_brand'] = $brandsCache[$value->brand_id];
            }
            $arFields = Array(
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID" => $iblockId,
                "XML_ID" => $value->id,
                "PROPERTY_VALUES" => $props,
                "NAME" => $value->name,
                "ACTIVE" => "Y"
            );
            
            $this->addCollection($arFields, ["deletion_mark" => $value->deletion_mark]);
        }
        echo "</div>";
        if(($ost->return == true)) {
            echo "<div class='suc'>Колекции загружены</div>";
            echo "<div class='info'>В количестве: ".count($ost->return->brand)."</div>";
        }
        else {
            echo "<div class='error'>Колекции не загружены</div>";
        }
    }


    function getCountEl($id) {
        $arFilter = Array("IBLOCK_ID" => IntVal($id), "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, array(), false, array());

        return $res;
    }

    function getCountSec($id) {
        $arFilter = Array(
            "IBLOCK_ID" => IntVal($id),
            "ACTIVE" => "Y"
        );

        return CIBlockSection::GetCount($arFilter);

    }

    function getCountProp($id) {
        $properties = CIBlockProperty::GetList(
            Array(),
            Array("ACTIVE" => "Y", "IBLOCK_ID" => $id));

        return $properties->SelectedRowsCount();
    }

    private function deleteFile($file) {

        // если файл - это детальная, то ищем его в бд по оригинальному имени и удаляем
        $this->checkifFileDetailImageExist($file);

        // ищем среди всех остальных возможных типов документов элемент
        $element = CIBlockElement::GetList([], ['IBLOCK_ID' => self::DOCUMENTATION_IBLOCK_ID, 'XML_ID' => $file->id])->GetNext();
        if($element){
            $this->csv->saveLog(array("Файл с ID {$file->id} удален"));
            echo "<div class='error'>Файл с ID {$file->id} удален</div>";
            CIBlockElement::Delete($element['ID']);
        }
        else {
            $this->csv->saveLog(array("Файл с ID {$file->id} для удаления не найден"));
        }
    }

    private function checkifFileDetailImageExist($file){
        $dbFile = CFile::GetList([], ['ORIGINAL_NAME' => $file->id.".jpg"]);

        while($arFile = $dbFile->GetNext()){
            CFile::Delete($arFile['ID']);
            $this->csv->saveLog(array("Детальное изображение товара с ID {$file->id} удалено",
                                      "Оригинальное имя файла - {$file->id}.jpg"));
            echo "<div class='error'>Детальное изображение товара с ID {$file->id} удалено</div>";
        }

        return true;
    }
}