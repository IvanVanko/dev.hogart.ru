<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 15:31
 *
 * @global $APPLICATION
 * @var $this CBitrixComponent
 * @var array $arResult
 *
 */
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\OrderPaymentTable;
use Hogart\Lk\Entity\PdfTable;
use Hogart\Lk\Helper\Template\FlashSuccess;

switch ($_REQUEST['action']) {
    case 'order-payment':
        $result = OrderPaymentTable::createPaymentByUser(
            $arResult['order']['id'],
            intval($_REQUEST['payment_type']),
            floatval($_REQUEST['release_sum']),
            $this
        );
        LocalRedirect($APPLICATION->GetCurPage(false));
        break;
    case 'order-kp':
        if (empty($arResult['order']['pdf'][PdfTable::TYPE_KP])) {
            PdfTable::pdfRequest($arResult['order']['guid_id'], PdfTable::TYPE_KP);
            new FlashSuccess("Вы будете уведомлены по готовности коммерческого предложения!");
            LocalRedirect($APPLICATION->GetCurPage(false));
        } else {
            LocalRedirect("/account/orders/pdf/" . $arResult['order']['id'] . "/" . $arResult['order']['pdf'][PdfTable::TYPE_KP]['guid_id'] . "/");
        }

        break;

}