<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $page = $APPLICATION->GetCurDir(true); ?>

<div class="row">
    <div class="col-md-9 col-sm-12 col-xs-12">
        <h3><?= $APPLICATION->GetTitle() ?></h3>
        <h5><a class="color-green" data-toggle="tooltip" data-placement="right" title="Информация о бренде <?= $arParams["BRAND_NAME"] ?>" href="/brands/<?= $_REQUEST["ELEMENT_CODE"] ?>/">О бренде <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></h5>
        <div class="row vertical-align">
            <div class="col-md-6">
                <? $count = count($arResult['ITEMS']) ?>
                <div class="h5">Найдено <?= $count ?> <?= number($count, array('документ', 'документа', 'документов')); ?></div>
            </div>
            <div class="col-md-6 text-right">
                <a href="/arch/" class="icon-doc-sc"><i class="fa fa-file-archive-o" aria-hidden="true"></i> Скачать выбранные</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 doc-loadlist">
                <? $type = null; ?>
                <? foreach ($arResult["ITEMS"] as $i => $arItem): ?>
                    <? if ($type !== $arItem["PROPERTIES"]["type"]["VALUE"]): ?>
                        <? if (null !== $type): ?>
                            </div></div>
                        <? endif; ?>
                        <div class="item-box">
                            <? $type = $arItem["PROPERTIES"]["type"]["VALUE"]; ?>
                            <li class="li-container head_sub" data-type="<?= $arItem["PROPERTIES"]["type"]["VALUE_XML_ID"] ?>">
                                <div class="checkbox">
                                    <label class="h3 pull-left documentation__label">
                                        <input type="checkbox"
                                               name="doc_brands_<?= $i ?>"
                                               id="doc_brands_<?= $i ?>"
                                        > 
                                    </label>
                                    <h3 class="pull-left documentation__title" data-toggle="collapse" data-target="#<?= $arItem["PROPERTIES"]["type"]["VALUE_XML_ID"] ?>" aria-expanded="true" aria-controls="collapseTypes">
                                        <?= $type ?> (<?= $arResult['TYPE_COUNTS'][$type] ?>)
                                    </h3>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                            <div style="overflow: hidden;" class="collapse <?= ($i <= 0 ? "in" : "") ?>" aria-expanded="<?= ($i <= 0 ? "true" : "false") ?>" id="<?= $arItem["PROPERTIES"]["type"]["VALUE_XML_ID"] ?>">
                                
                            
                    <? endif; ?>
                        <li class="item" data-type="<?= $arItem["PROPERTIES"]["type"]["VALUE_XML_ID"] ?>">
                            <div class="checkbox">
                                <label class="h4">
                                    <input type="checkbox"
                                           name="document"
                                           id="doc_brands_<?= $i ?>"
                                           value="/download.php?id=<?= $arItem['PROPERTIES']['file']['VALUE'] ?>&name=<?= $arItem['NAME'] ?>.<?= $arItem['FILE']['EXTENTION'] ?>"
                                           data-file-id="<?= $arItem['ID'] ?>"
                                    >
                                    <span class="icon-acrobat"><?= $arItem['NAME'] ?> <i class="download-link fa fa-download"></i></span>
                                            <span class="h6 green">— .<?= $arItem['FILE']['EXTENTION'] ?>
                                                , <?= $arItem["FILE"]['FILE_SIZE'] ?> mb
                                            </span>
                                </label>
                            </div>
                        </li>
                <? endforeach; ?>
                </div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-12 aside aside-mobile">
        <div class="filter-stock">
            <a class="filter-stock__link js-filter-stock-mobile" href="#" title=""></a>
            <div class="reg-side-cnt padding">
                <form class="filter-documentation" action="">
                    <? if (!empty($arResult['FILTER']['TYPES']) && count($arResult['FILTER']['TYPES']) > 1): ?>
                        <h3>Тип документа</h3>
                        <? foreach ($arResult['FILTER']['TYPES'] as $key => $type): ?>
                            <div class="checkbox">
                                <label>
                                    <input
                                        type="checkbox"
                                        name="types[]"
                                        id="breands_<?= $type ?>"
                                        value="<?= $type ?>"
                                        <? if (in_array($type, $_REQUEST['types'])): ?> checked<? endif; ?>
                                    /><span class="checkbox-text"> <?= $type ?></span>
                                </label>
                            </div>
                        <? endforeach; ?>
                    <? endif; ?>
                    <h3>Название</h3>
                    <div class="field custom_label filter-documentation__input">
                        <input type="text" id="email" name="product" value="<?= $_REQUEST['product'] ?>">
                    </div>
                    <button class="btn btn-primary">Найти документы</button>
                    <a href="<?= $page ?>" class="btn btn-link">сбросить запрос</a>
                </form>
            </div>
        </div>
    </div>
</div>