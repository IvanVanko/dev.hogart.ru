<?
    set_time_limit(0);
    require_once("../bitrix/modules/main/include/prolog_before.php");
    CModule::IncludeModule("iblock");
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детальная информация</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<form action="" method="get">
    <input type="submit" name="start" value="Старт">
</form>
<pre>
<? if (!empty($_GET['start'])): ?>
    <?
    try {
        $rs = CIBlockElement::GetList(
            array(),
            array('IBLOCK_ID' => 1),
            false,
            false,
            array('IBLOCK_ID', 'ID', 'DETAIL_PICTURE', 'PREVIEW_PICTURE')
        );

        while ($cat = $rs->GetNext()) {
            $el  = new CIBlockElement;
            $res = CIBlockElement::GetProperty(1, $cat["ID"], array(), array("CODE" => "photos"));
            $arr = array();

            CFile::Delete($cat['DETAIL_PICTURE']);
            CFile::Delete($cat['PREVIEW_PICTURE']);

            while ($ob = $res->GetNext()) {
                $arr[$ob['PROPERTY_VALUE_ID']] = Array("VALUE" => Array("del" => "Y"));
                CFile::Delete($ob['VALUE']);
                echo 'Файл удален: '.$ob['VALUE'].'<br>';
            }

            CIBlockElement::SetPropertyValues( $cat['ID'], 1, $arr, "PHOTOS");

            $el->Update(
                $cat['ID'],
                array(
                    'DETAIL_PICTURE' => array('del' => 'Y'),
                    'PREVIEW_PICTURE' => array('del' => 'Y'),
                    'PROPERTY_VALUES' => array(
                        'PHOTOS' => $arr
                    )
                )
            );
        }
    }
    catch (Exception $e) {
        echo 'error: ';
        echo $e->getMessage();
    }

    ?>
<? endif; ?>
</pre>
</body>
</html>