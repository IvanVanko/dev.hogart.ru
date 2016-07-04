<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $page = $APPLICATION->GetCurDir(true); ?>

<div class="row">
    <div class="col-md-9">
        <h3><?= \Bitrix\Main\Localization\Loc::getMessage("doc_title") ?></h3>
        <div class="row">
            <div class="col-md-6">
                <? $count = count($arResult['ITEMS']) ?>
                <h3>Найдено <?= $count ?> <?= number($count, array('документ', 'документа', 'документов')); ?></h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="/arch/" class="icon-doc-sc">Скачать выбранные</a>
            </div>
        </div>
        <?
        $i = 1;
        foreach ($arResult["BRANDS"] as $brand => $arBrand):
            ?>
            <ul class="doc-loadlist doc-box">
                <li 
                    data-toggle="collapse" 
                    data-target="#documents-<?= $brand ?>" 
                    aria-expanded="false" 
                    class="h4 li-container head<? if ($_REQUEST['fbrand'] == 'y'): ?> active<? endif ?>">
                    <input type="checkbox"
                           id="breands_<?= $i ?>"
                           name="breands_<?= $i ?>"
                    >
                    <span><?= !empty($brand) ? $brand : 'Без бренда' ?></span>
                </li>
                <div class="collapse" id="documents-<?= $brand ?>">
                    <? $i++; ?>
                    <? foreach ($arBrand as $type => $arType): ?>
                        <div class="item-box">
                            <li class="li-container head_sub<? if ($_REQUEST['fbrand'] == 'y'): ?> active<? endif ?>"
                                style="display: <? if ($_REQUEST['fbrand'] == 'y'): ?>list-item<? else: ?><? endif ?>;">
                                <div class="checkbox">
                                    <label class="h4">
                                        <input type="checkbox"
                                               name="doc_brands_<?= $i ?>"
                                               id="doc_brands<?= $i ?>"
                                        > <?= $type ?> (<?= count($arType) ?>)
                                    </label>
                                </div>
                            </li>
                            <? $i++; ?>
                            <? foreach ($arType as $arItem): ?>
                                <li class="item"
                                    style="display: <? if ($_REQUEST['fbrand'] == 'y'): ?>list-item<? else: ?><? endif ?>;">
                                    <div class="checkbox">
                                        <label class="h5">
                                            <input type="checkbox"
                                                   name="document"
                                                   id="doc_brands<?= $i ?>"
                                                   value="/download.php?id=<?= $arItem['PROPERTIES']['file']['VALUE'] ?>&name=<?= $arItem['NAME'] ?>.<?= $arItem['FILE']['EXTENTION'] ?>"
                                                   data-file-id="<?= $arItem['ID'] ?>"
                                            >
                                            <span class="icon-acrobat"><?= $arItem['NAME'] ?></span>
                                            <span class="green">— .<?= $arItem['FILE']['EXTENTION'] ?>
                                                , <?= $arItem["FILE"]['FILE_SIZE'] ?> mb <i class="download-link fa fa-download"></i>
                                            </span>
                                        </label>
                                    </div>
                                </li>
                                <? $i++; ?>
                            <? endforeach; ?>
                        </div>
                    <? endforeach; ?>
                </div>
            </ul>
        <? endforeach; ?>
    </div>
    <div class="col-md-3">
        <div class="reg-side-cnt padding">
            <form action="">
                <? if (!empty($arResult['FILTER']['TYPES'])): ?>
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
                                /> <?= $type ?>
                            </label>
                        </div>
                    <? endforeach; ?>
                <? endif; ?>
                <? if (!empty($arResult['FILTER']['BRANDS'])): ?>
                    <h3>Бренд</h3>
                    <div id="brandsContainer" class="row breands hide-big-cnt" data-hide="Еще бренды">
                        <? $_counter = 0; ?>
                        <? foreach ($arResult['FILTER']['BRANDS'] as $brandId => $brandName): ?>
                            <div class="col-md-6 <?= (($_counter > 3 && !in_array($brandId, $_REQUEST['brands'])) ? "more" : "")?>">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"
                                               name="brands[]"
                                               id="breands_<?= $brandId ?>"
                                               value="<?= $brandId ?>"
                                            <? if (in_array($brandId, $_REQUEST['brands'])): ?> checked<? endif; ?>
                                        > <?= $brandName ?>
                                    </label>
                                </div>
                            </div>
                            <? $_counter++; ?>
                        <? endforeach; ?>
                        <? if ($_counter > 4): ?>
                            <div class="col-sm-12">
                                <span class="btn-more" rel="brandsContainer">Еще <i class="fa"></i></span>
                            </div>
                        <? endif; ?>
                    </div>
                <? endif; ?>
                <h3>Название</h3>
                <div class="field custom_label">
                    <input type="text" id="email" name="product" value="<?= $_REQUEST['product'] ?>">
                </div>
                <button class="btn btn-primary">Найти документы</button>
                <a href="<?= $page ?>" class="btn btn-link">сбросить запрос</a>
            </form>
        </div>
    </div>
</div>