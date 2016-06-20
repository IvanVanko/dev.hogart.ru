<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/05/16
 * Time: 16:50
 */

namespace Sprint\Migration;


class Version201606170001 extends Version
{
    protected $description = "Обновление Почтового шаблона для Формы расшарить по email";

    public function up()
    {
        if(!\CModule::IncludeModule("form")) {
            $this->outError("Отсутствует модуль Form");
        } else {
            // добавляем дополнительные поля
            // EN
            $formEN = (new \CForm)->GetByID("SHARE_EMAIL_EN", "Y")->GetNext();

            $formRU = (new \CForm)->GetByID("SHARE_EMAIL_RU", "Y")->GetNext();
            foreach([$formEN,$formRU] as $form) {
                if (\CFormField::GetList($form['ID'], "", $by, $order, ['SID' => 'PAGE_URL|SENDTO_TITLE'])->SelectedRowsCount() == 0) {
                    $page_url = (new \CFormField)->Set([
                        "TITLE" => "URL страницы",
                        "SID" => "PAGE_URL",
                        "VAR_NAME" => "PAGE_URL",
                        "REQUIRED" => "N",
                        "IN_RESULTS_TABLE" => "Y",
                        'FIELD_TYPE' => '',
                        "FORM_ID" => $form['ID'],
                    ]);

                    $page_title = (new \CFormField)->Set([
                        "TITLE" => "Заголовок страницы",
                        "SID" => "SENDTO_TITLE",
                        "VAR_NAME" => "SENDTO_TITLE",
                        "REQUIRED" => "N",
                        "IN_RESULTS_TABLE" => "Y",
                        'FIELD_TYPE' => '',
                        "FORM_ID" => $form['ID'],
                    ]);
                }

                if($fieldEmail = \CFormField::GetList($form['ID'], "", $by, $order, ['SID'=>'SIMPLE_QUESTION_183'])->GetNext()){
                    \CFormField::Set([
                        "SID" => "SENDTO_EMAIL",
                        "TITLE" => $fieldEmail["TITLE"],
                        "FORM_ID" => $fieldEmail["FORM_ID"],
                    ], $fieldEmail["ID"]);
                }

            }

            // Обновляем шаблоны формы SHARE_EMAIL_RU
            $arFilter = [
//                "ID" => '122|203',// 'MAKE_REQUEST_EN'],
                "EVENT_NAME" => 'FORM_FILLING_SHARE_EMAIL_RU',// 'MAKE_REQUEST_EN'],
                "SID_EXACT_MATCH" => 'Y',
            ];
            $rsEventMsgs = (new \CEventMessage)->GetList($by="s_id", $order="desc", $arFilter);
            while ($arEventMsg = $rsEventMsgs->Fetch())
            {
                if($arEventMsg['LID'] == 'en'){
                    $message = <<< TEMPLATE
Hello!

You Shared page "# SENDTO_TITLE_RAW #".

Page can me found at <a href="#PAGE_URL_RAW#"> link </a>.TEMPLATE;
TEMPLATE;

                }else {
                    $message = <<< TEMPLATE
Добрый день!

Вы поделились страницей "#SENDTO_TITLE_RAW#". 

С ее содержимым можно ознакомиться по <a href="#PAGE_URL_RAW#">ссылке</a>.
TEMPLATE;
                }

                (new \CEventMessage)->Update($arEventMsg['ID'], [
                    "CC"=> null,
                    "EMAIL_TO"=>'#SENDTO_EMAIL#',
                    "MESSAGE"=> $message
                ]);
            }
        }
    }
}
