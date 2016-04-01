<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if (count($arResult['ITEMS_BY_SECTIONS']) > 0):?>
    <?$elements_html = ""?>
    <div class="inner selection-equip-line">
        <div class="row">
            <div class="col2"><h2>Опросные листы</h2></div>
            <div class="col2">
                <ul class="selection-equip-tab js-tabs-list">
                    <li><a href="#tab_all" data-show="selection-equip-group" class="js-tab-trigger">
                            Показать все листы
                        </a></li>
                    <?foreach ($arResult["ITEMS_BY_SECTIONS"] as $key => $arItems):?>
                        <li>
                            <a href="#tab<?=$key?>" data-group="selection-equip-group" class="js-tab-trigger">
                                <?=$arResult["SECTIONS"][$key]['NAME']?>
                            </a>
                        </li>
                        <?

                        $elements_html .= "<ul class=\"js-tab-item file-list\" data-id=\"#tab_all\">";
                        foreach ($arResult["ITEMS"] as $arItem) {
                            $elements_html .= "<li><a class=\"icon-acrobat\" href=\"".$arItem['DISPLAY_PROPERTIES']['file']['FILE_VALUE']['SRC']."\">".$arItem['NAME']."</a></li>";
                        }
                        $elements_html .= "</ul>";

                        $elements_html .= "<ul class=\"js-tab-item file-list\" data-id=\"#tab".$key."\">";
                        foreach ($arItems as $arItem) {
                            $elements_html .= "<li><a class=\"icon-acrobat\" href=\"".$arItem['DISPLAY_PROPERTIES']['file']['FILE_VALUE']['SRC']."\">".$arItem['NAME']."</a></li>";
                        }
                        $elements_html .= "</ul>";
                        ?>
                    <?endforeach;?>
                </ul>
            </div>
        </div>
    </div>
    <div class="inner no-full">
        <?print($elements_html);?>
    </div>
<?endif; ?>