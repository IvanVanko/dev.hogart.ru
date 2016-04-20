<?php

/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 20/04/16
 * Time: 14:24
 */
namespace Sprint\Migration;

use Sprint\Migration\Helpers\EventHelper;

class Version210420160001 extends Version
{
    protected $description = "Обновления для задач 46, 26, 28";

    public function up(){
        global $DB;

        $EVENT_USER_REGISTER_SUBJECT = "!!! Регистрация подтверждена! #EVENT_NAME#, #DATE#, #ADDRESS#";
        $EVENT_USER_REGISTER_BODY =<<<HTML
        Уважаемый(ая) #NAME# #SURNAME# #LAST_NAME#!<br /><br />
        Поздравляем, Вы утверждены как участник!<br />
        Мы будем рады видеть вас на мероприятии "#EVENT_NAME#" #DATE# по адресу: #ADDRESS#. <br /><br />
        Ваш входной билет во вложении, его необходимо распечатать (сохранить на телефоне) или показать смс, с подтверждением регистрации. <br /><br />
        #ORG_INFO#<br />
        С уважением, компания "Хогарт".<br />
        #URL#<br />
HTML;

        $helper = new EventHelper();
        $result = $helper->updateEventMessageByFilter([
            "TYPE_ID" => "EVENT_USER_REGISTER",
            "SITE_ID" => "s1"
        ], [
            "SUBJECT" => $EVENT_USER_REGISTER_SUBJECT,
            "MESSAGE" => $EVENT_USER_REGISTER_BODY,
            "BODY_TYPE" => "html"
        ]);
        if ($result) {
            $this->outSuccess('Почтовый шаблон EVENT_USER_REGISTER изменен');
        }
    }

    public function down(){
        return true;
    }
}