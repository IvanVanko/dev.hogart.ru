<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?
try {
    //Создаём SOAP-клиент по WSDL-документу
    $client = new SoapClient(
//		"https://89.111.54.152/TestAndrew/ws/Site.1cws?wsdl"
        "https://89.111.54.152/production/ws/Site.1cws?wsdl"
        , array(
            'login' => "Outside"
        , 'password' => "23rehcbcnrbghjikb24nsczxbrbkjvtnhjd"
        )
    );
    print_r($client);
    //создаём ассоциативный массив с названием ключа, совпадающим
    //с названием параметра операции веб-сервиса и передаём значение id
//	$arr_params['id'] = $_POST['id'];
    $arr_params = array();
    $arr_params['ID_Portal'] = 'HG';
    //заключаем подключение в сервису в try catch, чтобы страничка товара
    //нормально отобразилась, на случай неудачного подключения


    //require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

    echo "Вывод инфы:<br /><br />";
    echo "<PRE>";

    if (true) {
//    if (CModule::IncludeModule("iblock")) {

        try {
            $func_list = $client->__getFunctions();

            if (!count($func_list) > 0) die();

			var_dump($func_list);
        } catch (Exception $e) {
            echo "<p><b>Получить данные из 1С не удалось: " . $e->getMessage() . "</b></p>";
        }


        /*
         * Блок работы с выгрузкой по элементам "Документация"
         *
         *
         */
        /*/
                try {
                    $ost = $client->__soapCall("TehDocGet", array('parameters' => $arr_params));

                    var_dump($ost);
                } catch (Exception $e) {
                    echo "<p><b>Получить данные из 1С не удалось: ".$e->getMessage()."</b></p>";
                }
        /**/

        /*
         * Блок работы с выгрузкой по элементам "Товар"
         *
         *
         */
        /*/
                try {
                    $ost = $client->__soapCall("ItemsGet", array('parameters' => $arr_params));

                    var_dump($ost);
                } catch (Exception $e) {
                    echo "<p><b>Получить данные из 1С не удалось: ".$e->getMessage()."</b></p>";
                }
        /**/

        /*
         * Блок работы с выгрузкой по элементам "Бренд"
         * Result: $ost->return->brand
         * Ответ: $ost->return
         *
         * Объект бренда:
         * 	object(stdClass) {
         * 		["id"]             => string(36) "8024e104-768f-11de-8104-00155d0a1e01"
         * 		["name"]           => string(6) "Reflex"
         * 		["visibility"]     => bool(true)
         * 		["titel"]          => string(6) "Reflex"
         * 		["set_collection"] => string(10) "Серия"
         * 		["deletion_mark"]  => bool(false)
         * 	}
         *
         * Объект коллекции:
         *	object(stdClass)#894 (3) {
         *		["id"]       => string(36) "0bc4eef9-a696-11e2-8a69-003048b99ee9"
         *		["name"]     => string(6) "Velvet"
         *		["brand_id"] => string(36) "a124a877-8706-11de-b907-00155d0a1e01"
         *	}
         */
        /**/
        try {
            $ost = $client->__soapCall("TypeTehDocGet", array('parameters' => $arr_params));
            $arr_params2 = array();
            $arr_params2['ID_Portal'] = 'HG';
            $arr_params2['StringBrands'] = array();

			var_dump($ost);
/*/
            $el = new CIBlockElement;
            foreach ($ost->return->brand as $key => $value) {
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => 2,
                    "CODE" => $value->id,
                    "EXTERNAL_ID" => $value->id,
                    "PROPERTY_VALUES" => array(),
                    "NAME" => $value->titel,
                    "ACTIVE" => ($value->visibility) ? "Y" : "N",
                );
                $BRAND_ID = $el->Add($arLoadProductArray);
                //var_dump($BRAND_ID);
                $arr_params2['StringBrands'][] = $value->id;
                echo "Добавлена: " . $value->name . " - " . $value->titel . "<br />";
            }
            echo "<br /><br />";

            $arr_params2['StringBrands'] = implode(";", $arr_params2['StringBrands']);
            //echo $arr_params2['StringBrands'] . "<br />";
            $ost = $client->__soapCall("BrandAnswer", array('parameters' => $arr_params2));

            echo "Бренды загружены: " . (($ost->return == true) ? "Отлично" : "Ошибка") . " <br />";
            echo "В количестве: " . count($ost->return->brand) . "<br />";
/**/
        } catch (Exception $e) {
            echo "<p><b>Получить данные по брендам из 1С не удалось: " . $e->getMessage() . "</b></p>";
        }
        /**/

        echo "<br />";

    }

    //require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");

} catch (SoapFault $e) {
    echo $e->getMessage();
}

?>
</body>
</html>