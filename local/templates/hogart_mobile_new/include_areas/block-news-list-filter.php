<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?Global $arResult;?>
<?
#DebugMessage($arResult);
?>

<div class="main-filter news-filter">
    <div class="btn show-filter-btn open-next"></div>
    <form action="#" class="main-filter-form hidden_block open-block action_filter">
        <div class="filter-block">
            <input type="reset" class="input-btn gray-btn" value="сбросить">
        </div>
        <?if (count($arResult["FILTER"]["TAG"]) > 0):?>
            <div class="filter-block">
            <? foreach ($arResult["FILTER"]["TAG"] as $key => $tag): ?>
                <input type="checkbox" id="checkbox_<?= $key ?>" name="tag[<?= $tag["PROPERTY_TAG_VALUE_ENUM_ID"] ?>]" class="custom_checkbox tag<?=$tag['PROPERTY_TAG_ENUM_ID']?>" value="<?= $tag["PROPERTY_TAG_VALUE_VALUE"] ?>" <? if (isset($_REQUEST["tag"][$tag["PROPERTY_TAG_VALUE_ENUM_ID"]])): ?> checked<? endif; ?> />
                <label for="checkbox_<?= $key ?>"><?= $tag["PROPERTY_TAG_VALUE_VALUE"] ?></label>
            <? endforeach; ?>   
            </div>
        <?endif?>
        <div class="filter-block">
            <p class="block-title">Фильтр по продукции</p>
            <?if(count($arResult["FILTER"]["DIRECTIONS"]) > 0):?>
            <select name="direction">
                <option value="">Выбрать направление</option>
                <? foreach ($arResult["FILTER"]["DIRECTIONS"] as $direction): ?>
                    <option value="<?= $direction["ID"] ?>"<? if ($_REQUEST["direction"] == $direction["ID"]): ?> selected<? endif ?>><?= $direction["NAME"] ?></option>
                <? endforeach ?>
            </select>
            <? foreach ($arResult["FILTER"]["DIRECTIONS"] as $direction): ?>
                <input type="hidden" name="direction_<?= $direction['ID'] ?>_left" value=<?= $direction['LEFT_MARGIN'] ?> />
                <input type="hidden" name="direction_<?= $direction['ID'] ?>_right" value=<?= $direction['RIGHT_MARGIN'] ?> />
            <? endforeach; ?>
            <?endif?>
            <?if(count($arResult["FILTER"]["TYPES"]) > 0):?>
            <select name="catalog_section" id="catalog_section">
                <option value="">Выбрать тип товара</option>
                <? foreach ($arResult["FILTER"]["TYPES"] as $type): ?>
                <option value="<?= $type["ID"] ?>"<? if ($_REQUEST["catalog_section"] == $type["ID"]): ?> selected<? endif ?>><?= $type["VALUE"] ?></option>
                <? endforeach ?>
            </select>
            <?endif?>
            <?if(count($arResult["FILTER"]["BRANDS"]) > 0):?>
            <select name="brand" id="brand">
                <option value="">Выбрать бренд</option>
                <? foreach ($arResult["FILTER"]["BRANDS"] as $brand): ?>
                <option value="<?= $brand["ID"] ?>"<? if ($_REQUEST["brand"] == $brand["ID"]): ?> selected<? endif ?>><?= $brand["VALUE"] ?></option>
                <? endforeach; ?>
            </select>
            <?endif?>
            
        </div>
        <input type="submit" class="input-btn gray-btn" value="Показать результаты">
    </form>
</div>