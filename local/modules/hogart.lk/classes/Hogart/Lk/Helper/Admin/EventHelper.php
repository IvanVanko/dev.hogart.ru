<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 17/10/2016
 * Time: 11:01
 */

namespace Hogart\Lk\Helper\Admin;


class EventHelper extends AbstractHelper
{
    /**
     * @param $eventName
     * @param $fields array(), key LID = language id
     * @return bool|int
     * @throws \Sprint\Migration\Exceptions\HelperException
     */
    public function addEventTypeIfNotExists($eventName, $fields) {
        $this->checkRequiredKeys(__METHOD__, $fields, array('LID'));

        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $aItem = \CEventType::GetList(array(
            'TYPE_ID' => $eventName,
            'LID' => $fields['LID']
        ))->Fetch();

        if ($aItem) {
            return $aItem['ID'];
        }

        $default = array(
            "LID" => $fields['LID'],
            "EVENT_NAME" => 'event_name',
            "NAME" => 'NAME',
            "DESCRIPTION" => 'description',
        );

        $fields = array_merge($default, $fields);
        $fields['EVENT_NAME'] = $eventName;

        $event = new \CEventType;
        $id = $event->Add($fields);

        if ($id){
            return $id;
        }

        $this->throwException(__METHOD__, 'Event type %s not added', $eventName);
    }

    /**
     * @param $eventName
     * @param $fields array(), key LID = site id
     * @return int
     * @throws \Sprint\Migration\Exceptions\HelperException
     */
    public function addEventMessageIfNotExists($eventName, $fields) {
        $this->checkRequiredKeys(__METHOD__, $fields, array('SUBJECT'));

        $by = 'id';
        $order = 'asc';
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $aItem = \CEventMessage::GetList($by, $order, array(
            'TYPE_ID' => $eventName,
            'SUBJECT' => $fields['SUBJECT']
        ))->Fetch();

        if ($aItem) {
            return $aItem['ID'];
        }

        $default = array(
            'ACTIVE' => 'Y',
            'LID' => 's1',
            'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
            'EMAIL_TO' => '#EMAIL_TO#',
            'BCC' => '',
            'SUBJECT' => 'subject',
            'BODY_TYPE' => 'text',
            'MESSAGE' => 'message',
        );

        $fields = array_merge($default, $fields);
        $fields['EVENT_NAME'] = $eventName;

        $event = new \CEventMessage;
        $id = $event->Add($fields);

        if ($id) {
            return $id;
        }

        $this->throwException(__METHOD__, 'Event message %s not added, error: %s',$eventName, $event->LAST_ERROR);
    }
}