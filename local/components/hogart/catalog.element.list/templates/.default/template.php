<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/06/16
 * Time: 12:56
 */
?>

<? if (!empty($arResult["PREV_LINK"])): ?>
<a href="<?= $arResult["PREV_LINK"] ?>">
    Prev
</a>
<? endif; ?>

<? if (!empty($arResult["NEXT_LINK"])): ?>
<a href="<?= $arResult["NEXT_LINK"] ?>">
    Next
</a>
<? endif; ?>