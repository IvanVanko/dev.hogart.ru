<? require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("title", "Хогарт - официальный сайт.");
$APPLICATION->SetPageProperty("keywords", "Хогарт - официальный сайт.");
$APPLICATION->SetPageProperty("description", "Хогарт - официальный сайт.");
$APPLICATION->SetTitle("Хогарт - официальный сайт.");


$catalogMenu = $APPLICATION->IncludeComponent(
    "bitrix:menu.sections",
    "",
    array(
        "IS_SEF" => "Y",
        "SEF_BASE_URL" => "/catalog/",
        "SECTION_PAGE_URL" => "#bx_cat_#SECTION_ID#",
        "DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_ID#/",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "1",
        "DEPTH_LEVEL" => "1",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "INCLUDE_SUBSECTIONS" => "Y"
    ),
    false
);
?>

<div class="b-wrapper">
    <main class="b-main">
        <div class="b-slider">
            <div class="b-slider__content b-slider__content--main js-slider-main-page">
                <div class="b-slider__item b-slider__item--main">
                    <img src="/images/banner-hogart-1.jpg" alt="" title="" />
                </div>
                <div class="b-slider__item b-slider__item--main">
                    <img src="/images/banner_hogart3.jpg" alt="" title="" />
                </div>
                <div class="b-slider__item b-slider__item--main">
                    <img src="/images/banner_hogart3.jpg" alt="" title="" />
                </div>
            </div>
        </div>


        <div class="b-catalog-main">
            <div class="b-catalog-main__title">
                <h2>
                    <a href="/catalog/" class="b-title-link b-title-link--catalog" title="Каталог">Каталог</a>
                    <a href="/documentation/" class="b-title-link b-title-link--catalog" title="Документация">Документация</a>
                    <a href="#" class="b-title-link b-title-link--catalog" title="Прайс-лист">Прайс-лист</a>
                </h2>
            </div>
            <ul class="b-catalog-main__list">
                <? foreach ($catalogMenu as $catalogItem): ?>
                    <li class="b-catalog-main__item">
                        <a href="<?= $catalogItem[1] ?>" class="b-catalog-main__link" title="<?= $catalogItem[0] ?>">
                            <? if (!empty($catalogItem[3]['ICON'])): ?>
                                <? $file = CFile::ResizeImageGet($catalogItem[3]['ICON'], array('width' => 300, 'height' => 300), BX_RESIZE_IMAGE_EXACT, true); ?>
                                <img src="<?= $file['src']; ?>" alt="<?= $catalogItem[0] ?>">
                            <? endif; ?>
                            <span><?= $catalogItem[0] ?></span>
                        </a>
                        <a href="<?= preg_replace("%bx_cat%", "heating", $catalogItem[1]) ?>" class="b-catalog-main__link b-catalog-main__link__mobile" title="<?= $catalogItem[0] ?>">
                            <? if (!empty($catalogItem[3]['ICON'])): ?>
                                <? $file = CFile::ResizeImageGet($catalogItem[3]['ICON'], array('width' => 300, 'height' => 300), BX_RESIZE_IMAGE_EXACT, true); ?>
                                <img src="<?= $file['src']; ?>" alt="<?= $catalogItem[0] ?>">
                            <? endif; ?>
                            <span><?= $catalogItem[0] ?></span>
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>
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
