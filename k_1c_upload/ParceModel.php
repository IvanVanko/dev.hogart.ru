<?php

class ParceModel
{
    //Приватные переменные для хранения ссылки на подключение
    private $client;
    private $arr_params = array();
    public $answer = false;

    function __construct()
    {
        try {
            //Подключаемся к soap-серверу
            $this->client = new SoapClient(
                "https://89.111.54.152/production/ws/Site.1cws?wsdl",
//                "https://89.111.54.152/TestAndrew/ws/Site.1cws?wsdl",
                array(
                    'login' => "Outside",
                    'password' => "23rehcbcnrbghjikb24nsczxbrbkjvtnhjd"
                )
            );
            //создаём ассоциативный массив с названием ключа, совпадающим
            //с названием параметра операции веб-сервиса и передаём значение id
            $this->arr_params['ID_Portal'] = 'HG';
        } catch (SoapFault $e) {
            echo '<div class="error">';
            echo $e->getMessage();
            echo '</div>';
        }
    }

    //Soap сервер доступен?
    function isReadySoap()
    {
        return $this->client ? true : false;
    }

    //Получить массив доступных функций
    function getFunctionSoap()
    {
        try {
            $func_list = $this->client->__getFunctions();
            return $func_list;
        } catch (Exception $e) {
            return false;
        }
    }

    function showFunctionSoap()
    {
        try {
            echo '<div class="suc">Доступные функции soap<pre>';
            print_r($this->getFunctionSoap());
            echo '</pre></div>';
        } catch (Exception $e) {
            echo "<p class='error'><b>Получить данные из 1С не удалось: " . $e->getMessage() . "</b></p>";
        }
    }

    //Получить массив данных брендов
    function GetBreands()
    {
        try {
            $ost = $this->client->__soapCall("BrandGet", array('parameters' => $this->arr_params));
            return $ost;
        } catch (Exception $e) {
            echo "<p class='error'><b>Получить данные по брендам из 1С не удалось: " . $e->getMessage() . "</b></p>";
            return false;
        }
    }

    //выполняем все операции по брендам
    function initBreands()
    {
        $BLOCK_ID = 2;
        $ost = $this->GetBreands();
        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            $answer['StringBrands'] = array();
            //Объект элемента в модели битрикса
            $el = new CIBlockElement;
            echo "<div class='suc'>";
<<<<<<< HEAD
            if (gettype($ost->return->brand) == 'object')
                $ost->return->brand = array($ost->return->brand);
            
            var_dump($ost->return->brand);
=======

            if (gettype($ost->return->brand) == 'object')
                $ost->return->brand = array($ost->return->brand);
>>>>>>> dce4612afd54d1b13183b8fc4bd358ceef6ea6d4

            foreach ($ost->return->brand as $key => $value) {
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"         => $BLOCK_ID,
                    "CODE"              => $value->id,
                    "EXTERNAL_ID"       => $value->id,
                    "PROPERTY_VALUES"   => array(),
                    "NAME"              => $value->title,
                    "ACTIVE"            => ($value->visibility) ? "Y" : "N"
                );
                //проверяем наличие такого элемента
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID'    => $BLOCK_ID,
                        "=CODE"        => $value->id,
                        "=EXTERNAL_ID" => $value->id,
                    ),
                    false, false, array('ID'));
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    //Удаляем из битрикса элемент
                    if ($value->deletion_mark == true) {
                        //Проверяем права на удаление
                        if (CIBlock::GetPermission($BLOCK_ID) >= 'W') {
                            //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                            if (!CIBlockElement::Delete($arItem['ID'])) {
                                echo 'Запись удалена - ' . $arItem['ID'];
                            } else echo '<p class="error">При удалении эллемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                    ' . $el->LAST_ERROR . '</p>';
                        }
                    } else {
                        //Если удалять не нужно, то обновляем и выводим сообщение
                        if ($res = $el->Update($arItem['ID'], $arLoadProductArray))
                            echo "Запись обновлена: " . $value->name . " - " . $value->title . "<br />";
                        else echo 'Error: ' . $el->LAST_ERROR;
                    }
                } else {
                    if ($BRAND_ID = $el->Add($arLoadProductArray))
                        echo "Добавлена: " . $value->name . " - " . $value->title . "<br />";
                    else echo 'Error: ' . $el->LAST_ERROR;
                }
                $answer['StringBrands'][] = $value->id;
            }
            echo "</div>";

            $g = false;
            if (count($answer['StringBrands']) >= 20) $g = true;
            
            $answer['StringBrands'] = implode(";", $answer['StringBrands']);

            //Коллекции
            $this->initCollection();
            if ($this->answer) {
                echo '<i>Ответ на сервер отправлен</i><br />';
                $ost = $this->client->__soapCall("BrandAnswer", array('parameters' => $answer));
                if ($g) $this->initBreands();
            }
            if (($ost->return == true)) {
                echo "<div class='suc'>Бренды загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->brand) . "</div>";
            } else
                echo "<div class='error'>Бренды не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }

    function GetTehDoc()
    {
        try {
            $ost = $this->client->__soapCall("TehDocGet", array('parameters' => $this->arr_params));
            return $ost;
        } catch (Exception $e) {
            echo "<p class='error'><b>Получить данные технической документации из 1С не удалось: " .
                $e->getMessage() . "</b></p>";
            return false;
        }
    }

    //выполняем все операции по документации
    function initTehDoc()
    {
        $BLOCK_ID = 10;
        $ost = $this->GetTehDoc();
        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            $answer['StringTehDoc'] = array();
            //Объект элемента в модели битрикса
            $el = new CIBlockElement;
            echo "<div class='suc'>";
            $arr = array();
            if(gettype($ost->return->tehdoc)=='object')
                $ost->return->tehdoc = array($ost->return->tehdoc);
            foreach ($ost->return->tehdoc as $key => $value) {
                if ($arr[$value->id]) {
                    if ($value->lines->obj_type == 'brands') {
                        $rsItems = CIBlockElement::GetList(array(),
                            array(
                                'IBLOCK_ID' => 2,
                                "=CODE" => $value->lines->obj_id,
                                "=EXTERNAL_ID" => $value->lines->obj_id,
                            ),
                            false, false, array('ID'));
                        if ($arItem = $rsItems->GetNext()) {
                            $arr[$value->id]['lines'][] = $arItem["ID"];
                        }
                    }
                } else {
                    $rsItems = CIBlockElement::GetList(array(),
                        array(
                            'IBLOCK_ID' => 2,
                            "=CODE" => $value->lines->obj_id,
                            "=EXTERNAL_ID" => $value->lines->obj_id,
                        ),
                        false, false, array('ID'));
                    $arItem = $rsItems->GetNext();
                    $arr[$value->id] = array(
                        'id'     => $value->id,
                        'adress' => $value->adress,
                        'lines'  => $value->lines->obj_type == 'brands' ? array($arItem["ID"]) : array(),
                        'name'   => $value->name
                    );
                }
            }
            foreach ($arr as $key => $value) {
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => $BLOCK_ID,
                    "CODE" => $value['id'],
                    "EXTERNAL_ID" => $value['id'],
                    "PROPERTY_VALUES" => array(
                        64 => 10,
                        65 => CFile::MakeFileArray('/1c-upload/' . $value['adress']),
                        87 => $value['lines']
                    ),
                    "NAME" => $value['name'],
                    "ACTIVE" => "Y"
                );
                //проверяем наличие такого элемента
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID' => $BLOCK_ID,
                        "=CODE" => $value['id'],
                        "=EXTERNAL_ID" => $value['id'],
                    ),
                    false, false, array('ID'));
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    //Удаляем из битрикса элемент
                    if ($value['deletion_mark']) {
                        //Проверяем права на удаление
                        if (CIBlock::GetPermission($BLOCK_ID) >= 'W') {
                            //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                            if (!CIBlockElement::Delete($arItem['ID'])) {
                                echo 'Запись удалена - ' . $arItem['ID'];
                            } else echo '<p class="error">При удалении эллемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                    ' . $el->LAST_ERROR . '</p>';
                        }
                    } else {
                        //Если удалять не нужно, то обновляем и выводим сообщение
                        if ($res = $el->Update($arItem['ID'], $arLoadProductArray))
                            echo "Запись обновлена: " .  $value['name'] . "<br />";
                        else echo 'Error: ' . $el->LAST_ERROR;
                    }
                } else {
                    if ($BRAND_ID = $el->Add($arLoadProductArray))
                        echo "Добавлена: " .  $value['name'] . "<br />";
                    else echo 'Error: ' . $el->LAST_ERROR;
                }
                $answer['StringTehDoc'][] = $value->id;
            }
            echo "</div>";
            $answer['StringTehDoc'] = implode(";", $answer['StringTehDoc']);
            if ($this->answer)
                $ost = $this->client->__soapCall("TehDocAnswer", array('parameters' => $answer));
            if (($ost->return == true)) {
                echo "<div class='suc'>Документации загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->tehdoc) . "</div>";
            } else
                echo "<div class='error'>Документации не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }

    function GetWarehouse()
    {
        try {
            $ost = $this->client->__soapCall("StockGet", array('parameters' => $this->arr_params));
            return $ost;
        } catch (Exception $e) {
            echo "<p class='error'><b>Получить данные по брендам из 1С не удалось: " . $e->getMessage() . "</b></p>";
            return false;
        }
    }

    function initWarehouse()
    {
        $BLOCK_ID = 13;
        $ost = $this->GetWarehouse();
        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            //Объект элемента в модели битрикса
            $el = new CIBlockElement;
            echo "<div class='suc'>";
            if(gettype($ost->return->warehouse)=='object')
                $ost->return->warehouse = array($ost->return->warehouse);
            foreach ($ost->return->warehouse as $key => $value) {
                if (empty($value->warehouse_name)) break;
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => $BLOCK_ID,
                    "CODE" => $value->warehouse_id,
                    "EXTERNAL_ID" => $value->warehouse_id,
                    "PROPERTY_VALUES" => array(77 => $value->warehouse_address),
                    "NAME" => $value->warehouse_name,
                    "ACTIVE" => "Y"
                );
                //проверяем наличие такого элемента
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID' => $BLOCK_ID,
                        "=CODE" => $value->warehouse_id,
                        "=EXTERNAL_ID" => $value->warehouse_id,
                    ),
                    false, false, array('ID'));
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    //Удаляем из битрикса элемент
                    if ($value->deletion_mark == true) {
                        //Проверяем права на удаление
                        if (CIBlock::GetPermission($BLOCK_ID) >= 'W') {
                            //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                            if (!CIBlockElement::Delete($arItem['ID'])) {
                                echo 'Запись удалена - ' . $arItem['ID'];
                            } else echo '<p class="error">При удалении эллемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                    ' . $el->LAST_ERROR . '</p>';
                        }
                    } else {
                        //Если удалять не нужно, то обновляем и выводим сообщение
                        if ($res = $el->Update($arItem['ID'], $arLoadProductArray))
                            echo "Запись обновлена: " . $value->warehouse_name . "<br />";
                        else echo 'Error: ' . $el->LAST_ERROR;
                    }
                } else {
                    if ($BRAND_ID = $el->Add($arLoadProductArray))
                        echo "Добавлена: " . $value->warehouse_name . "<br />";
                    else echo 'Error: ' . $el->LAST_ERROR;
                }

            }
            echo "</div>";
            if (($ost->return == true)) {
                echo "<div class='suc'>Склады загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->warehouse) . "</div>";
            } else
                echo "<div class='error'>Склады не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }

    function GetCategory()
    {
        try {
            $ost = $this->client->__soapCall("CategoryGet", array('parameters' => $this->arr_params));
            return $ost;
        } catch (Exception $e) {
            echo "<p class='error'><b>Получить данные из 1С не удалось: " .
                $e->getMessage() . "</b></p>";
            return false;
        }
    }

    function initCategory()
    {
        $ost = $this->GetCategory();

        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            define("CACHED_b_iblock_bucket_size", 3600);
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            $answer['StringCategory'] = array();
            //Объект элемента в модели битрикса
            $el = new CIBlockSection;
            echo "<div class='suc'>";
            if(gettype($ost->return->Category)=='object')
                $ost->return->Category = array($ost->return->Category);
            foreach ($ost->return->Category as $key => $value) {
                $arFilter = Array('IBLOCK_ID' => 1, 'EXTERNAL_ID' => $value->parent_id);
                $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, true);
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                $parent_id = false;
                if ($arItem = $rsItems->GetNext()) {
                    $parent_id = $arItem['ID'];
                }
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => $parent_id?$parent_id:($value->branch == 0 ? 9 : ($value->branch == 1 ? 2 : 1)),
                    "CODE" => $value->id,
                    "EXTERNAL_ID" => $value->id,
                    "NAME" => $value->name,
                    "ACTIVE" => "Y",
                    "IBLOCK_CODE" => $value->name,
                    "IBLOCK_ID" => 1,
                    "SORT" => 500,
                );
                //проверяем наличие такого элемента
                $arFilter = Array('IBLOCK_ID' => 1, 'EXTERNAL_ID' => $value->id);
                $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, true);
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    //Удаляем из битрикса элемент
                    if ($value->deletion_mark == true) {
                        //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                        if (CIBlockSection::Delete($arItem['ID'])) {
                            echo 'Запись удалена - ' . $arItem['ID'];
                        } else echo '<p class="error">При удалении эллемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                    ' . $el->LAST_ERROR . '</p>';
                    } else {
                        //Если удалять не нужно, то обновляем и выводим сообщение
                        if ($res = $el->Update($arItem['ID'], $arLoadProductArray))
                            echo "Запись обновлена: " . $value->name . " - " . $value->title . "<br />";
                        else echo 'Error: ' . $el->LAST_ERROR;
                    }
                } else {
                    if ($BRAND_ID = $el->Add($arLoadProductArray))
                        echo "Добавлена: " . $value->name . " - " . $value->title . "<br />";
                    else echo 'Error: ' . $el->LAST_ERROR;
                }
                $answer['StringCategory'][] = $value->id;
            }
            echo "</div>";
            $answer['StringCategory'] = implode(";", $answer['StringCategory']);
            $this->initPropname();
            if ($this->answer)
                $ost = $this->client->__soapCall("CategoryAnswer", array('parameters' => $answer));
            if (($ost->return == true)) {
                echo "<div class='suc'>Категории загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->category) . "</div>";
            } else
                echo "<div class='error'>Категории не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }

    function code1C2codeBitrix($code)
    {
        $code = 'd_' . $code;
        $code = str_replace('-', '_', $code);
        return $code;
    }

    function initPropname()
    {
        $ost = $this->GetCategory();
        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            define("CACHED_b_iblock_bucket_size", 3600);
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            //Объект элемента в модели битрикса
            $el = new CIBlockProperty;
            echo "<div class='suc'>";
            $get = array();
            if(gettype($ost->return->propname)=='object')
                $ost->return->propname = array($ost->return->propname);
            foreach ($ost->return->propname as $k => $v) {
                if ($get[$v->id]) {
                    $get[$v->id]['cat_id'][] = $v->cat_id;
                } else {
                    $get[$v->id] = array(
                        'name' => $v->name,
                        'cat_id' => array($v->cat_id),
                        'main' => $v->main,
                        'type' => $v->type,
                        'interval' => $v->interval
                    );
                }
            }
            foreach ($get as $key => $value) {
                $arFields = Array(
                    "NAME" => $value['name'],
                    "ACTIVE" => "Y",
                    "SORT" => "100",
                    "CODE" => $this->code1C2codeBitrix($key),
                    "PROPERTY_TYPE" => "L",
                    "IBLOCK_ID" => 1,
                    "VALUES" => array()
                );


                //проверяем наличие такого элемента
                $arFilter = Array('IBLOCK_ID' => 1, 'CODE' => $this->code1C2codeBitrix($key));
                $rsItems = CIBlockProperty::GetList(Array("SORT" => "ASC"), $arFilter);
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    //Удаляем из битрикса элемент
                    if ($value->deletion_mark == true) {
                        //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                        if (CIBlockProperty::Delete($arItem['ID'])) {
                            echo 'Запись удалена - ' . $arItem['ID'];
                        } else echo '<p class="error">При удалении эллемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                    ' . $el->LAST_ERROR . '</p>';
                    } else {
                        //Если удалять не нужно, то обновляем и выводим сообщение
                        if ($res = $el->Update($arItem['ID'], $arFields)){
                            echo "Запись обновлена: " . $value['name'] . "<br />";
                            foreach ($value['cat_id'] as $v) {
                                $arFilter = Array('IBLOCK_ID' => 1, 'CODE' => $v);
                                $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, true);
                                $tmp = new CIBlockSectionPropertyLink;
                                if ($arItem = $rsItems->GetNext()) {
                                    $tmp->Add($arItem['ID'], $arItem['ID'],
                                        $value['main'] ? array('SMART_FILTER' => 'Y') : array());
                                } else echo 'Error: не удалось связать блоки. ' . $tmp->LAST_ERROR . '\n';
                            }
                        }

                        else echo 'Error: ' . $el->LAST_ERROR;
                    }
                } else {
                    if ($BRAND_ID = $el->Add($arFields)) {
                        echo "Добавлена: " . $value['name'] . "" . "<br />";
                        foreach ($value['cat_id'] as $v) {
                            $arFilter = Array('IBLOCK_ID' => 1, 'CODE' => $v);
                            $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, true);
                            $tmp = new CIBlockSectionPropertyLink;
                            if ($arItem = $rsItems->GetNext()) {
                                $tmp->Add($arItem['ID'], $BRAND_ID,
                                    $value['main'] ? array('SMART_FILTER' => 'Y') : array());
                            } else echo 'Error: не удалось связать блоки. ' . $tmp->LAST_ERROR . '\n';
                        }
                    } else echo 'Error: ' . $el->LAST_ERROR;
                }
            }
            echo "</div>";
            $this->initProp();
            if (($ost->return == true)) {
                echo "<div class='suc'>Свойства загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->category) . "</div>";
            } else
                echo "<div class='error'>Свойства не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }

    function initProp()
    {
        $ost = $this->GetCategory();
        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            define("CACHED_b_iblock_bucket_size", 3600);
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            //Объект элемента в модели битрикса
            $el = new CIBlockProperty;
            echo "<div class='suc'>";
            $get = array();
            if(gettype($ost->return->prop)=='object')
                $ost->return->prop = array($ost->return->prop);
            foreach ($ost->return->prop as $key => $value) {
                if (!empty($value->name) && $value->name != '.' && $value->name != '-') {
                    $get[$value->propname_id][] = array('VALUE'=>$value->name, 'XML_ID'=>$this->code1C2codeBitrix($value->id));
                    $get[$value->propname_id] = array_unique($get[$value->propname_id]);
                }
            }
            foreach ($get as $key => $value) {
                $arFields = Array(
                    "VALUES" => $value
                );
                //проверяем наличие такого элемента
                $arFilter = Array('IBLOCK_ID' => 1, 'CODE' => $this->code1C2codeBitrix($key));
                $rsItems = CIBlockProperty::GetList(Array("SORT" => "ASC"), $arFilter);
                //Если эллемент уже существует, обновляем его
                if ($arItem = $rsItems->GetNext()) {
                    //Если удалять не нужно, то обновляем и выводим сообщение
                    if ($res = $el->Update($arItem['ID'], $arFields))
                        echo "Значение свойства добавлено: " . $value['name'] . "<br />";
                    else echo 'Error: ' . $el->LAST_ERROR;

                }
            }
            echo "</div>";
            if (($ost->return == true)) {
                echo "<div class='suc'>Значения свойств загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->category) . "</div>";
            } else
                echo "<div class='error'>Значения свойств не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }


    function GetProduct()
    {
        try {
            $ost = $this->client->__soapCall("ItemsGet", array('parameters' => $this->arr_params));
            return $ost;
        } catch (Exception $e) {
            echo "<p class='error'><b>Получить данные технической документации из 1С не удалось: " .
                $e->getMessage() . "</b></p>";
            return false;
        }
    }

    //выполняем все операции по документации
    function initProduct()
    {
        $BLOCK_ID = 1;
        $ost = $this->GetProduct();
        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            $answer['StringItems'] = array();
            //Объект элемента в модели битрикса
            $el = new CIBlockElement;
            echo "<div class='suc'>";
            if(gettype($ost->return->item)=='object')
                $ost->return->item = array($ost->return->item);
            foreach ($ost->return->item as $key => $value) {
                $section_id = -1;
                $arFilter = Array('IBLOCK_ID' => 1, 'EXTERNAL_ID' => $value->cat_id);
                $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, true);
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    $section_id = $arItem['ID'];
                }

                $id_breand = null;
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID' => 2,
                        "=CODE" => $value->brand_id,
                        "=EXTERNAL_ID" => $value->brand_id,
                    ),
                    false, false, array('ID'));
                if($arItem = $rsItems->GetNext()){
                    $id_breand = $arItem['ID'];
                }

                $id_col = null;
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID' => 22,
                        "=CODE" => $value->set_id,
                    ),
                    false, false, array('ID'));
                if($arItem = $rsItems->GetNext()){
                    $id_col = $arItem['ID'];
                }

                $prop = $value->properties;
                $propA = array();
                foreach ($prop as $vp) {
                    $property_enums = CIBlockPropertyEnum::GetList(
                        Array("DEF"=>"DESC", "SORT"=>"ASC"),
                        Array("IBLOCK_ID"=>1,
                            "CODE"=>$this->code1C2codeBitrix($vp->propname_id)));
                    while($enum_fields = $property_enums->GetNext())
                    {
                        if($enum_fields["CODE"]==$this->code1C2codeBitrix($vp->prop_id))
                            $propA[$this->code1C2codeBitrix($vp->propname_id)] = $enum_fields["ID"];
                    }
                }
                $propA[89]= $value->article;
//                $propA[]= $value->article;
//                65 => CFile::MakeFileArray($value->adress),
                $propA[352]=$id_col;
                $propA[4]=$id_breand;
                $propA[97]=$value->novelty?19:'';
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => $section_id,
                    "IBLOCK_ID" => $BLOCK_ID,
                    "CODE" => $value->id,
                    "EXTERNAL_ID" => $value->id,
                    "PROPERTY_VALUES" => $propA,
                    "NAME" => $value->name_full,
                    "ACTIVE" => "Y",
                    "DETAIL_TEXT" => $value->description
                );
                //проверяем наличие такого элемента
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID' => $BLOCK_ID,
                        "=CODE" => $value->id,
                        "=EXTERNAL_ID" => $value->id,
                    ),
                    false, false, array('ID'));
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    //Удаляем из битрикса элемент
                    if ($value->deletion_mark == true) {
                        //Проверяем права на удаление
                        if (CIBlock::GetPermission($BLOCK_ID) >= 'W') {
                            //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                            if (!CIBlockElement::Delete($arItem['ID'])) {
                                echo 'Запись удалена - ' . $arItem['ID'];
                            } else echo '<p class="error">При удалении эллемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                    ' . $el->LAST_ERROR . '</p>';
                        }
                    } else {
                        //Если удалять не нужно, то обновляем и выводим сообщение
                        if ($res = $el->Update($arItem['ID'], $arLoadProductArray))
                            echo "Запись обновлена: " . $value->name . "<br />";
                        else echo 'Error: ' . $el->LAST_ERROR;
                    }
                } else {
                    if ($BRAND_ID = $el->Add($arLoadProductArray))
                        echo "Добавлена: " . $value->name . "<br />";
                    else echo 'Error: ' . $el->LAST_ERROR;
                }
                $answer['StringItems'][] = $value->id;
            }
            echo "</div>";

            $isGo = false;
            if (count($answer['StringItems']) >= 100) {
                $isGo = true;
            }

            $answer['StringItems'] = implode(";", $answer['StringItems']);
            if ($this->answer) {
                $ost = $this->client->__soapCall("ItemsAnswer", array('parameters' => $answer));
                if ($isGo) $this->initProduct();
            }
            if (($ost->return == true)) {
                echo "<div class='suc'>Товары загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->item) . "</div>";
            } else
                echo "<div class='error'>Товары не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }


    function GetPrice()
    {
        try {
            $ost = $this->client->__soapCall("CostsGet", array('parameters' => $this->arr_params));
            return $ost;
        } catch (Exception $e) {
            echo "<p class='error'><b>Получить данные технической документации из 1С не удалось: " .
                $e->getMessage() . "</b></p>";
            return false;
        }
    }

    //выполняем все операции по документации
    function initPrice()
    {
        $BLOCK_ID = 1;
        $ost = $this->GetBreands();
        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            $answer['StringItems'] = array();
            //Объект элемента в модели битрикса
            $el = new CIBlockElement;
            echo "<div class='suc'>";
            if(gettype($ost->return->item)=='object')
                $ost->return->item = array($ost->return->item);
            foreach ($ost->return->item as $key => $value) {
                $section_id = -1;
                $arFilter = Array('IBLOCK_ID' => 1, 'EXTERNAL_ID' => $value->cat_id);
                $rsItems = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, true);
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    $section_id = $arItem['ID'];
                }
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => $section_id,
                    "IBLOCK_ID" => $BLOCK_ID,
                    "CODE" => $value->id,
                    "EXTERNAL_ID" => $value->id,
                    "PROPERTY_VALUES" => array(
                        89 => $value->article,
                        65 => CFile::MakeFileArray($value->adress)
                    ),
                    "NAME" => $value->name_full,
                    "ACTIVE" => "Y",
                    "DETAIL_TEXT" => $value->description
                );
                //проверяем наличие такого элемента
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID' => $BLOCK_ID,
                        "=CODE" => $value->id,
                        "=EXTERNAL_ID" => $value->id,
                    ),
                    false, false, array('ID'));
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    //Удаляем из битрикса элемент
                    if ($value->deletion_mark == true) {
                        //Проверяем права на удаление
                        if (CIBlock::GetPermission($BLOCK_ID) >= 'W') {
                            //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                            if (!CIBlockElement::Delete($arItem['ID'])) {
                                echo 'Запись удалена - ' . $arItem['ID'];
                            } else echo '<p class="error">При удалении эллемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                    ' . $el->LAST_ERROR . '</p>';
                        }
                    } else {
                        //Если удалять не нужно, то обновляем и выводим сообщение
                        if ($res = $el->Update($arItem['ID'], $arLoadProductArray))
                            echo "Запись обновлена: " . $value->name . "<br />";
                        else echo 'Error: ' . $el->LAST_ERROR;
                    }
                } else {
                    $answer['StringItems'][] = $value->id;
                    if ($BRAND_ID = $el->Add($arLoadProductArray))
                        echo "Добавлена: " . $value->name . "<br />";
                    else echo 'Error: ' . $el->LAST_ERROR;
                }
            }
            echo "</div>";
            $answer['StringItems'] = implode(";", $answer['StringTehDoc']);
            if ($this->answer) {
                if (count($ost->return->item) >= 300) {
                    $this->initProduct();
                }
                $ost = $this->client->__soapCall("ItemsAnswer", array('parameters' => $answer));
            }
            if (($ost->return == true)) {
                echo "<div class='suc'>Товары загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->item) . "</div>";
            } else
                echo "<div class='error'>Товары не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }


    //выполняем все операции по брендам
    function initCollection()
    {
        $BLOCK_ID = 22;
        $ost = $this->GetBreands();
        //Битрикс подключился?
        if (CModule::IncludeModule("iblock")) {
            $answer = array();
            $answer['ID_Portal'] = 'HG';
            //Объект элемента в модели битрикса
            $el = new CIBlockElement;
            echo "<div class='suc'>";
            if(gettype($ost->return->set)=='object')
                $ost->return->set = array($ost->return->set);
            foreach ($ost->return->set as $key => $value) {
                $id_br = -1;
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID' => 2,
                        "=CODE" => $value->brand_id,
                        "=EXTERNAL_ID" => $value->brand_id,
                    ),
                    false, false, array('ID'));
                if ($arItem = $rsItems->GetNext()) {
                    $id_br = $arItem["ID"];
                }
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => $BLOCK_ID,
                    "CODE" => $value->id,
                    "EXTERNAL_ID" => $value->id,
                    "PROPERTY_VALUES" => array(155 => $id_br),
                    "NAME" => $value->name,
                    "ACTIVE" => "Y"
                );
                //проверяем наличие такого элемента
                $rsItems = CIBlockElement::GetList(array(),
                    array(
                        'IBLOCK_ID' => $BLOCK_ID,
                        "=CODE" => $value->id,
                        "=EXTERNAL_ID" => $value->id,
                    ),
                    false, false, array('ID'));
                //Если эллемент уже существует, обновляем его или удаляем, при условии что флаг установлен
                if ($arItem = $rsItems->GetNext()) {
                    //Удаляем из битрикса элемент
                    if ($value->deletion_mark == true) {
                        //Проверяем права на удаление
                        if (CIBlock::GetPermission($BLOCK_ID) >= 'W') {
                            //Удаляем и выводим сообщение о выполнении, иначе показываем ошибку
                            if (!CIBlockElement::Delete($arItem['ID'])) {
                                echo 'Запись удалена - ' . $arItem['ID'];
                            } else echo '<p class="error">При удалении эллемента произошла ошибка [' . $arItem['ID'] . ']<br>
                                    ' . $el->LAST_ERROR . '</p>';
                        }
                    } else {
                        //Если удалять не нужно, то обновляем и выводим сообщение
                        if ($res = $el->Update($arItem['ID'], $arLoadProductArray))
                            echo "Запись обновлена: " . $value->name . " - " . $value->title . "<br />";
                        else echo 'Error: ' . $el->LAST_ERROR;
                    }
                } else {
                    if ($BRAND_ID = $el->Add($arLoadProductArray))
                        echo "Добавлена: " . $value->name . " - " . $value->title . "<br />";
                    else echo 'Error: ' . $el->LAST_ERROR;
                }

            }
            echo "</div>";
            if (($ost->return == true)) {
                echo "<div class='suc'>Колекции загружены</div>";
                echo "<div class='info'>В количестве: " . count($ost->return->brand) . "</div>";
            } else
                echo "<div class='error'>Колекции не загружены</div>";
        } else echo '<div class="error">1C Битрикс не загружен</div>';
    }


}