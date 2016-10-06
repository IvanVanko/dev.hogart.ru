<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/09/16
 * Time: 04:45
 *
 * @global $APPLICATION
 * @var $this CBitrixComponent
 * @var $arParams array
 * @var $arResult array
 *
 */

use Hogart\Lk\PhpExcel\SkuReadFilter;
use Hogart\Lk\Helper\Template\Ajax;
use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Helper\Template\FlashInfo;
use Hogart\Lk\Entity\CartTable;
use Hogart\Lk\Entity\OrderTable;

if (
    !empty($_REQUEST['cart_id'])
    && (
        Ajax::isAjax(Ajax::GetAjaxId($this, ['step1']))
        || Ajax::isAjax(Ajax::GetAjaxId($this, ['cart_id' => $_REQUEST['cart_id']]))
    )
) {

    switch ($_REQUEST['action']) {
        case 'create_order':
            $orderId = OrderTable::createByCart($_REQUEST['cart_id']);
            if ($orderId) {
                CartTable::delete($_REQUEST['cart_id']);
            }
            break;
        case 'upload_sku':
            $APPLICATION->RestartBuffer();
            $initialPreviewConfig = [];
            foreach ($_FILES['sku']['name'] as $k => $name) {
                $tempName = tempnam(HOGART_TMP_DIR, "sku_") . "_" . $name;
                move_uploaded_file($_FILES['sku']['tmp_name'][$k], $tempName);
                $initialPreviewConfig[] = [
                    'caption' => $name,
                    'size' => $_FILES['sku']['size'][$k],
                    'key' => ($k + 1),
                    'url' => $tempName
                ];
            }
            echo json_encode([
                'append' => true,
                'initialPreviewConfig' => $initialPreviewConfig
            ]);
            exit;
            break;
        case 'add_items_file':
            ini_set("mbstring.func_overload", 0);
            $items = [];
            foreach ($_REQUEST['file'] as $fileName) {
                /** @var PHPExcel_Reader_Abstract $reader */
                $reader = PHPExcel_IOFactory::createReaderForFile($fileName);
                $filter = new SkuReadFilter($_REQUEST['sku_column'], $_REQUEST['count_column']);
                $reader->setReadFilter($filter);
                $reader->setReadDataOnly(true);
                $excel = $reader->load($fileName);
                foreach ($excel->getActiveSheet()->getRowIterator() as $row) {
                    /** @var PHPExcel_Cell $cell */
                    foreach ($row->getCellIterator() as $cell) {
                        if (strtolower($cell->getColumn()) == strtolower($_REQUEST['sku_column'])) {
                            $sku = $cell->getFormattedValue();
                        } else {
                            $count = $cell->getValue();
                        }
                    }
                    $items[$sku] += $count;
                }
            }
            foreach ($items as $sku => $count) {
                $result = CartTable::addItemToCartBySku($_REQUEST['cart_id'], $sku, $count, $_REQUEST['item_group']);
                if ($result) {
                    new FlashInfo(vsprintf("Товар с артикулом %s добавлен", [$sku]));
                }
            }
            break;
        case 'add_item_simple':
            $result = CartTable::addItemToCartBySku($_REQUEST['cart_id'], $_REQUEST['sku'], 1, $_REQUEST['item_group']);
            if ($result) {
                new FlashInfo(vsprintf("Товар с артикулом %s добавлен", [$_REQUEST['sku']]));
            }
            break;
        case 'change_contract':
            CartTable::changeContract($_REQUEST['cart_id'], $_REQUEST['contract_id']);
            break;
        case 'change_store':
            CartTable::changeStore($_REQUEST['cart_id'], $_REQUEST['store_id']);
            break;
        case 'change_category':
            CartTable::changeCategory($_REQUEST['cart_id'], $_REQUEST['new_item_group'], $_REQUEST['item_group']);
            break;
        case 'change_cart_order':
            CartTable::changeOrder($_REQUEST['cart_id'], $_REQUEST['new_order']);
            break;
        case 'change_quantity':
            CartTable::changeCount($_REQUEST['cart_id'], $_REQUEST['item_id'], $_REQUEST['quantity']);
            break;
        case 'edit_items_group':
            CartTable::changeCategory(
                $_REQUEST['cart_id'],
                $_REQUEST['new_item_group'],
                null,
                $_REQUEST['item'],
                (bool)$_REQUEST['copy'],
                $_REQUEST['contract'],
                $_REQUEST['store']
            );
            break;
        case 'delete_items':
            CartTable::deleteItems($_REQUEST['cart_id'], $_REQUEST['item']);
            break;
        case 'delete_nostock_items':
            CartTable::deleteNoStockItems($_REQUEST['cart_id']);
            break;
    }
}
