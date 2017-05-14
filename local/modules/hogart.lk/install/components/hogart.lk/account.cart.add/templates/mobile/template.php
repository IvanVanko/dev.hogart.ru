<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Hogart\Lk\Helper\Template\Cart;
use Hogart\Lk\Helper\Template\Ajax;
$this->setFrameMode(true);
?>

<div class="b-header__cart">
    <div data-loader-wrapper="header.header-cnt" class="cart-counter2" id="<?= Cart::getContainer() ?>">
        <? $node = Ajax::Start($component, [], null, Cart::init($component)); ?>
        <a class="header-mobile__cart" href="<?= $arParams['CART_URL'] ?>">
            <span class="icon">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            </span>
            <span class="counter"><?= ($arResult['count']) ?></span>
        </a>
        <? Ajax::End($node->getId()) ?>
    </div>
</div>
