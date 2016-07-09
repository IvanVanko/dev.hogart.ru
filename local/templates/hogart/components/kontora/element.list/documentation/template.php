<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $page = $APPLICATION->GetCurDir(true); ?>

<div class="row">
    <div class="col-md-9">
        <h3><?= $APPLICATION->GetTitle() ?></h3>
        <div class="row">
            <div class="col-md-6">
                <? $count = count($arResult['ITEMS']) ?>
                <h3>Найдено <?= $count ?> <?= number($count, array('документ', 'документа', 'документов')); ?></h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="/arch/" class="icon-doc-sc">Скачать выбранные</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 doc-loadlist">
                <? foreach ($arResult["DOCUMENTATION"] as $type => $arType): ?>
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
        </div>
    </div>
    <div class="col-md-3">
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