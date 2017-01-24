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
use Hogart\Lk\Entity\OrderEditTable;
use Hogart\Lk\Entity\OrderItemEditTable;
use Hogart\Lk\Helper\Template\FlashInfo;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Template\FlashWarning;
use Hogart\Lk\Helper\Template\FlashSuccess;

switch ($_REQUEST['action']) {
    case 'add_item_simple':
        $item = OrderItemEditTable::addNewItem(intval($_REQUEST['order_id']), (string)$_REQUEST['xml_id'], (string)$_REQUEST['item_group']);
        if ($item['ID']) {
            new FlashInfo(vsprintf("Позиция <b><u>%s</u></b> добавлена!", [$item['NAME']]));
        }
        break;
    case 'change_quantity':
        $result = OrderItemEditTable::changeQuantity(intval($_REQUEST['order_id']), (string)$_REQUEST['item_id'], (string)$_REQUEST['item_group'], intval($_REQUEST['quantity']));
        break;
    case 'change_discount':
        $result = OrderItemEditTable::changeDiscount(intval($_REQUEST['order_id']), (string)$_REQUEST['item_id'], (string)$_REQUEST['item_group'], floatval($_REQUEST['discount']));
        break;
    case 'change_note':
        $result = OrderEditTable::changeNote(intval($_REQUEST['order_id']), $_REQUEST['note']);
        break;
    case 'delete_items':
        OrderItemEditTable::softDelete(intval($_REQUEST['order_id']), $_REQUEST['item']);
        break;
    case 'set_max_discounts':
        OrderItemEditTable::setMaxDiscounts(intval($_REQUEST['order_id']));
        break;
    case 'apply_edit':
        try {
            OrderEditTable::applyChangesToOrder(intval($_REQUEST['order']));
            new FlashSuccess(vsprintf("Изменения по заказу <b><u>%s</u></b> сохранены", [OrderTable::showName(OrderTable::getRowById(intval($_REQUEST['order'])))]));
            LocalRedirect("/account/order/" . intval($_REQUEST['order']));
        } catch (Exception $e) {
            new FlashError("Произошла ошибка! " . $e->getMessage());
        }
        break;
    case 'cancel_edit':
        try {
            $result = OrderEditTable::delete(intval($_REQUEST['order']));
            new FlashWarning(vsprintf("Изменения по заказу <b><u>%s</u></b> отменены", [OrderTable::showName(OrderTable::getRowById(intval($_REQUEST['order'])))]));
            LocalRedirect("/account/order/" . intval($_REQUEST['order']));
        } catch (Exception $e) {
            new FlashError("Произошла ошибка!");
        }
        break;
}