<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="padding">
                <h2><?=$arResult['PROPERTIES']['materials']['NAME'];?></h2>
                <ul class="ul-file">
                    <?foreach ($arResult["PROPERTIES"]["materials"]["DESCRIPTION"] as $key => $value):
                        $file = CFile::GetPath($arResult["PROPERTIES"]["materials"]['VALUE'][$key]);
                        ?>
                        <li>
                            <a href="<?=$file;?>"><?=$value?></a>
                            <span>— .jpg, 0.71 mb</span>
                        </li>
                    <?endforeach?>
                </ul>
                <h2>Об организаторе</h2>
                <div class="info-creator">
                    <div class="photo">
                        <img src="<?=CFile::GetPath($arResult['ORGS']['PREVIEW_PICTURE']);?>" alt=""/>
                        <h3><?=$arResult['ORGS']['NAME'];?></h3>
                    </div>

                    <div class="head"><?=$arResult['ORGS']['props']['status']['~VALUE'];?> / <?=$arResult['ORGS'][0]['props']['company']['VALUE'];?></div>
                    <ul class="contact">
                        <li class="phone"><?=$arResult['ORGS']['props']['phone']['VALUE'];?></li>
                        <li class="email"><a href="mailto:<?=$arResult['ORGS']['props']['mail']['VALUE'];?>"><?=$arResult['ORGS']['props']['mail']['VALUE'];?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
