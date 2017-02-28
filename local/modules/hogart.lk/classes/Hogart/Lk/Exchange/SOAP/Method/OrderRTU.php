<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 06/10/2016
 * Time: 18:41
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Iblock\ElementTable;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\AddResult;
use Bitrix\Main\Entity\UpdateResult;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactTable;
use Hogart\Lk\Entity\FlashMessagesTable;
use Hogart\Lk\Entity\OrderEventTable;
use Hogart\Lk\Entity\OrderRTUItemTable;
use Hogart\Lk\Entity\OrderRTUTable;
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\AbstractPutRequest;
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;
use Hogart\Lk\Helper\Template\Message;

class OrderRTU extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "OrderRTU";
    }

    public function orderRTUPut(AbstractPutRequest $request)
    {
        $response = $this->client->getSoapClient()->OrderRTUPut($request->__toRequest());
        foreach ($response->return->Response as $order_rtu) {
            OrderRTUTable::update($order_rtu->ID_Site, [
                'guid_id' => $order_rtu->ID
            ]);
        }
        return $response;
    }

    public function ordersRTUGet()
    {
        return $this->client->getSoapClient()->OrdersRTUGet(new Request());
    }

    public function ordersRTUAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->OrdersRTUAnswer($response);
        }
    }

    public function updateOrdersRTU()
    {
        global $DB;

        $answer = new Response();
        $response = $this->ordersRTUGet();
        foreach ($response->return->Order_RTU as $order_rtu) {
	        $contact = ContactTable::getByField('guid_id', $order_rtu->Ord_RTU_ID_Contact);

            $DB->StartTransaction();
            $result = OrderRTUTable::createOrUpdateByField([
                "guid_id" => $order_rtu->Ord_RTU_ID,
                "number" => (string)$order_rtu->Ord_RTU_Number,
                "status" => (int)$order_rtu->Ord_RTU_Status,
                "refuse_reason" => (string)$order_rtu->Ord_ReasonForRefusal,
                "rtu_date" => new DateTime((string)$order_rtu->Ord_RTU_Date, 'Y-m-d H:i:s'),
                "delivery_type" => intval($order_rtu->Ord_RTU_Delivery),
                "store_guid" => (string)$order_rtu->Ord_RTU_Warehouse_ID,
                "address_guid" => (string)$order_rtu->Ord_RTU_Address,
                "plan_date" => new Date($order_rtu->Ord_RTU_PlanDate, 'Y-m-d'),
                "plan_time" => (string)$order_rtu->Ord_RTU_PlanTime,
                "contact_id" => intval($contact['id']),
                "email" => (string)$order_rtu->Ord_RTU_Email,
                "phone" => (string)$order_rtu->Ord_RTU_Phone_Contact,
                "is_sms_notify" => (bool)$order_rtu->Ord_RTU_SendSMS,
                "is_email_notify" => (bool)$order_rtu->Ord_RTU_SendEmail,
                "is_tk" => (bool)$order_rtu->Ord_RTU_TK,
                "tk_name" => (string)$order_rtu->Ord_RTU_TK_Name,
                "tk_address" => (string)$order_rtu->Ord_RTU_TK_Address,
                "driver_name" => (string)$order_rtu->Ord_RTU_Driver,
                "driver_phone" => (string)$order_rtu->Ord_RTU_Driver_Phone,
                "is_active" => !$order_rtu->deletion_mark,
                "note" => (string)$order_rtu->Ord_RTU_Description
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()])));
                $DB->Rollback();
                continue;
            } else {
                if ($result->getId()) {
                    $orders = [];
                    $order = null;
                    if (!OrderRTUItemTable::clearItems($result->getId())) {
                        $DB->Rollback();
                        $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                        continue;
                    }

                    foreach ($order_rtu->Ord_RTU_Items as $i => $rtu_item) {
                        if (null === $order || $order['guid_id'] != $rtu_item->Order_ID) {

                            $order = OrderTable::getByField('guid_id', $rtu_item->Order_ID);

                            if (empty($order['id'])) {
                                throw new MethodException(MethodException::ERROR_NO_ORDER, [$rtu_item->Order_ID]);
                            }
                            $orders[] = $order;
                        }

                        $item = ElementTable::getList([
                            'filter'=>[
                                '=XML_ID' => $rtu_item->ID_Item,
                                '=IBLOCK.ID' => new SqlExpression('?i', CATALOG_IBLOCK_ID)
                            ]
                        ])->fetch();
                        if (!isset($item)) {
                            $DB->Rollback();
                            throw new MethodException(MethodException::ERROR_NO_ITEM, [$rtu_item->Order_ID, $i]);
                        }

                        $rtu_item_result = OrderRTUItemTable::createOrUpdateByField([
                            "order_rtu_id" => $result->getId(),
                            "order_id" => $order['id'],
                            "guid_id" => $rtu_item->RTU_Item_ID,
                            "item_id" => $item['ID'],
                            "item_group" => (string)$rtu_item->Group,
                            "count" => intval($rtu_item->Count),
                            "price" => floatval($rtu_item->Cost),
                            "discount" => floatval($rtu_item->Discount),
                            "discount_price" => floatval($rtu_item->Cost_Disc),
                            "total" => floatval($rtu_item->Summ),
                            "total_vat" => floatval($rtu_item->Sum_VAL),
                        ], "guid_id");

                        if (empty($rtu_item_result->getId())) {
                            $exception = new MethodException(MethodException::ERROR_UNDEFINED);
                            $this->client->getLogger()->error($exception->getMessage());
                            continue;
                        }
                    }

                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Заявка на отгрузку {$result->getId()} ({$order_rtu->Ord_RTU_ID})");
                    }

                    if ($result instanceof AddResult) {
                        foreach ($orders as $order) {
                            $accounts = OrderTable::getAccountsByOrder($order['id']);

                            if (empty($accounts)) continue;

                            OrderEventTable::add([
                                'entity_id' => $result->getId(),
                                'event' => OrderEventTable::ORDER_EVENT_ORDER_RTU_CREATE,
                                'order_id' => $order['id'],
                            ]);

                            $message = new Message(
                                OrderTable::showName($order) . " обновлен! Получена заявка на отгрузку",
                                Message::SEVERITY_INFO
                            );
                            $message
                                ->setIcon('fa fa-file-text-o')
                                ->setUrl("/account/order/" . $order['id'])
                                ->setDelay(0)
                            ;

                            foreach ($accounts as $account) {
                                $flash_result = FlashMessagesTable::addNewMessage($account['a_id'], $message);
                                if ($flash_result->getId()) {
                                    $this->client->getLogger()->notice("Добавлено оповещение по заказу ({$order['id']})");
                                }
                            }
                        }
                    }
                    $DB->Commit();
                    $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID));
                } else {
                    $answer->addResponse(new ResponseObject($order_rtu->Ord_RTU_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->ordersRTUAnswer($answer);
        return count($answer->Response);
    }
}
