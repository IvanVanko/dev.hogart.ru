<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('ParsingModel.php');
ini_set("xdebug.var_display_max_depth", -1);
$parce = new ParsingModel(!empty($_GET['dump']) ? $_GET['dump'] : false);
if ($_GET['answer'] == 'Y') {
    $parce->answer = true;
}
else {
    $parce->answer = false;
}
?>
    <!doctype html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Загрузка данных с веб-сервера</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700&subset=cyrillic,latin'
              rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <div class="logo">
        <a href="/k_1c_upload/"><img src="/images/logo_hide_menu@2x.png" alt=""/></a>
    </div>
    <div class="info" id="work">
        <a href="detail.php">Детальная страница по категориям и продуктам</a>
        <table width="95%" class="simple-little-table">
            <?
            $state = $parce->csv->getArrayState();
            ?>
            <tbody>
            <tr>
                <th></th>
                <th>Просмотреть ответы от сервера</th>
                <!--                <th>Загрузить без ответа серверу</th>-->
                <th>Загрузить с ответом серверу</th>
                <th>Количество элементов</th>
                <th>Время последнего запроса</th>
                <th>Ошибочные данные в пакете</th>
            </tr>
            <tr>
                <th>Бренды<br>
                    <small>+колекции</small>
                </th>
                <td><a href="?q=Vbreands">Посмотреть</a></td>
                <td><a href="?q=breands&answer=Y">Загрузить и ответить</a></td>
                <td>
                    <?= $parce->getCountEl(2); ?> брендов<br>
                    <?= $parce->getCountEl(22); ?> колекций
                </td>
                <td><?= $state['breands']; ?></td>
            </tr>
            <tr>
                <th>Категории <br>
                    <small>Характеристики и значения характеристик</small>
                </th>
                <td>
                    <a href="?q=Vcategory">Посмотреть</a><br>
                    <a href="?q=Vbranch">Посмотреть главные категории</a>
                </td>
                <td>
                    <a href="?q=category&answer=Y">Загрузить и ответить</a><br>
                    <a href="?q=branch&answer=Y">Загрузить главные категории и ответить</a>
                </td>
                <td>
                    <?= $parce->getCountSec(1); ?> категорий<br>
                    <?= $parce->getCountProp(1); ?> свойств
                </td>
                <td><?= $state['category']; ?></td>
            </tr>
            <tr>
                <th>Продукты</th>
                <td><a href="?q=Vproduct">Посмотреть</a></td>
                <td><a href="?q=product&answer=Y">Загрузить и ответить</a></td>
                <td><?= $parce->getCountEl(1) ?> продуктов</td>
                <td><?= $state['product']; ?></td>
            </tr>
            <tr>
                <th>Цены для продуктов</th>
                <td><a href="?q=Vprice">Посмотреть</a></td>
                <td>
                    <a href="?q=price&answer=Y">Загрузить и ответить</a>
                </td>
                <td></td>
                <td><?= $state['price']; ?></td>
            </tr>
            <tr>
                <th>Техдокументация</th>
                <td>
                    <a href="?q=Vtehdoc">Посмотреть</a>
                    <a href="?q=VTypeTehDocGet">Посмотреть Типы</a>
                </td>
                <td>
                    <a href="?q=tehdoc&answer=Y">Загрузить и ответить</a><br>
                    <a href="?q=TypeTehDocGet&answer=Y">Загрузить Типы и ответить</a>
                </td>
                <td><?= $parce->getCountEl(10); ?> документаций</td>
                <td><?= $state['tehdoc']; ?></td>
                <td>
                    <a href="?q=tehdoc&V=Y">Посмотреть ошибочные данные</a><br>
                    <a href="?q=tehdoc&P=Y&V=Y">Удалить ошибочные данные</a>
                </td>
            </tr>

            <tr>
                <th>Склады</th>
                <td>
                    <a href="?q=Vwarehouse">Посмотреть</a>
                </td>
                <td>
                    <a href="?q=warehouse">Загрузить</a><br>
                    <!--                    <a href="?q=warehouse&answer=Y">Загрузить и ответить</a><br>-->
                </td>
                <td></td>
                <td><?= $state['warehouse']; ?></td>
            </tr>
            </tbody>
        </table>
        <input type="checkbox" id="isDump" <?= ($_GET['dump'] ? 'checked' : ''); ?>
               onchange="showOrHide(this)"> Делать дамп ответов?
        <br>
    </div>
    <script>
        function showOrHide(cb) {
            if (cb.checked) {
                var els = document.getElementById('work').getElementsByTagName('a');
                for (var i = 0; i < els.length; i++) {
                    var attr = els[i].getAttribute('href');
                    els[i].setAttribute('href', attr + '&dump=dumpFolder');
                }
            }
            else {
                var els = document.getElementById('work').getElementsByTagName('a');
                for (var i = 0; i < els.length; i++) {
                    var attr = els[i].getAttribute('href');
                    attr = attr.replace('&dump=dumpFolder', '');
                    els[i].setAttribute('href', attr);
                }
            }
        }
    </script>
    <?php
    $parce->showFunctionSoap();
    if ($parce->isReadySoap()) {
        $q = explode(',', $_GET['q']);
        foreach ($q as $key => $val) {

            if ($val == "breands" or $val == "*") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div>';
                $parce->csv->saveState('breands');
                $parce->initBrands();
            }
            if ($val == "tehdoc" or $val == "*") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div>';
                $parce->csv->saveState('tehdoc');
                $parce->initTehDoc();
            }
            if ($val == "TypeTehDocGet" or $val == "*") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div>';
                $parce->csv->saveState('TypeTehDocGet');
                $parce->initTypeTechDoc();
            }
            if ($val == "warehouse" or $val == "*") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div>';
                $parce->csv->saveState('warehouse');
                $parce->initWarehouse();
            }

            if ($val == "category" or $val == "*") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div>';
                $parce->csv->saveState('category');
                $parce->initCategory();
            }
            if ($val == "branch" or $val == "*") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div>';
                $parce->csv->saveState('branch');
                $parce->initBranch();
            }
            if ($val == "product" or $val == "*") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div>';
                $parce->csv->saveState('product');
                $parce->initProduct();
            }

            if ($val == "price" or $val == "*") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div>';
                $parce->csv->saveState('price');
                $parce->initPrice();
            }


            if ($val == "Vbreands") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('BrandGet'));
                echo '</pre>';
            }
            if ($val == "VTypeTehDocGet") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('TypeTehDocGet'));
                echo '</pre>';
            }
            if ($val == "Vtehdoc") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('TehDocGet'));
                echo '</pre>';
            }
            if ($val == "Vwarehouse") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('StockGet'));
                echo '</pre>';
            }

            if ($val == "Vcategory") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('CategoryGet'));
                echo '</pre>';
            }
            if ($val == "Vbranch") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('BranchGet'));
                echo '</pre>';
            }
            if ($val == "Vproduct") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('ItemsGet'));
                echo '</pre>';
            }

            if ($val == "Vprice") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('CostsGet'));
                echo '</pre>';
            }

            if ($val == "Vtypetehdoc") {
                echo '<div class="info"><h2>[' . $key . '] ' . $val . '</h2></div><pre>';
                var_dump($parce->GetResultFunction('TypeTehDocGet'));
                echo '</pre>';
                //                echo '<div class="error">Ошибка, метод критически не доработан</div>';
            }
        }
        if (count($q) == 0 or $q[0] == '') {
            echo "<div class='error'>Передайте параметры</div>";
        }
    }
    ?>
    </body>
    </html>
<?php
require("../bitrix/modules/main/include/epilog_after.php");
?>