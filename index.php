<? require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("title", "Хогарт - официальный сайт.");
$APPLICATION->SetPageProperty("keywords", "Хогарт - официальный сайт.");
$APPLICATION->SetPageProperty("description", "Хогарт - официальный сайт.");
$APPLICATION->SetTitle("Хогарт - официальный сайт.");

?>

<div class="b-wrapper">
    <main class="b-main">
        <div class="b-slider">
            <?$APPLICATION->IncludeComponent("bitrix:advertising.banner","bootstrap",Array(
                    "TYPE" => "index_top",
                    "CACHE_TYPE" => "N",
                    "NOINDEX" => "Y",
                    "CACHE_TIME" => "0",
                    "BS_CYCLING" => "Y",
                    "BS_INTERVAL" => 3000,
                    "BS_PAUSE" => "Y",
                    "BS_WRAP" => "Y",
                    "BS_BULLET_NAV" => "Y",
                    "BS_EFFECT" => "slide",
                    "BS_HIDE_FOR_PHONES" => "Y"
                )
            );?>
        </div>


        <div class="b-catalog-main">
            <div class="b-catalog-main__title">
                <h2>
                    <a href="/catalog/" class="b-title-link b-title-link--catalog" title="Каталог">Каталог товаров</a>
                    <a href="/documentation/" class="b-title-link b-title-link--catalog" title="Документация">Документация</a>
                    <a href="/price/" class="b-title-link b-title-link--catalog" title="Прайс-лист">Прайс-лист (pdf)</a>
                </h2>
            </div>
            <? include($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/catalog_menu.php'); ?>
        </div>

        <?
        $date = new DateTime();
        date_sub($date, date_interval_create_from_date_string('2 month'));
        $APPLICATION->IncludeComponent("kontora:element.list", "index_news", array(
            "LINK" => SITE_DIR . "company/news/",
            'IBLOCK_ID' => (LANGUAGE_ID == 'en' ? '28' : '3'),
            'FILTER' => array(
                ">=DATE_ACTIVE_FROM" => date_format($date, 'd-m-Y')." 00:00:00",
                "PROPERTY_INDEX_SHOW" => "Y",
                ">PREVIEW_PICTURE" => 0
            ),
            "CHECK_PERMISSIONS" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0",
            "PROPS" => "Y",
            'ORDER' => array('property_priority' => 'asc,nulls', 'active_from' => 'desc'),
            'ELEMENT_COUNT' => 3,
            'TITLE_NAME' => 'Новости'
        ));

        $APPLICATION->IncludeComponent("kontora:element.list", "index_brands", array(
            "LINK" => SITE_DIR . "brands",
            'IBLOCK_ID' => BRAND_IBLOCK_ID,
            'FILTER' => array(
                "ACTIVE" => "Y",
                ">PROPERTY_INDEX_LOGO" => 0
            ),
            "CHECK_PERMISSIONS" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0",
            "PROPS" => "Y",
            'TITLE_NAME' => 'Бренды'
        ));

        $APPLICATION->IncludeComponent("kontora:element.list", "index_news", array(
            "LINK" => SITE_DIR . "company/stock/",
            'IBLOCK_ID' => (LANGUAGE_ID == 'en' ? '34' : '6'),
            'FILTER' => array(
                ">=DATE_ACTIVE_FROM" => date_format($date, 'd-m-Y')." 00:00:00",
                "PROPERTY_INDEX_SHOW" => "Y",
                ">PREVIEW_PICTURE" => 0
            ),
            "CHECK_PERMISSIONS" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "0",
            "PROPS" => "Y",
            'ORDER' => array('property_priority' => 'asc,nulls', 'active_from' => 'desc'),
            'ELEMENT_COUNT' => 3,
            'TITLE_NAME' => 'Акции'
        ));
        ?>
        <!-- div class="b-history">
            <h2>
                <a href="/company/" class="b-title-link" title="О компании">О компании</a>
            </h2>

            <p>Компания Хогарт, основанная в&nbsp;1996 году на&nbsp;сегодняшний день является одним из&nbsp;крупнейших поставщиков инженерных систем в&nbsp;области отопления, вентиляции и&nbsp;сантехники.</p>
            <p>Компания имеет собственный офисно-складской комплекс, два салона розничных продаж в&nbsp;Москве и&nbsp;офисно-складской комплекс в&nbsp;Санкт-Петербурге.</p>
            <p>За&nbsp;20&nbsp;лет работы нам доверили поставку оборудования более 50&nbsp;000 различных компаний. Компания &laquo;Хогарт&raquo; является членом апик и&nbsp;авок, активно содействуя развитию отрасли, вместе с&nbsp;другими ведущими игроками климатической отрасли.</p>
        </div-->
    </main>
</div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
