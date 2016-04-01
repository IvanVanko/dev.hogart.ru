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
<form action="refactor.php" method="get">
    <input type="submit" name="start" value="Старт">
</form>

<? if (!empty($_GET['start'])): ?>
    <?
    try {
        $rs = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 1), false, array('ID', 'NAME'));
        while ($cat = $rs->GetNext()) {
            $smart = CIBlockSectionPropertyLink::GetArray(1, $cat['ID'], true);
            foreach ($smart as $k => $v) {
                $t = new CIBlockSectionPropertyLink;
                $t->Delete($cat['ID'], $v['PROPERTY_ID']);
                $t->Add($cat['ID'], $v['PROPERTY_ID'],
                        array(
                            'SMART_FILTER' => 'Y',
                            'DISPLAY_EXPANDED' => $v['SMART_FILTER']
                        ));
                echo 'field update: '.$cat['ID'].' - '.$v['PROPERTY_ID'].'<br>';
            }
        }
    }
    catch (Exception $e) {
        echo 'error: ';
        echo $e->getMessage();
    }

    ?>
<? endif; ?>
</body>
</html>