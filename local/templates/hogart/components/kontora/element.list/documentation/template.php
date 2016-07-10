<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $page = $APPLICATION->GetCurDir(true); ?>

<div class="row">
    <div class="col-md-9">
        <h3><?= $APPLICATION->GetTitle() ?></h3>
        <div class="row">
            <div class="col-md-6">
                <? $count = count($arResult['ITEMS']) ?>
                <div class="h5">Найдено <?= $count ?> <?= number($count, array('документ', 'документа', 'документов')); ?></div>
            </div>
            <div class="col-md-6 text-right">
                <a href="/arch/" class="icon-doc-sc">Скачать выбранные</a>
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
                                    <label class="h3 pull-left">
                                        <input type="checkbox"
                                               name="doc_brands_<?= $i ?>"
                                               id="doc_brands_<?= $i ?>"
                                        > 
                                    </label>
                                    <h3 class="pull-left" data-toggle="collapse" data-target="#<?= $arItem["PROPERTIES"]["type"]["VALUE_XML_ID"] ?>" aria-expanded="true" aria-controls="collapseTypes">
                                        <?= $type ?> (<?= $arResult['TYPE_COUNTS'][$type] ?>)
                                    </h3>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                            <div style="overflow: hidden;" class="collapse in" aria-expanded="true" id="<?= $arItem["PROPERTIES"]["type"]["VALUE_XML_ID"] ?>">
                                
                            
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
                                    <span class="icon-acrobat"><?= $arItem['NAME'] ?></span>
                                            <span class="h6 green">— .<?= $arItem['FILE']['EXTENTION'] ?>
                                                , <?= $arItem["FILE"]['FILE_SIZE'] ?> mb <i class="download-link fa fa-download"></i>
                                            </span>
                                </label>
                            </div>
                        </li>
                <? endforeach; ?>
                </div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 aside">
        <div class="reg-side-cnt padding">
            <form action="">
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
                                /> <?= $type ?>
                            </label>
                        </div>
                    <? endforeach; ?>
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