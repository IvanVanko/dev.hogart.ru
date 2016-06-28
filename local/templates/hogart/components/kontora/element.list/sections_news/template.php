<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
global $USER;
?>

<? if (!empty($arResult['ITEMS'])): ?>
    <div class="row sections-news">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="title"><a href="<?= $arParams["LINK"] ?>"><?= $arParams["BLOCK_TITLE"] ?></a></h4>
                </div>
                <div class="col-md-6">
                    <h6 class="more">
                        <a href="<?= $arParams["LINK"] ?>"><?= GetMessage("смотреть все") ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                    </h6>
                </div>
            </div>
            <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                $date = explode('.', $arItem['ACTIVE_FROM']);
                $date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"]));
                ?>
                <div class="row news-item">
                    <div class="col-md-12">
                        <? if (!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y'): ?>
                            <a class="profile-url js-popup-open" href="javascript:" data-popup="#popup-login">
                                <div class="date">
                                    <div><?= $date_from ?></div>
                                </div>
                                <p><?= $arItem['NAME'] ?></p>
                                <p><?= GetMessage("Для прочтения необходима авторизация на сайте") ?></p>
                            </a>
                        <? else: ?>
                            <a id="<?= $this->GetEditAreaId($arItem['ID']); ?>" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                <div class="date">
                                    <div><?= $date_from ?></div>
                                </div>
                                <p><?= $arItem['NAME'] ?></p>
                            </a>
                        <? endif; ?>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>