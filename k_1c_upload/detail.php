<?
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
<form action="detail.php" method="get">
    <input placeholder="Код продукта или категории" type="text" name="hash" value="<?=$_GET['hash'];?>">
    <select name="type">
        <option value="category" <?=$_GET['type'] == 'category' ? 'selected' : '';?>>Категории</option>
        <option value="product" <?=$_GET['type'] == 'product' ? 'selected' : '';?>>Продукты</option>
    </select>
    <input type="submit" value="Показать">
</form>

<? if ($_GET['type'] == 'category'): ?>

    <?
    $rs  = CIBlockSection::GetList(array(), array('EXTERNAL_ID' => $_GET['hash'], 'IBLOCK_ID' => 1));
    $cat = $rs->GetNext();
    ?>
    <h1>[<?=$cat['ID']?>] <?=$cat['NAME']?></h1>
    <div class="info">
        Всего подразделов:
        <?
            $rs = CIBlockSection::GetList(
                array('LEFT_MARGIN' => 'ASC'),
                array(
                    'IBLOCK_ID' => 1,
                    '>LEFT_MARGIN' => $cat['LEFT_MARGIN'],
                    '<RIGHT_MARGIN' => $cat['RIGHT_MARGIN'],
                )
            );
            echo $rs->SelectedRowsCount() - 1;
        ?>
    </div>
    <div class="info">Всего продуктов:
        <?
            $items = CIBlockElement::GetList(Array(), Array("SECTION_ID" => $cat['ID'], "INCLUDE_SUBSECTIONS" => "Y"),
                                             false, false, array("ID")
            );
            echo $items->SelectedRowsCount();
        ?>
    </div>

    <div class="info">
        <?
            $arr_prop   = array();
            $properties = CIBlockProperty::GetList(
                Array(),
                Array("IBLOCK_ID" => 1));
            while ($p = $properties->GetNext()) {
                $arr_prop[$p["ID"]] = array('name' => $p["NAME"], 'code' => $p['CODE'], 'color' => '#fff');
            }



            $smart = CIBlockSectionPropertyLink::GetArray(1, $cat['ID'], false);
            foreach ($smart as $v) {
                if ($arr_prop[$v['PROPERTY_ID']]) {
                    $arr_prop[$v['PROPERTY_ID']]['color'] = '#FFDDD6';
                    if ($v['DISPLAY_EXPANDED'] == 'Y') {
                        $arr_prop[$v['PROPERTY_ID']]['color'] = '#E6F1D5';
                    }
                }
            }

        ?>

        <div class="cube" style="background: #fff">Свойство инфоблока</div>
        <div class="cube" style="background: #FFDDD6">Свойство в разделе</div>
        <div class="cube" style="background: #E6F1D5">Свойство в смарт фильтре</div>

        <table class="simple-little-table">
            <tr>
                <th>ID</th>
                <th>Хеш</th>
                <th>Имя</th>
            </tr>
            <?foreach ($arr_prop as $key => $val):?>
                <tr style="background: <?=$val['color']?>">
                    <?
                        $color = $val['color'];
                        if($val['code'][0].$val['code'][1] != 'd_'){
                            $color = '#fff';
                        }
                    ?>
                    <td style="background: <?=$val['color']?>"><?=$key;?></td>
                    <td style="background: <?=$color;?>"><?=$val['code'];?></td>
                    <td style="background: <?=$color;?>"><?=$val['name'];?></td>
                </tr>
            <? endforeach; ?>
        </table>
    </div>
<? endif; ?>
</body>
</html>