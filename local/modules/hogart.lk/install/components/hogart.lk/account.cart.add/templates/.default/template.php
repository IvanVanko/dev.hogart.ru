<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
/** @ver array $companies */

use Hogart\Lk\Helper\Template\Cart;
use Hogart\Lk\Helper\Template\Ajax;
$this->setFrameMode(true);
?>
<div data-loader-wrapper="header.header-cnt" class="cart-counter" id="<?= Cart::getContainer() ?>">
    <? $node = Ajax::Start($component, [], null, Cart::init($component)); ?>
    <a href="<?= $arParams['CART_URL'] ?>">
        <i class="fa fa-shopping-cart"></i> <span class="counter"><?= ($arResult['count']) ?></span>
    </a>
    <? Ajax::End($node->getId()) ?>
</div>
