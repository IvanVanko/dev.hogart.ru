<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/04/16
 * Time: 14:24
 */
namespace Sprint\Migration;

use Sprint\Migration\Helpers\EventHelper;
use Sprint\Migration\Helpers\IblockHelper;

class Version210420160001 extends Version
{
    protected $description = "Обновления для задач 46, 26, 28";

    public function up(){
        global $DB;

        $EventHelper = new EventHelper();
        $events = [
            'EVENT_USER_REGISTER' => [
                'SUBJECT' => "Регистрация подтверждена! #EVENT_NAME#, #DATE#, #ADDRESS#",
                'MESSAGE' => <<<HTML
                    Уважаемый(ая) #NAME# #SURNAME# #LAST_NAME#!<br /><br />
                    Поздравляем, Вы утверждены как участник!<br />
                    Мы будем рады видеть вас на мероприятии "#EVENT_NAME#" #DATE# по адресу: #ADDRESS#. <br /><br />
                    Ваш входной билет во вложении, его необходимо распечатать (сохранить на телефоне) или показать смс, с подтверждением регистрации. <br /><br />
                    #ORG_INFO#<br />
                    С уважением, компания "Хогарт".<br />
                    #URL#<br />                
HTML
            ],
            "EVENT_USER_REGISTER_DENIED" => [
                "SUBJECT" => "Информация о регистрации на мероприятие #EVENT_NAME#",
                "MESSAGE" => <<<HTML
                    #TEXT#<br />
                    #URL#<br />
HTML
            ],
            "EVENT_USER_REGISTER_MODERATE" => [
                "SUBJECT" => "#EVENT_NAME#",
                "MESSAGE" => <<<HTML
                    Благодарим Вас за проявленный интерес к нашему мероприятию. <br />
                    Ваша заявка принята и находится на рассмотрении.<br />
                    #ORG_INFO#<br />
                    #URL#<br />
HTML
            ]
        ];

        foreach ($events as $eventName => $eventFields) {
            if ($EventHelper->updateEventMessageByFilter([
                "TYPE_ID" => $eventName,
                "SITE_ID" => "s1"
            ], array_merge([
                "BODY_TYPE" => "html"
            ], $eventFields))) {
                $this->outSuccess("Почтовый шаблон {$eventName} изменен");
            }
        }


        if ((new \CIBlock())->Update(11, [
            "DETAIL_PAGE_URL" => "#SITE_DIR#/helpful-information/#ELEMENT_ID#/"
        ])) {
            $this->outSuccess("Обновлено значение \"URL страницы детального просмотра\" в Инфоблок \"Полезная инфомация\"");
        }

        $IblockHelper = new IblockHelper();
        if ($IblockHelper->deletePropertyIfExists(26, "INVITATION")) {
            $this->outSuccess("Удалено свойство \"Приглашен\" в Инфоблок \"Регистрации на мероприятия\"");
        }
        if ($IblockHelper->deletePropertyIfExists(26, "DENIED")) {
            $this->outSuccess("Удалено свойство \"Отказ регистрации\" в Инфоблок \"Регистрации на мероприятия\"");
        }
        $IblockHelper->updatePropertyIfExists(26, "EVENT", [
            "PROPERTY_TYPE" => "E",
            "USER_TYPE" => "EAutocomplete"
        ]);

        if (($id = $IblockHelper->addPropertyIfNotExists(26, [
            "CODE" => "STATUS",
            "NAME" => "Статус",
            "ACTIVE" => "Y",
            "PROPERTY_TYPE" => "L",
            "FILTRABLE" => "Y",
            "VALUES" => [
                "INVITATION" => [
                    "VALUE" => "Приглашен",
                    "XML_ID" => "INVITATION"
                ],
                "DENIED" => [
                    "VALUE" => "Отказано в регистрации",
                    "XML_ID" => "DENIED"
                ]
            ]
        ])) && $id > 0) {
            $this->outSuccess("Добавлено свойство \"Статус\" в Инфоблок \"Регистрации на мероприятия\"");
        }
    }

    public function down(){
        return true;
    }
}