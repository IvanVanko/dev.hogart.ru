<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/10/2016
 * Time: 14:41
 *
 * @global $APPLICATION
 */

if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

define("NO_SPECIAL_CHARS_CHAIN", true);

use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\FlashMessagesTable;
use Hogart\Lk\Entity\CartItemTable;
use Hogart\Lk\Entity\OrderEditTable;
use Hogart\Lk\Helper\Template\FlashError;


global $USER, $CACHE_MANAGER;
$account = AccountTable::getAccountByUserID($USER->GetID());

if ($account['id']) {
    ini_set('output_buffering', 'Off');
    ini_set('zlib.output_compression', 'Off');
    $session_id = session_id();
    session_write_close();
    ignore_user_abort(true);
    ob_implicit_flush(true);

    while (@ob_end_clean());

    header('X-Accel-Buffering: no');
    header("Content-Type: text/event-stream");
    header("Cache-Control: no-cache");
    header("Access-Control-Allow-Origin: *");

    switch ((string)$_GET["action"]) {
        case 'edit_order':
            $timer = 15;
            while ($timer >= 0) {
                $order = OrderEditTable::getByField("order_id", $_GET['order_id']);
                echo "data: " . json_encode(['type' => 'edit_order_cancel', 'order_id' => $order['order_id']]) . PHP_EOL . PHP_EOL;
                echo PHP_EOL;
                ob_flush();
                flush();
                sleep(1);
                $timer--;
            }
            break;
        default:

            $timer = 0;
            $cart_count = CartItemTable::getAccountCartCount($account['id']);
            while (connection_status() == CONNECTION_NORMAL) {
                if ($timer % 3 == 0) {
                    $messages = FlashMessagesTable::getMessages($account['id'], 3);
                    if (!empty($messages)) {
                        foreach ($messages as $message) {
                            echo "data: " . $message->toJSON() . PHP_EOL;
                            echo "id: " . $message->getUnique() . PHP_EOL;
                            echo PHP_EOL;
                        }
                    }
                }

                if ($timer % 5 == 0) {
                    session_start($session_id);
                    $user_id = $USER->GetID();
                    session_write_close();

                    $account = AccountTable::getAccountByUserID($user_id);
                    echo "data: " . json_encode(['type' => 'heartbeat', 'account' => (int)$account['id']]) . PHP_EOL . PHP_EOL;
                }

                if ($cart_count != ($new_cart_count = CartItemTable::getAccountCartCount($account['id']))) {
                    echo "data: " . json_encode(['type' => 'cart_counter', 'count'=> $new_cart_count]) . PHP_EOL . PHP_EOL;
                    $cart_count = $new_cart_count;
                }

                ob_flush();
                flush();
                sleep(1);
                $timer++;
            }

            break;
    }

    exit(0);
} else {
    while (@ob_end_clean());
    header("Content-Type: text/event-stream");
    header("HTTP/1.1 401 Unauthorized");
    exit;
}
